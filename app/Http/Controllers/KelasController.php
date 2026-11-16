<?php

namespace App\Http\Controllers;

use App\Models\AnggotaRombel;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class KelasController extends Controller
{
    public function anggota(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'nullable|integer|exists:kelas,id',
            'tahun_ajaran_id' => 'nullable|integer|exists:tahun_ajaran,id',
        ]);
        $tahunAjaran = isset($validated['tahun_ajaran_id']) ? TahunAjaran::findOrFail($validated['tahun_ajaran_id']) : TahunAjaran::getActive();
        $tahunAjaranList = TahunAjaran::orderByDesc('tahun_ajaran')->orderBy('id')->get()->groupBy('tahun_ajaran')->map(fn ($group) => $group->firstWhere('id', $tahunAjaran?->id) ?? $group->first())->values();
        $kelas = isset($validated['kelas_id']) ? Kelas::with('tahunAjaran')->findOrFail($validated['kelas_id']) : null;
        $kelasList = Kelas::with('tahunAjaran')->orderBy('tingkat')->orderBy('rombel')->get();
        $siswaList = collect();
        if ($kelas && $tahunAjaran) {
            $year = $tahunAjaran->tahun_ajaran;
            $siswaList = Siswa::where(function ($q) use ($kelas, $year) {
                $q->where(function ($q) use ($year) {
                    $q->where('status', 'aktif')->whereDoesntHave('anggotaRombel', fn ($q) => $q->where('tahun_ajaran', $year)->whereNotNull('kelas_id'));
                })->orWhereHas('anggotaRombel', fn ($q) => $q->where('tahun_ajaran', $year)->where('kelas_id', $kelas->id));
            })->with(['anggotaRombel' => fn ($q) => $q->where('tahun_ajaran', '<=', $year)->with('kelas')->orderByDesc('tahun_ajaran')])
                ->orderBy('nama')->get(['id', 'nama', 'nis', 'nisn', 'jenis_kelamin', 'status', 'kelas_id'])->map(function ($siswa) use ($year) {
                    $current = $siswa->anggotaRombel->firstWhere('tahun_ajaran', $year);
                    $previous = $siswa->anggotaRombel->first(fn ($row) => $row->tahun_ajaran < $year && $row->kelas_id !== null);

                    return [
                        ...$siswa->only(['id', 'nama', 'nis', 'nisn', 'jenis_kelamin', 'status']),
                        'kelas_id' => $current?->kelas_id,
                        'kelas_sebelumnya' => $previous?->kelas?->nama_kelas,
                        'tahun_sebelumnya' => $previous?->tahun_ajaran,
                    ];
                });
        }

        return Inertia::render('Kelas/Anggota', compact('kelas', 'kelasList', 'siswaList', 'tahunAjaran', 'tahunAjaranList'));
    }

    public function updateAnggota(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => 'required|integer|exists:tahun_ajaran,id',
            'perubahan' => 'required|array|min:1',
            'perubahan.*.siswa_id' => 'required|integer|distinct|exists:siswa,id',
            'perubahan.*.kelas_asal_id' => 'present|nullable|integer|exists:kelas,id',
            'perubahan.*.kelas_tujuan_id' => ['present', 'nullable', 'integer', Rule::in([$kelas->id])],
        ]);

        $validated['perubahan'] = array_map(fn ($row) => [
            'siswa_id' => (int) $row['siswa_id'],
            'kelas_asal_id' => $row['kelas_asal_id'] === null ? null : (int) $row['kelas_asal_id'],
            'kelas_tujuan_id' => $row['kelas_tujuan_id'] === null ? null : (int) $row['kelas_tujuan_id'],
        ], $validated['perubahan']);

        DB::transaction(function () use ($validated, $kelas) {
            $period = TahunAjaran::whereKey($validated['tahun_ajaran_id'])->lockForUpdate()->firstOrFail();
            $year = $period->tahun_ajaran;
            // Lock classes consistently so simultaneous roster edits cannot exceed capacity.
            $classIds = collect($validated['perubahan'])->pluck('kelas_asal_id')->push($kelas->id)->filter()->unique()->sort()->values();
            $lockedClasses = Kelas::whereIn('id', $classIds)->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $students = Siswa::whereIn('id', array_column($validated['perubahan'], 'siswa_id'))
                ->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $delta = 0;

            foreach ($validated['perubahan'] as $row) {
                $student = $students->get($row['siswa_id']);
                if (! $student || $student->status !== 'aktif') {
                    throw ValidationException::withMessages(['perubahan' => 'Hanya siswa aktif yang dapat dipindahkan.']);
                }
                $membership = AnggotaRombel::where('siswa_id', $student->id)->where('tahun_ajaran', $year)->first();
                if ($membership?->kelas_id !== $row['kelas_asal_id']) {
                    throw ValidationException::withMessages(['perubahan' => 'Keanggotaan siswa sudah berubah. Muat ulang halaman sebelum menyimpan kembali.']);
                }
                $source = $row['kelas_asal_id'];
                $target = $row['kelas_tujuan_id'];
                if ($target === $kelas->id && $source !== null) {
                    throw ValidationException::withMessages(['perubahan' => 'Siswa sudah memiliki rombel. Keluarkan dari rombel asal dan simpan terlebih dahulu.']);
                }
                if ($source === $target || ($target === null && $source !== $kelas->id)) {
                    throw ValidationException::withMessages(['perubahan' => 'Pemindahan harus masuk atau keluar dari kelas yang sedang diatur.']);
                }
                $delta += $target === $kelas->id ? 1 : -1;
            }

            $targetClass = $lockedClasses->get($kelas->id);
            if (! $targetClass || AnggotaRombel::where('tahun_ajaran', $year)->where('kelas_id', $kelas->id)->count() + $delta > $targetClass->kapasitas) {
                throw ValidationException::withMessages(['perubahan' => 'Jumlah siswa melebihi kapasitas kelas. Keluarkan siswa atau ubah kapasitas kelas terlebih dahulu.']);
            }
            foreach ($validated['perubahan'] as $row) {
                AnggotaRombel::updateOrCreate(['siswa_id' => $row['siswa_id'], 'tahun_ajaran' => $year], ['kelas_id' => $row['kelas_tujuan_id']]);
                if (TahunAjaran::getActive()?->tahun_ajaran === $year) {
                    DB::table('siswa')->where('id', $row['siswa_id'])->update(['kelas_id' => $row['kelas_tujuan_id'], 'updated_at' => now()]);
                }
            }
        });

        return redirect()->route('rombel.index', ['kelas_id' => $kelas->id, 'tahun_ajaran_id' => $validated['tahun_ajaran_id']])->with('success', 'Anggota rombel berhasil diperbarui.');
    }

    public function index(Request $request)
    {
        $query = Kelas::with(['guru', 'tahunAjaran'])->withCount('siswa');

        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        $kelas = $query->orderBy('tingkat')->orderBy('rombel')->paginate(10)->withQueryString();

        return Inertia::render('Kelas/Index', [
            'kelas' => $kelas,
            'filters' => $request->only(['tingkat']),
        ]);
    }

    public function create()
    {
        $guruList = Guru::where('status_aktif', true)->orderBy('nama')->get();
        $tahunAjaranList = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();

        return Inertia::render('Kelas/Create', compact('guruList', 'tahunAjaranList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:50',
            'tingkat' => 'required|integer|between:1,6',
            'rombel' => 'nullable|string|max:5',
            'guru_id' => 'nullable|exists:guru,id',
            'tahun_ajaran_id' => 'nullable|exists:tahun_ajaran,id',
            'kapasitas' => 'required|integer|min:1|max:50',
        ]);

        Kelas::create($validated);

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil ditambahkan!');
    }

    public function show(Kelas $kelas, Request $request)
    {
        $request->validate(['tahun_ajaran_id' => 'nullable|integer|exists:tahun_ajaran,id', 'tab' => 'nullable|in:siswa,mapel,jadwal']);
        $tahunAjaran = $request->tahun_ajaran_id ? TahunAjaran::findOrFail($request->tahun_ajaran_id) : (TahunAjaran::getActive() ?? $kelas->tahunAjaran);
        $tahunAjaranList = TahunAjaran::orderByDesc('tahun_ajaran')->orderBy('id')->get();
        $kelas->load(['guru', 'jadwal' => fn ($q) => $q->where('tahun_ajaran_id', $tahunAjaran?->id)->with('mataPelajaran', 'guru')->orderBy('jam_mulai')]);
        $kelas->setRelation('tahunAjaran', $tahunAjaran);
        $kelas->setRelation('siswa', Siswa::diRombel($kelas->id, $tahunAjaran?->tahun_ajaran)->orderBy('nama')->get());

        $mapelList = MataPelajaran::untukKelas($kelas->id, $tahunAjaran?->id)->with('guru')->orderBy('nama_mapel')->get();
        $tab = $request->query('tab', 'siswa');

        return Inertia::render('Kelas/Show', compact('kelas', 'tahunAjaran', 'tahunAjaranList', 'mapelList', 'tab'));
    }

    public function edit(Kelas $kelas)
    {
        $guruList = Guru::where('status_aktif', true)->orderBy('nama')->get();
        $tahunAjaranList = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();

        return Inertia::render('Kelas/Edit', compact('kelas', 'guruList', 'tahunAjaranList'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:50',
            'tingkat' => 'required|integer|between:1,6',
            'rombel' => 'nullable|string|max:5',
            'guru_id' => 'nullable|exists:guru,id',
            'tahun_ajaran_id' => 'nullable|exists:tahun_ajaran,id',
            'kapasitas' => 'required|integer|min:1|max:50',
        ]);

        $kelas->update($validated);

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil diperbarui!');
    }

    public function destroy(Kelas $kelas)
    {
        if (AnggotaRombel::where('kelas_id', $kelas->id)->exists() || MataPelajaran::where('kelas_id', $kelas->id)->exists()) {
            throw ValidationException::withMessages(['kelas' => 'Kelas memiliki riwayat rombel dan tidak dapat dihapus.']);
        }
        $kelas->delete();

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil dihapus!');
    }
}
