<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Inertia\Inertia;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Guru/Index', [
            'guru' => $this->filteredGuru($request)->paginate(10)->withQueryString(),
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function rekap(Request $request)
    {
        return view('cetak.rekap-guru', ['guru' => $this->filteredGuru($request)->get()]);
    }

    public function cetak(Guru $guru)
    {
        return view('cetak.biodata', [
            'title' => 'Biodata Guru',
            'subtitle' => 'Identitas dan data tenaga pendidik',
            'caption' => $guru->jabatan ?: 'Jabatan belum diisi',
            'nama' => $guru->nama,
            'foto' => $guru->foto,
            'sections' => [
                'Identitas Guru' => [
                    'NIP' => $guru->nip,
                    'Jenis kelamin' => match ($guru->jenis_kelamin) { 'L' => 'Laki-laki', 'P' => 'Perempuan', default => null },
                    'Tempat lahir' => $guru->tempat_lahir,
                    'Tanggal lahir' => $guru->tanggal_lahir?->locale('id')->translatedFormat('j F Y'),
                ],
                'Kepegawaian' => [
                    'Jabatan' => $guru->jabatan,
                    'Pendidikan terakhir' => $guru->pendidikan_terakhir,
                    'Status' => $guru->status_aktif ? 'Aktif' : 'Nonaktif',
                ],
                'Kontak dan Alamat' => [
                    'Telepon' => $guru->telepon,
                    'Email' => $guru->email,
                    'Alamat' => $guru->alamat,
                ],
            ],
        ]);
    }

    private function filteredGuru(Request $request): Builder
    {
        $query = Guru::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nip', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status_aktif', $request->status === 'aktif');
        }

        return $query->orderBy('nama')->orderBy('id');
    }

    public function create()
    {
        return Inertia::render('Guru/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'nullable|string|max:30',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'jabatan' => 'nullable|string|max:100',
            'pendidikan_terakhir' => 'nullable|string|max:50',
            'foto' => 'nullable|image|max:2048',
            'status_aktif' => 'boolean',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('guru', 'public');
        }

        $validated['status_aktif'] = $request->has('status_aktif');

        Guru::create($validated);

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil ditambahkan!');
    }

    public function show(Guru $guru)
    {
        $guru->load(['kelas', 'mataPelajaran']);
        return Inertia::render('Guru/Show', compact('guru'));
    }

    public function edit(Guru $guru)
    {
        return Inertia::render('Guru/Edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $validated = $request->validate([
            'nip' => 'nullable|string|max:30',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'jabatan' => 'nullable|string|max:100',
            'pendidikan_terakhir' => 'nullable|string|max:50',
            'foto' => 'nullable|image|max:2048',
            'status_aktif' => 'boolean',
        ]);

        if ($request->hasFile('foto')) {
            if ($guru->foto) {
                Storage::disk('public')->delete($guru->foto);
            }
            $validated['foto'] = $request->file('foto')->store('guru', 'public');
        }

        $validated['status_aktif'] = $request->has('status_aktif');

        $guru->update($validated);

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil diperbarui!');
    }

    public function destroy(Guru $guru)
    {
        if ($guru->foto) {
            Storage::disk('public')->delete($guru->foto);
        }
        $guru->delete();

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil dihapus!');
    }
}
