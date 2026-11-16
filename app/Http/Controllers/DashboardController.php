<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user->canAccessPage('dashboard')) {
            return Inertia::render('Dashboard', ['restricted' => true]);
        }

        $userCounts = null;
        if ($user->can('manage-users')) {
            $counts = User::query()->selectRaw('role, COUNT(*) as total')->groupBy('role')->pluck('total', 'role');
            $userCounts = [
                'admin' => (int) ($counts['admin'] ?? 0),
                'operator' => (int) ($counts['operator'] ?? 0),
            ];
        }

        $totalSiswa = Siswa::where('status', 'aktif')->count();
        $totalGuru = Guru::where('status_aktif', true)->count();
        $totalKelas = Kelas::count();
        $totalMapel = MataPelajaran::where('status_aktif', true)->where('tahun_ajaran_id', TahunAjaran::getActive()?->id)->count();
        $tahunAjaranAktif = TahunAjaran::getActive();

        // Siswa per kelas chart data
        $siswaPerKelas = Kelas::with('guru')->withCount(['siswa' => function ($q) {
            $q->where('status', 'aktif');
        }])->orderBy('tingkat')->orderBy('rombel')->get();

        // Gender distribution
        $siswaLaki = Siswa::where('status', 'aktif')->where('jenis_kelamin', 'L')->count();
        $siswaPerempuan = Siswa::where('status', 'aktif')->where('jenis_kelamin', 'P')->count();

        // Absensi hari ini
        $today = today('Asia/Jakarta');
        $absensiQuery = Absensi::query()->when($tahunAjaranAktif, fn ($q) => $q->where('tahun_ajaran_id', $tahunAjaranAktif->id));
        $absensiHariIni = (clone $absensiQuery)->whereDate('tanggal', $today)->count();
        $hadirHariIni = (clone $absensiQuery)->whereDate('tanggal', $today)->where('status', 'Hadir')->count();
        $counts = (clone $absensiQuery)->whereDate('tanggal', '>=', $today->copy()->subDays(6)->toDateString())
            ->whereDate('tanggal', '<=', $today->toDateString())
            ->selectRaw('tanggal, status, COUNT(*) as total')->groupBy('tanggal', 'status')->get()
            ->groupBy(fn ($row) => $row->tanggal->toDateString());
        $statistikAbsensi = collect(range(6, 0))->map(function ($offset) use ($today, $counts) {
            $date = $today->copy()->subDays($offset)->toDateString();
            $statuses = $counts->get($date, collect())->pluck('total', 'status');

            return [
                'tanggal' => $date,
                'hadir' => (int) ($statuses['Hadir'] ?? 0),
                'sakit' => (int) ($statuses['Sakit'] ?? 0),
                'izin' => (int) ($statuses['Izin'] ?? 0),
                'alpa' => (int) ($statuses['Alpa'] ?? 0),
            ];
        });

        return Inertia::render('Dashboard', compact(
            'totalSiswa', 'totalGuru', 'totalKelas', 'totalMapel',
            'tahunAjaranAktif', 'siswaPerKelas', 'siswaLaki', 'siswaPerempuan',
            'absensiHariIni', 'hadirHariIni', 'statistikAbsensi', 'userCounts'
        ));
    }
}
