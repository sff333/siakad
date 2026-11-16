<?php

namespace App\Http\Controllers;

use App\Models\AnggotaRombel;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjaran = TahunAjaran::orderBy('tahun_ajaran', 'desc')->orderBy('semester')->paginate(10);

        return Inertia::render('TahunAjaran/Index', compact('tahunAjaran'));
    }

    public function create()
    {
        return Inertia::render('TahunAjaran/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_ajaran' => 'required|string|max:20',
            'semester' => 'required|in:Ganjil,Genap',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);
        $validated['is_active'] = false;
        TahunAjaran::create($validated);

        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil ditambahkan!');
    }

    public function edit(TahunAjaran $tahunAjaran)
    {
        return Inertia::render('TahunAjaran/Edit', compact('tahunAjaran'));
    }

    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $validated = $request->validate([
            'tahun_ajaran' => 'required|string|max:20',
            'semester' => 'required|in:Ganjil,Genap',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);
        if ($validated['tahun_ajaran'] !== $tahunAjaran->tahun_ajaran && AnggotaRombel::where('tahun_ajaran', $tahunAjaran->tahun_ajaran)->exists()) {
            throw ValidationException::withMessages(['tahun_ajaran' => 'Tahun ajaran ini sudah memiliki riwayat rombel. Buat tahun ajaran baru untuk periode berikutnya.']);
        }
        $tahunAjaran->update($validated);

        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil diperbarui!');
    }

    public function destroy(TahunAjaran $tahunAjaran)
    {
        if ($tahunAjaran->is_active || $tahunAjaran->nilai()->exists()
            || DB::table('mata_pelajaran')->where('tahun_ajaran_id', $tahunAjaran->id)->exists()
            || DB::table('absensi')->where('tahun_ajaran_id', $tahunAjaran->id)->exists()
            || DB::table('nilai_ekstrakurikuler')->where('tahun_ajaran_id', $tahunAjaran->id)->exists()
            || (TahunAjaran::where('tahun_ajaran', $tahunAjaran->tahun_ajaran)->count() === 1 && AnggotaRombel::where('tahun_ajaran', $tahunAjaran->tahun_ajaran)->exists())) {
            throw ValidationException::withMessages(['tahun_ajaran' => 'Tahun ajaran aktif atau yang memiliki riwayat akademik tidak dapat dihapus.']);
        }
        $tahunAjaran->delete();

        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil dihapus!');
    }

    public function activate(TahunAjaran $tahunAjaran)
    {
        DB::transaction(function () use ($tahunAjaran) {
            TahunAjaran::orderBy('id')->lockForUpdate()->get();
            TahunAjaran::where('is_active', true)->update(['is_active' => false]);
            $tahunAjaran->refresh();
            $tahunAjaran->update(['is_active' => true]);
        });

        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil diaktifkan!');
    }
}
