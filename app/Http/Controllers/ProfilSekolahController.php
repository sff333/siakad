<?php

namespace App\Http\Controllers;

use App\Models\ProfilSekolah;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProfilSekolahController extends Controller
{
    public function index()
    {
        $profil = ProfilSekolah::first();
        return Inertia::render('ProfilSekolah/Index', compact('profil'));
    }

    public function update(Request $request)
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
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'kepala_sekolah' => 'nullable|string|max:255',
            'nip_kepala_sekolah' => 'nullable|string|max:30',
            'logo' => 'nullable|image|max:2048',
            'logo_kiri' => 'nullable|image|max:2048',
            'tipe_garis_kop' => 'nullable|string|in:ganda,tunggal',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            $cloudinary = new Cloudinary(config('services.cloudinary.url'));
            $upload = $cloudinary->uploadApi()->upload($request->file('logo')->getRealPath(), [
                'folder' => 'sekolah',
            ]);
            $validated['logo'] = $upload['secure_url'];
        }

        if ($request->hasFile('logo_kiri')) {
            $cloudinary = new Cloudinary(config('services.cloudinary.url'));
            $upload = $cloudinary->uploadApi()->upload($request->file('logo_kiri')->getRealPath(), [
                'folder' => 'sekolah',
            ]);
            $validated['logo_kiri'] = $upload['secure_url'];
        }

        ProfilSekolah::updateOrCreate(['id' => 1], $validated);
        return redirect()->route('profil-sekolah.index')->with('success', 'Profil sekolah berhasil diperbarui!');
    }
}
