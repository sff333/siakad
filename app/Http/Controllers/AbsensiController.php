<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('rombel')->get();
        $tahunAjaranList = TahunAjaran::orderByDesc('tahun_ajaran')->orderBy('id')->get();
        $tahun_ajaran_id = $request->tahun_ajaran_id ?? TahunAjaran::getActive()?->id;
        $year = TahunAjaran::find($tahun_ajaran_id)?->tahun_ajaran;
        $kelas_id = $request->kelas_id;
        $tanggal = $request->tanggal ?? date('Y-m-d');
        $absensi = collect();
        $siswaList = collect();

        if ($kelas_id) {
            $siswaList = Siswa::diRombel($kelas_id, $year)->orderBy('nama')->get();
            $absensi = Absensi::where('kelas_id', $kelas_id)->where('tahun_ajaran_id', $tahun_ajaran_id)->whereDate('tanggal', $tanggal)->get()->keyBy('siswa_id');
        }

        return Inertia::render('Absensi/Index', compact('kelasList', 'kelas_id', 'tanggal', 'absensi', 'siswaList', 'tahunAjaranList', 'tahun_ajaran_id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'tanggal' => 'required|date',
            'absensi' => 'required|array|min:1',
            'absensi.*' => 'required|in:Hadir,Sakit,Izin,Alpa',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string|max:3000',
        ]);
        $year = TahunAjaran::findOrFail($validated['tahun_ajaran_id'])->tahun_ajaran;
        $ids = array_keys($validated['absensi']);
        $valid = Siswa::diRombel($validated['kelas_id'], $year)->whereIn('id', $ids)->pluck('id')->map(fn ($id) => (string) $id)->all();
        if (array_diff(array_map('strval', $ids), $valid)) {
            throw ValidationException::withMessages(['absensi' => 'Siswa bukan anggota rombel pada tahun ajaran yang dipilih.']);
        }
        // A calendar date belongs to one recorded semester; do not overwrite an archive through another period.
        if (Absensi::whereIn('siswa_id', $ids)->whereDate('tanggal', $validated['tanggal'])
            ->where(fn ($q) => $q->where('tahun_ajaran_id', '!=', $validated['tahun_ajaran_id'])->orWhereNull('tahun_ajaran_id'))->exists()) {
            throw ValidationException::withMessages(['tanggal' => 'Tanggal ini sudah memiliki presensi pada periode lain. Pilih periode yang sesuai.']);
        }
        DB::transaction(function () use ($validated) {
            foreach ($validated['absensi'] as $siswa_id => $status) {
                Absensi::updateOrCreate(['siswa_id' => $siswa_id, 'tanggal' => $validated['tanggal']], [
                    'kelas_id' => $validated['kelas_id'], 'status' => $status,
                    'keterangan' => $validated['keterangan'][$siswa_id] ?? null,
                    'tahun_ajaran_id' => $validated['tahun_ajaran_id'],
                ]);
            }
        });

        return redirect()->route('absensi.index', $request->only('kelas_id', 'tanggal', 'tahun_ajaran_id'))->with('success', 'Absensi berhasil disimpan!');
    }

    public function rekap(Request $request)
    {
        $request->validate([
            'kelas_id' => 'nullable|integer|exists:kelas,id',
            'tahun_ajaran_id' => 'nullable|integer|exists:tahun_ajaran,id',
            'bulan' => 'nullable|integer|between:1,12',
            'tahun' => 'nullable|integer|between:1,9999',
        ]);
        $kelasList = Kelas::orderBy('tingkat')->orderBy('rombel')->get();
        $tahunAjaranList = TahunAjaran::orderByDesc('tahun_ajaran')->orderBy('id')->get();
        $tahun_ajaran_id = $request->tahun_ajaran_id ?? TahunAjaran::getActive()?->id;
        $year = TahunAjaran::find($tahun_ajaran_id)?->tahun_ajaran;
        $kelas_id = $request->kelas_id;
        $bulan = (string) (int) ($request->bulan ?? now('Asia/Jakarta')->month);
        $tahun = (string) (int) ($request->tahun ?? now('Asia/Jakarta')->year);
        $start = CarbonImmutable::create((int) $tahun, (int) $bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $jumlahHari = $start->daysInMonth;
        $rekap = collect();

        if ($kelas_id) {
            $records = Absensi::where('kelas_id', $kelas_id)->where('tahun_ajaran_id', $tahun_ajaran_id)
                ->whereDate('tanggal', '>=', $start->toDateString())
                ->whereDate('tanggal', '<=', $start->endOfMonth()->toDateString())
                ->orderBy('tanggal')->get()->groupBy('siswa_id');
            $siswaList = Siswa::diRombel($kelas_id, $year)->orderBy('nama')->get();
            foreach ($siswaList as $siswa) {
                $daily = $records->get($siswa->id, collect());
                $counts = $daily->countBy('status');
                $rekap->push([
                    'siswa' => $siswa,
                    'hadir' => $counts->get('Hadir', 0),
                    'sakit' => $counts->get('Sakit', 0),
                    'izin' => $counts->get('Izin', 0),
                    'alpa' => $counts->get('Alpa', 0),
                    'total' => $daily->count(),
                    'harian' => (object) $daily->mapWithKeys(fn ($row) => [(int) $row->tanggal->format('d') => $row->status])->all(),
                ]);
            }
        }

        return Inertia::render('Absensi/Rekap', compact('kelasList', 'kelas_id', 'bulan', 'tahun', 'rekap', 'tahunAjaranList', 'tahun_ajaran_id', 'jumlahHari'));
    }
}
