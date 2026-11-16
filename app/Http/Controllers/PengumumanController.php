<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = Pengumuman::orderBy('created_at', 'desc')->paginate(10);
        return Inertia::render('Pengumuman/Index', compact('pengumuman'));
    }

    public function create()
    {
        return Inertia::render('Pengumuman/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'kategori' => 'nullable|string|max:50',
            'is_published' => 'boolean',
            'tanggal_publikasi' => 'nullable|date',
        ]);
        $validated['is_published'] = $request->has('is_published');
        $validated['user_id'] = auth()->id();
        $validated['tanggal_publikasi'] = $validated['tanggal_publikasi'] ?? now();
        Pengumuman::create($validated);
        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil ditambahkan!');
    }

    public function edit(Pengumuman $pengumuman)
    {
        return Inertia::render('Pengumuman/Edit', compact('pengumuman'));
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'kategori' => 'nullable|string|max:50',
            'is_published' => 'boolean',
            'tanggal_publikasi' => 'nullable|date',
        ]);
        $validated['is_published'] = $request->has('is_published');
        $pengumuman->update($validated);
        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui!');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();
        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil dihapus!');
    }
}
