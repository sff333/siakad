<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Siswa/Index', [
            'siswa' => $this->filteredSiswa($request)->paginate(10)->withQueryString(),
            'kelasList' => Kelas::orderBy('tingkat')->orderBy('rombel')->get(),
            'filters' => $request->only(['search', 'kelas_id', 'status']),
        ]);
    }

    public function rekap(Request $request)
    {
        return view('cetak.rekap-siswa', ['siswa' => $this->filteredSiswa($request)->get()]);
    }

    private function filteredSiswa(Request $request): Builder
    {
        $query = Siswa::with('kelas');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%')
                  ->orWhere('nisn', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $query->orderBy('nama')->orderBy('id');
    }

    public function create()
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('rombel')->get();
        return Inertia::render('Siswa/Create', compact('kelasList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|string|unique:siswa,nis|max:20',
            'nisn' => 'required|string|unique:siswa,nisn|max:20',
            'nik' => 'nullable|string|max:20',
            'nama' => 'required|string|max:255',
            'nama_panggilan' => 'nullable|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string|max:20',
            'kewarganegaraan' => 'nullable|string|max:50',
            'anak_ke' => 'nullable|integer|min:1|max:20',
            'jumlah_saudara' => 'nullable|integer|min:0|max:20',
            'golongan_darah' => 'nullable|string|max:5',
            'tinggi_badan' => 'nullable|integer|min:30|max:250',
            'berat_badan' => 'nullable|integer|min:10|max:200',
            'alamat' => 'nullable|string',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'tinggal_bersama' => 'nullable|string|max:100',
            'telepon_ortu' => 'nullable|string|max:20',
            'nama_ayah' => 'nullable|string|max:255',
            'nik_ayah' => 'nullable|string|max:20',
            'pendidikan_ayah' => 'nullable|string|max:100',
            'pekerjaan_ayah' => 'nullable|string|max:100',
            'penghasilan_ayah' => 'nullable|string|max:100',
            'nama_ibu' => 'nullable|string|max:255',
            'nik_ibu' => 'nullable|string|max:20',
            'pendidikan_ibu' => 'nullable|string|max:100',
            'pekerjaan_ibu' => 'nullable|string|max:100',
            'penghasilan_ibu' => 'nullable|string|max:100',
            'nama_wali' => 'nullable|string|max:255',
            'hubungan_wali' => 'nullable|string|max:100',
            'pekerjaan_wali' => 'nullable|string|max:100',
            'telepon_wali' => 'nullable|string|max:20',
            'foto' => 'nullable|image|max:2048',
            'kelas_id' => 'nullable|exists:kelas,id',
            'status' => 'required|in:aktif,lulus,pindah,keluar',
            'tanggal_masuk' => 'nullable|date',
            'asal_sekolah' => 'nullable|string|max:255',
            'nomor_ijazah_sebelumnya' => 'nullable|string|max:100',
        ], [
            'nis.unique' => 'NIS ganda. NIS ini sudah digunakan oleh siswa lain.',
            'nisn.unique' => 'NISN ganda. NISN ini sudah digunakan oleh siswa lain.',
            'nis.required' => 'NIS wajib diisi.',
            'nisn.required' => 'NISN wajib diisi.',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('siswa', 'public');
        }

        Siswa::create($validated);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan ke Buku Induk!');
    }

    public function show(Siswa $siswa)
    {
        $siswa->load(['kelas', 'nilai.mataPelajaran', 'absensi']);
        return Inertia::render('Siswa/Show', compact('siswa'));
    }

    public function cetak(Siswa $siswa)
    {
        return view('cetak.biodata-siswa', [
            'title' => 'Biodata Siswa',
            'nama' => $siswa->nama,
            'foto' => $siswa->foto,
            'sections' => [
                'Identitas Siswa' => [
                    'NIS' => $siswa->nis,
                    'NISN' => $siswa->nisn,
                    'NIK' => $siswa->nik,
                    'Nama panggilan' => $siswa->nama_panggilan,
                    'Jenis kelamin' => match ($siswa->jenis_kelamin) { 'L' => 'Laki-laki', 'P' => 'Perempuan', default => null },
                    'Tempat lahir' => $siswa->tempat_lahir,
                    'Tanggal lahir' => $siswa->tanggal_lahir?->locale('id')->translatedFormat('j F Y'),
                    'Agama' => $siswa->agama,
                    'Kewarganegaraan' => $siswa->kewarganegaraan,
                ],
                'Data Sekolah' => [
                    'Kelas' => $siswa->kelas?->nama_kelas,
                    'Status' => match ($siswa->status) {
                        'pindah' => 'Mutasi Keluar',
                        'keluar' => 'Keluar / DO',
                        default => ucfirst($siswa->status ?? ''),
                    },
                    'Tanggal masuk' => $siswa->tanggal_masuk?->locale('id')->translatedFormat('j F Y'),
                    'Asal sekolah' => $siswa->asal_sekolah,
                    'Nomor ijazah sebelumnya' => $siswa->nomor_ijazah_sebelumnya,
                ],
                'Alamat dan Tempat Tinggal' => [
                    'Alamat' => $siswa->alamat,
                    'RT' => $siswa->rt,
                    'RW' => $siswa->rw,
                    'Kelurahan / Desa' => $siswa->kelurahan,
                    'Kecamatan' => $siswa->kecamatan,
                    'Kabupaten / Kota' => $siswa->kabupaten,
                    'Provinsi' => $siswa->provinsi,
                    'Kode pos' => $siswa->kode_pos,
                    'Tinggal bersama' => $siswa->tinggal_bersama,
                ],
                'Orang Tua' => [
                    'Nama ayah' => $siswa->nama_ayah,
                    'Nama ibu' => $siswa->nama_ibu,
                    'NIK ayah' => $siswa->nik_ayah,
                    'NIK ibu' => $siswa->nik_ibu,
                    'Pekerjaan ayah' => $siswa->pekerjaan_ayah,
                    'Pekerjaan ibu' => $siswa->pekerjaan_ibu,
                    'Telepon orang tua' => $siswa->telepon_ortu,
                ],
                'Wali' => [
                    'Nama wali' => $siswa->nama_wali,
                    'Hubungan dengan siswa' => $siswa->hubungan_wali,
                    'Telepon wali' => $siswa->telepon_wali,
                ],
            ],
        ]);
    }

    public function edit(Siswa $siswa)
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('rombel')->get();
        return Inertia::render('Siswa/Edit', compact('siswa', 'kelasList'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:20|unique:siswa,nis,' . $siswa->id,
            'nisn' => 'required|string|max:20|unique:siswa,nisn,' . $siswa->id,
            'nik' => 'nullable|string|max:20',
            'nama' => 'required|string|max:255',
            'nama_panggilan' => 'nullable|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string|max:20',
            'kewarganegaraan' => 'nullable|string|max:50',
            'anak_ke' => 'nullable|integer|min:1|max:20',
            'jumlah_saudara' => 'nullable|integer|min:0|max:20',
            'golongan_darah' => 'nullable|string|max:5',
            'tinggi_badan' => 'nullable|integer|min:30|max:250',
            'berat_badan' => 'nullable|integer|min:10|max:200',
            'alamat' => 'nullable|string',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'tinggal_bersama' => 'nullable|string|max:100',
            'telepon_ortu' => 'nullable|string|max:20',
            'nama_ayah' => 'nullable|string|max:255',
            'nik_ayah' => 'nullable|string|max:20',
            'pendidikan_ayah' => 'nullable|string|max:100',
            'pekerjaan_ayah' => 'nullable|string|max:100',
            'penghasilan_ayah' => 'nullable|string|max:100',
            'nama_ibu' => 'nullable|string|max:255',
            'nik_ibu' => 'nullable|string|max:20',
            'pendidikan_ibu' => 'nullable|string|max:100',
            'pekerjaan_ibu' => 'nullable|string|max:100',
            'penghasilan_ibu' => 'nullable|string|max:100',
            'nama_wali' => 'nullable|string|max:255',
            'hubungan_wali' => 'nullable|string|max:100',
            'pekerjaan_wali' => 'nullable|string|max:100',
            'telepon_wali' => 'nullable|string|max:20',
            'foto' => 'nullable|image|max:2048',
            'kelas_id' => 'nullable|exists:kelas,id',
            'status' => 'required|in:aktif,lulus,pindah,keluar',
            'tanggal_masuk' => 'nullable|date',
            'asal_sekolah' => 'nullable|string|max:255',
            'nomor_ijazah_sebelumnya' => 'nullable|string|max:100',
        ], [
            'nis.unique' => 'NIS ganda. NIS ini sudah digunakan oleh siswa lain.',
            'nisn.unique' => 'NISN ganda. NISN ini sudah digunakan oleh siswa lain.',
            'nis.required' => 'NIS wajib diisi.',
            'nisn.required' => 'NISN wajib diisi.',
        ]);

        if ($request->hasFile('foto')) {
            if ($siswa->foto) {
                Storage::disk('public')->delete($siswa->foto);
            }
            $validated['foto'] = $request->file('foto')->store('siswa', 'public');
        }

        $siswa->update($validated);

        return redirect()->route('siswa.show', $siswa->id)->with('success', 'Buku Induk siswa berhasil diperbarui!');
    }

    public function destroy(Siswa $siswa)
    {
        if ($siswa->foto) {
            Storage::disk('public')->delete($siswa->foto);
        }
        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus!');
    }
}
