<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class MataPelajaranController extends Controller
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
        $period = $this->period($request);

        return redirect()->route('kelas.show', ['kelas' => $kelas->id, 'tahun_ajaran_id' => $period->id, 'tab' => 'mapel']);
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
        $guruList = Guru::where('status_aktif', true)->orderBy('nama')->get();

        return Inertia::render('Mapel/Create', compact('kelas', 'tahunAjaran', 'guruList'));
    }

    public function store(Kelas $kelas, Request $request)
    {
        $validated = $this->validated($request, $kelas);
        MataPelajaran::create([...$validated, 'kelas_id' => $kelas->id]);

        return redirect()->route('kelas.show', ['kelas' => $kelas->id, 'tahun_ajaran_id' => $validated['tahun_ajaran_id'], 'tab' => 'mapel'])
            ->with('success', 'Mata pelajaran kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas, MataPelajaran $mapel)
    {
        abort_unless($mapel->kelas_id === $kelas->id, 404);
        $tahunAjaran = TahunAjaran::findOrFail($mapel->tahun_ajaran_id);
        $guruList = Guru::where('status_aktif', true)->orderBy('nama')->get();

        return Inertia::render('Mapel/Edit', compact('mapel', 'kelas', 'tahunAjaran', 'guruList'));
    }

    public function update(Kelas $kelas, MataPelajaran $mapel, Request $request)
    {
        abort_unless($mapel->kelas_id === $kelas->id, 404);
        $validated = $this->validated($request, $kelas, $mapel);
        $mapel->update($validated);

        return redirect()->route('kelas.show', ['kelas' => $kelas->id, 'tahun_ajaran_id' => $mapel->tahun_ajaran_id, 'tab' => 'mapel'])
            ->with('success', 'Mata pelajaran kelas berhasil diperbarui.');
    }

    private function validated(Request $request, Kelas $kelas, ?MataPelajaran $mapel = null): array
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => ['required', 'integer', 'exists:tahun_ajaran,id', ...($mapel ? [Rule::in([$mapel->tahun_ajaran_id])] : [])],
            'kode_mapel' => ['required', 'string', 'max:10', Rule::unique('mata_pelajaran', 'kode_mapel')
                ->where('kelas_id', $kelas->id)->where('tahun_ajaran_id', $request->tahun_ajaran_id)->ignore($mapel?->id)],
            'nama_mapel' => 'required|string|max:100',
            'kkm' => 'required|integer|between:0,100',
            'guru_id' => 'nullable|exists:guru,id',
            'is_pilar' => 'boolean', 'is_muatan_lokal' => 'boolean', 'status_aktif' => 'boolean',
        ]);

        return [...$validated, 'is_pilar' => $request->boolean('is_pilar'),
            'is_muatan_lokal' => $request->boolean('is_muatan_lokal'), 'status_aktif' => $request->boolean('status_aktif', true)];
    }

    public function destroy(Kelas $kelas, MataPelajaran $mapel)
    {
        abort_unless($mapel->kelas_id === $kelas->id, 404);
        if (DB::table('nilai')->where('mata_pelajaran_id', $mapel->id)->exists() || DB::table('jadwal')->where('mata_pelajaran_id', $mapel->id)->exists()) {
            throw ValidationException::withMessages(['mapel' => 'Mata pelajaran sudah digunakan pada nilai atau jadwal. Nonaktifkan jika tidak digunakan lagi.']);
        }
        $period = $mapel->tahun_ajaran_id;
        $mapel->delete();

        return redirect()->route('kelas.show', ['kelas' => $kelas->id, 'tahun_ajaran_id' => $period, 'tab' => 'mapel'])->with('success', 'Mata pelajaran dihapus.');
    }
}
