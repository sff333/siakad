<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class JadwalController extends Controller
{
    private function period(Request $request): TahunAjaran
    {
        $request->validate(['tahun_ajaran_id' => 'nullable|integer|exists:tahun_ajaran,id']);
        $period = $request->tahun_ajaran_id ? TahunAjaran::findOrFail($request->tahun_ajaran_id) : TahunAjaran::getActive();
        if (! $period) {
            throw ValidationException::withMessages(['tahun_ajaran_id' => 'Pilih tahun ajaran terlebih dahulu.']);
        }

        return $period;
    }

    public function index(Kelas $kelas, Request $request)
    {
        return redirect()->route('kelas.show', ['kelas' => $kelas->id, 'tahun_ajaran_id' => $this->period($request)->id, 'tab' => 'jadwal']);
    }

    public function legacy(Request $request)
    {
        if ($request->filled('kelas_id')) {
            return $this->index(Kelas::findOrFail($request->kelas_id), $request);
        }

        return redirect()->route('kelas.index');
    }

    public function create(Kelas $kelas, Request $request)
    {
        $tahunAjaran = $this->period($request);
        $mapelList = MataPelajaran::untukKelas($kelas->id, $tahunAjaran->id)->where('status_aktif', true)->orderBy('nama_mapel')->get();
        $guruList = Guru::where('status_aktif', true)->orderBy('nama')->get();

        return Inertia::render('Jadwal/Create', compact('kelas', 'mapelList', 'guruList', 'tahunAjaran'));
    }

    public function store(Kelas $kelas, Request $request)
    {
        $validated = $this->validated($request, $kelas);
        Jadwal::create([...$validated, 'kelas_id' => $kelas->id]);

        return redirect()->route('kelas.show', ['kelas' => $kelas->id, 'tahun_ajaran_id' => $validated['tahun_ajaran_id'], 'tab' => 'jadwal'])->with('success', 'Jadwal kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas, Jadwal $jadwal)
    {
        abort_unless($jadwal->kelas_id === $kelas->id, 404);
        $tahunAjaran = TahunAjaran::findOrFail($jadwal->tahun_ajaran_id);
        $mapelList = MataPelajaran::untukKelas($kelas->id, $tahunAjaran->id)->where(fn ($q) => $q->where('status_aktif', true)->orWhere('id', $jadwal->mata_pelajaran_id))->orderBy('nama_mapel')->get();
        $guruList = Guru::where('status_aktif', true)->orderBy('nama')->get();

        return Inertia::render('Jadwal/Edit', compact('jadwal', 'kelas', 'mapelList', 'guruList', 'tahunAjaran'));
    }

    public function update(Kelas $kelas, Jadwal $jadwal, Request $request)
    {
        abort_unless($jadwal->kelas_id === $kelas->id, 404);
        $jadwal->update($this->validated($request, $kelas, $jadwal));

        return redirect()->route('kelas.show', ['kelas' => $kelas->id, 'tahun_ajaran_id' => $jadwal->tahun_ajaran_id, 'tab' => 'jadwal'])->with('success', 'Jadwal kelas berhasil diperbarui.');
    }

    private function validated(Request $request, Kelas $kelas, ?Jadwal $jadwal = null): array
    {
        return $request->validate([
            'tahun_ajaran_id' => ['required', 'integer', 'exists:tahun_ajaran,id', ...($jadwal ? [Rule::in([$jadwal->tahun_ajaran_id])] : [])],
            'mata_pelajaran_id' => ['required', Rule::exists('mata_pelajaran', 'id')->where(fn ($q) => $q
                ->where('kelas_id', $kelas->id)->where('tahun_ajaran_id', $request->tahun_ajaran_id)
                ->where(fn ($q) => $q->where('status_aktif', true)->orWhere('id', $jadwal?->mata_pelajaran_id)))],
            'guru_id' => 'nullable|exists:guru,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ], ['mata_pelajaran_id.exists' => 'Pilih mata pelajaran dari kelas dan periode ini.']);
    }

    public function destroy(Kelas $kelas, Jadwal $jadwal)
    {
        abort_unless($jadwal->kelas_id === $kelas->id, 404);
        $period = $jadwal->tahun_ajaran_id;
        $jadwal->delete();

        return redirect()->route('kelas.show', ['kelas' => $kelas->id, 'tahun_ajaran_id' => $period, 'tab' => 'jadwal'])->with('success', 'Jadwal kelas dihapus.');
    }
}
