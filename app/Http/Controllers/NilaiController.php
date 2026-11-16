<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\NilaiEkstrakurikuler;
use App\Models\PengaturanPredikat;
use App\Models\ProfilSekolah;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('rombel')->get();
        $tahunAjaranList = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();

        $kelas_id = $request->kelas_id;
        $mapel_id = $request->mapel_id;
        $tahun_ajaran_id = $request->tahun_ajaran_id ?? TahunAjaran::getActive()?->id;
        $mapelList = MataPelajaran::untukKelas($kelas_id, $tahun_ajaran_id)->where('status_aktif', true)->orderBy('nama_mapel')->get();
        if ($mapel_id && ! $mapelList->contains('id', $mapel_id)) {
            $mapel_id = null;
        }
        $mode = $request->query('mode') === 'ekstrakurikuler' ? 'ekstrakurikuler' : 'pelajaran';
        $ekstrakurikulerList = collect();
        $nilaiList = collect();
        $siswaList = collect();

        if ($kelas_id && $tahun_ajaran_id) {
            $siswaList = Siswa::diRombel($kelas_id, TahunAjaran::find($tahun_ajaran_id)?->tahun_ajaran)->orderBy('nama')->get();
            $ekstrakurikulerList = NilaiEkstrakurikuler::whereIn('siswa_id', $siswaList->pluck('id'))
                ->where('tahun_ajaran_id', $tahun_ajaran_id)->orderBy('id')->get()->groupBy('siswa_id');
            $nilaiList = Nilai::where('kelas_id', $kelas_id)
                ->where('mata_pelajaran_id', $mapel_id)
                ->where('tahun_ajaran_id', $tahun_ajaran_id)
                ->get()->keyBy('siswa_id');
        }

        $aturanPredikat = PengaturanPredikat::untuk($kelas_id, $tahun_ajaran_id);

        return Inertia::render('Nilai/Index', compact('kelasList', 'mapelList', 'tahunAjaranList', 'kelas_id', 'mapel_id', 'tahun_ajaran_id', 'nilaiList', 'siswaList', 'mode', 'ekstrakurikulerList', 'aturanPredikat'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => ['required', Rule::exists('mata_pelajaran', 'id')->where(fn ($q) => $q->where('kelas_id', $request->kelas_id)->where('tahun_ajaran_id', $request->tahun_ajaran_id)->where('status_aktif', true))],
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'nilai' => 'required|array|min:1',
            'nilai.*' => 'required|array',
            'nilai.*.tugas' => 'nullable|numeric|between:0,100',
            'nilai.*.uts' => 'nullable|numeric|between:0,100',
            'nilai.*.uas' => 'nullable|numeric|between:0,100',
            'nilai.*.capaian_kompetensi' => 'nullable|string|max:3000',
        ], [
            'nilai.*.tugas.between' => 'Nilai tugas harus antara 0 dan 100.',
            'nilai.*.uts.between' => 'Nilai UTS harus antara 0 dan 100.',
            'nilai.*.uas.between' => 'Nilai UAS harus antara 0 dan 100.',
            'nilai.*.capaian_kompetensi.max' => 'Capaian kompetensi maksimal 3000 karakter.',
        ]);

        $ids = array_keys($validated['nilai']);
        $validIds = Siswa::diRombel($validated['kelas_id'], TahunAjaran::findOrFail($validated['tahun_ajaran_id'])->tahun_ajaran)
            ->whereIn('id', $ids)->pluck('id')->map(fn ($id) => (string) $id)->all();
        if (array_diff(array_map('strval', $ids), $validIds)) {
            throw ValidationException::withMessages(['nilai' => 'Siswa harus terdaftar dalam rombel pada tahun ajaran yang dipilih.']);
        }

        DB::transaction(function () use ($validated) {
            Kelas::whereKey($validated['kelas_id'])->lockForUpdate()->firstOrFail();
            $aturan = PengaturanPredikat::untuk($validated['kelas_id'], $validated['tahun_ajaran_id']);
            foreach ($validated['nilai'] as $siswa_id => $data) {
                $tugas = $data['tugas'] ?? null;
                $uts = $data['uts'] ?? null;
                $uas = $data['uas'] ?? null;
                $hasScores = $tugas !== null || $uts !== null || $uas !== null;
                $key = [
                    'siswa_id' => $siswa_id,
                    'mata_pelajaran_id' => $validated['mata_pelajaran_id'],
                    'tahun_ajaran_id' => $validated['tahun_ajaran_id'],
                ];
                if (! $hasScores && empty($data['capaian_kompetensi']) && ! Nilai::where($key)->exists()) {
                    continue;
                }
                $akhir = $hasScores ? Nilai::hitungNilaiAkhir($tugas ?? 0, $uts ?? 0, $uas ?? 0) : null;
                Nilai::updateOrCreate($key, [
                    'kelas_id' => $validated['kelas_id'],
                    'nilai_tugas' => $tugas,
                    'nilai_uts' => $uts,
                    'nilai_uas' => $uas,
                    'nilai_akhir' => $akhir,
                    'predikat' => $akhir !== null ? Nilai::getPredikat($akhir, $aturan) : null,
                    'capaian_kompetensi' => $data['capaian_kompetensi'] ?? null,
                ]);
            }
        });

        return redirect()->route('nilai.index', [
            'kelas_id' => $validated['kelas_id'],
            'mapel_id' => $validated['mata_pelajaran_id'],
            'tahun_ajaran_id' => $validated['tahun_ajaran_id'],
        ])->with('success', 'Nilai dan capaian kompetensi berhasil disimpan.');
    }

    public function editPredikat(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'required|integer|exists:kelas,id',
            'tahun_ajaran_id' => 'nullable|integer|exists:tahun_ajaran,id',
            'mapel_id' => 'nullable|integer|exists:mata_pelajaran,id',
        ]);
        $kelas = Kelas::findOrFail($validated['kelas_id']);
        $tahunAjaran = isset($validated['tahun_ajaran_id']) ? TahunAjaran::findOrFail($validated['tahun_ajaran_id']) : TahunAjaran::getActive();
        if (! $tahunAjaran) {
            throw ValidationException::withMessages(['tahun_ajaran_id' => 'Pilih tahun ajaran terlebih dahulu.']);
        }
        $aturanPredikat = PengaturanPredikat::untuk($kelas->id, $tahunAjaran->id);
        $defaultPredikat = PengaturanPredikat::DEFAULTS;
        $mapel_id = $validated['mapel_id'] ?? null;

        return Inertia::render('Nilai/Predikat', compact('kelas', 'tahunAjaran', 'aturanPredikat', 'defaultPredikat', 'mapel_id'));
    }

    public function updatePredikat(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'required|integer|exists:kelas,id',
            'tahun_ajaran_id' => 'required|integer|exists:tahun_ajaran,id',
            'mapel_id' => 'nullable|integer|exists:mata_pelajaran,id',
            'min_a' => 'required|numeric|decimal:0,2|between:0.01,100|gt:min_b',
            'min_b' => 'required|numeric|decimal:0,2|between:0.01,100|gt:min_c',
            'min_c' => 'required|numeric|decimal:0,2|between:0.01,100|gt:min_d',
            'min_d' => 'required|numeric|decimal:0,2|between:0.01,100',
        ], [
            'min_a.gt' => 'Batas A harus lebih besar daripada batas B.',
            'min_b.gt' => 'Batas B harus lebih besar daripada batas C.',
            'min_c.gt' => 'Batas C harus lebih besar daripada batas D.',
            'min_*.between' => 'Batas nilai harus lebih dari 0 dan tidak melebihi 100.',
        ]);
        DB::transaction(function () use ($validated) {
            Kelas::whereKey($validated['kelas_id'])->lockForUpdate()->firstOrFail();
            PengaturanPredikat::updateOrCreate(
                ['kelas_id' => $validated['kelas_id'], 'tahun_ajaran_id' => $validated['tahun_ajaran_id']],
                collect($validated)->only(['min_a', 'min_b', 'min_c', 'min_d'])->all()
            );
            $aturan = PengaturanPredikat::untuk($validated['kelas_id'], $validated['tahun_ajaran_id']);
            Nilai::where('kelas_id', $validated['kelas_id'])->where('tahun_ajaran_id', $validated['tahun_ajaran_id'])
                ->whereNotNull('nilai_akhir')->chunkById(200, function ($rows) use ($aturan) {
                    foreach ($rows as $nilai) {
                        $nilai->update(['predikat' => Nilai::getPredikat($nilai->nilai_akhir, $aturan)]);
                    }
                });
        });

        return redirect()->route('nilai.index', [
            'kelas_id' => $validated['kelas_id'], 'tahun_ajaran_id' => $validated['tahun_ajaran_id'],
            'mapel_id' => $validated['mapel_id'] ?? null,
        ])->with('success', 'Pengaturan predikat disimpan dan predikat nilai kelas ini sudah diperbarui.');
    }

    public function storeEkstrakurikuler(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'siswa_id' => 'required|integer|exists:siswa,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'kegiatan' => 'present|array|max:20',
            'kegiatan.*.kegiatan' => 'required|string|max:255',
            'kegiatan.*.predikat' => ['required', Rule::in(['Sangat Baik', 'Baik', 'Cukup', 'Perlu Bimbingan'])],
            'kegiatan.*.keterangan' => 'nullable|string|max:3000',
        ], [
            'siswa_id.exists' => 'Siswa harus terdaftar aktif di kelas yang dipilih.',
            'kegiatan.*.kegiatan.required' => 'Nama kegiatan ekstrakurikuler wajib diisi.',
            'kegiatan.*.predikat.required' => 'Predikat kegiatan wajib dipilih.',
        ]);

        if (! Siswa::diRombel($validated['kelas_id'], TahunAjaran::findOrFail($validated['tahun_ajaran_id'])->tahun_ajaran)->whereKey($validated['siswa_id'])->exists()) {
            throw ValidationException::withMessages(['siswa_id' => 'Siswa bukan anggota rombel pada tahun ajaran yang dipilih.']);
        }

        DB::transaction(function () use ($validated) {
            NilaiEkstrakurikuler::where('siswa_id', $validated['siswa_id'])
                ->where('tahun_ajaran_id', $validated['tahun_ajaran_id'])->delete();
            foreach ($validated['kegiatan'] as $row) {
                NilaiEkstrakurikuler::create([
                    'siswa_id' => $validated['siswa_id'],
                    'tahun_ajaran_id' => $validated['tahun_ajaran_id'],
                    'kegiatan' => $row['kegiatan'],
                    'predikat' => $row['predikat'],
                    'keterangan' => $row['keterangan'] ?? null,
                ]);
            }
        });

        return back()->with('success', 'Kegiatan ekstrakurikuler berhasil disimpan.');
    }

    public function rapor(Request $request)
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('rombel')->get();
        $tahunAjaranList = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();
        $kelas_id = $request->kelas_id;
        $tahun_ajaran_id = $request->tahun_ajaran_id ?? TahunAjaran::getActive()?->id;
        $siswaList = collect();

        if ($kelas_id) {
            $siswaList = Siswa::diRombel($kelas_id, TahunAjaran::find($tahun_ajaran_id)?->tahun_ajaran)
                ->withAvg(['nilai as rata_rata' => fn ($query) => $query->where('tahun_ajaran_id', $tahun_ajaran_id)], 'nilai_akhir')
                ->orderBy('nama')->get();
        }

        return Inertia::render('Nilai/Rapor', compact('kelasList', 'tahunAjaranList', 'kelas_id', 'tahun_ajaran_id', 'siswaList'));
    }

    public function raporDetail($siswa_id, Request $request)
    {
        $siswa = Siswa::with('kelas.guru')->findOrFail($siswa_id);
        $tahun_ajaran_id = $request->tahun_ajaran_id ?? TahunAjaran::getActive()?->id;

        $nilai = Nilai::where('siswa_id', $siswa_id)
            ->where('tahun_ajaran_id', $tahun_ajaran_id)
            ->with('mataPelajaran')
            ->get();

        $tahunAjaran = TahunAjaran::find($tahun_ajaran_id);
        $siswa->setRelation('kelas', $siswa->kelasPada($tahunAjaran?->tahun_ajaran));
        $siswa->kelas_id = $siswa->kelas?->id;
        $absensi = Absensi::where('siswa_id', $siswa_id)->where('tahun_ajaran_id', $tahun_ajaran_id);
        $rekapAbsensi = [
            'hadir' => (clone $absensi)->where('status', 'Hadir')->count(),
            'sakit' => (clone $absensi)->where('status', 'Sakit')->count(),
            'izin' => (clone $absensi)->where('status', 'Izin')->count(),
            'alpa' => (clone $absensi)->where('status', 'Alpa')->count(),
        ];

        $ekstrakurikuler = NilaiEkstrakurikuler::where('siswa_id', $siswa_id)
            ->where('tahun_ajaran_id', $tahun_ajaran_id)->orderBy('id')->get();

        return Inertia::render('Nilai/RaporDetail', compact('siswa', 'nilai', 'tahunAjaran', 'rekapAbsensi', 'ekstrakurikuler'));
    }

    public function updateKopSurat(Request $request)
    {
        $validated = $request->validate([
            'instansi_induk' => 'nullable|string|max:255',
            'sub_instansi' => 'nullable|string|max:255',
            'nama_sekolah' => 'required|string|max:255',
            'npsn' => 'nullable|string|max:20',
            'nss' => 'nullable|string|max:20',
            'akreditasi' => 'nullable|string|max:10',
            'alamat' => 'nullable|string',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'kepala_sekolah' => 'nullable|string|max:255',
            'nip_kepala_sekolah' => 'nullable|string|max:30',
            'tipe_garis_kop' => 'nullable|string|in:ganda,tunggal',
            'logo' => 'nullable|image|max:2048',
            'logo_kiri' => 'nullable|image|max:2048',
        ]);

        $profil = ProfilSekolah::firstOrCreate(['id' => 1]);

        if ($request->hasFile('logo')) {
            if ($profil->logo) {
                Storage::disk('public')->delete($profil->logo);
            }
            $validated['logo'] = $request->file('logo')->store('sekolah', 'public');
        }

        if ($request->hasFile('logo_kiri')) {
            if ($profil->logo_kiri) {
                Storage::disk('public')->delete($profil->logo_kiri);
            }
            $validated['logo_kiri'] = $request->file('logo_kiri')->store('sekolah', 'public');
        }

        $profil->update($validated);

        return back()->with('success', 'Kop surat buku rapor berhasil diperbarui!');
    }
}
