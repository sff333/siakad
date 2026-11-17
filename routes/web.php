<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LogAktivitasController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\ProfilSekolahController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::redirect('/', '/login')->name('home');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth', \App\Http\Middleware\EnsurePageAccess::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('log-aktivitas', [LogAktivitasController::class, 'index'])
        ->middleware('can:manage-users')
        ->name('log-aktivitas.index');

    Route::middleware('can:manage-users')->group(function () {
        Route::get('siswa/rekap', [SiswaController::class, 'rekap'])->name('siswa.rekap');
        Route::get('guru/rekap', [GuruController::class, 'rekap'])->name('guru.rekap');
        Route::get('guru/{guru}/cetak', [GuruController::class, 'cetak'])->name('guru.cetak');
        Route::get('siswa/{siswa}/cetak', [SiswaController::class, 'cetak'])->name('siswa.cetak');
    });

    Route::resource('pengguna', UserController::class)
        ->parameters(['pengguna' => 'user'])
        ->only(['index', 'create', 'store', 'edit', 'update'])
        ->middleware('can:manage-users');
    Route::patch('pengguna/{user}/status', [UserController::class, 'updateStatus'])
        ->middleware('can:manage-users')
        ->name('pengguna.status.update');

    foreach (['siswa', 'guru'] as $jenis) {
        Route::get("{$jenis}/import", [\App\Http\Controllers\ImportDataController::class, 'create'])->defaults('jenis', $jenis)->name("{$jenis}.import");
        Route::get("{$jenis}/import/template", [\App\Http\Controllers\ImportDataController::class, 'template'])->defaults('jenis', $jenis)->name("{$jenis}.import.template");
        Route::post("{$jenis}/import", [\App\Http\Controllers\ImportDataController::class, 'store'])->defaults('jenis', $jenis)->name("{$jenis}.import.store");
    }

    // Guru
    Route::resource('guru', GuruController::class);

    // Siswa
    Route::resource('siswa', SiswaController::class);

    // Kelas
    Route::get('rombel', [KelasController::class, 'anggota'])->name('rombel.index');
    Route::patch('kelas/{kelas}/anggota', [KelasController::class, 'updateAnggota'])->name('kelas.anggota.update');
    Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas']);

    // Mata Pelajaran
    Route::get('mapel', [MataPelajaranController::class, 'legacy']);
    Route::get('mapel/create', [MataPelajaranController::class, 'legacy']);
    Route::resource('kelas.mapel', MataPelajaranController::class)->parameters(['kelas' => 'kelas', 'mapel' => 'mapel'])->except('show');

    // Tahun Ajaran
    Route::resource('tahun-ajaran', TahunAjaranController::class);
    Route::post('tahun-ajaran/{tahunAjaran}/activate', [TahunAjaranController::class, 'activate'])->name('tahun-ajaran.activate');

    // Jadwal
    Route::get('jadwal', [JadwalController::class, 'legacy']);
    Route::get('jadwal/create', [JadwalController::class, 'legacy']);
    Route::resource('kelas.jadwal', JadwalController::class)->parameters(['kelas' => 'kelas', 'jadwal' => 'jadwal'])->except('show');

    // Absensi
    Route::get('absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::get('absensi/rekap', [AbsensiController::class, 'rekap'])->name('absensi.rekap');

    // Nilai
    Route::get('nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::post('nilai', [NilaiController::class, 'store'])->name('nilai.store');
    Route::get('nilai/predikat', [NilaiController::class, 'editPredikat'])->name('nilai.predikat.edit');
    Route::put('nilai/predikat', [NilaiController::class, 'updatePredikat'])->name('nilai.predikat.update');
    Route::post('nilai/ekstrakurikuler', [NilaiController::class, 'storeEkstrakurikuler'])->name('nilai.ekstrakurikuler.store');
    Route::get('rapor', [NilaiController::class, 'rapor'])->name('rapor.index');
    Route::post('rapor/kop-surat', [NilaiController::class, 'updateKopSurat'])->name('rapor.kop-surat');
    Route::get('rapor/{siswa}', [NilaiController::class, 'raporDetail'])->name('rapor.detail');

    // Pengumuman
    Route::resource('pengumuman', PengumumanController::class)->except(['show']);

    // Profil Sekolah
    Route::get('profil-sekolah', [ProfilSekolahController::class, 'index'])->name('profil-sekolah.index');
    Route::put('profil-sekolah', [ProfilSekolahController::class, 'update'])->name('profil-sekolah.update');
});

Route::get('/setup-database-vercel', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', [
            '--seed' => true,
            '--force' => true
        ]);
        return 'Sukses! Database berhasil dibangun ulang dengan struktur AUTO_INCREMENT yang benar.';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});