<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'page_permissions'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    public const PAGES = [
        'dashboard' => 'Ringkasan Dashboard',
        'siswa' => 'Data Siswa dan Import',
        'guru' => 'Data Guru dan Import',
        'kelas' => 'Kelas, Rombel, Mata Pelajaran dan Jadwal',
        'absensi' => 'Presensi',
        'rekap-presensi' => 'Rekap Presensi',
        'nilai' => 'Nilai, Predikat dan Ekstrakurikuler',
        'rapor' => 'Rapor dan Kop Surat',
        'pengumuman' => 'Pengumuman',
        'tahun-ajaran' => 'Tahun Ajaran',
        'profil-sekolah' => 'Profil Sekolah',
    ];

    public function allowedPages(): array
    {
        if (!$this->is_active) {
            return [];
        }

        return $this->role === 'admin' || $this->page_permissions === null
            ? array_keys(self::PAGES)
            : array_values(array_intersect(array_keys(self::PAGES), $this->page_permissions));
    }

    public function canAccessPage(string $page): bool
    {
        return in_array($page, $this->allowedPages(), true);
    }
    public const ROLES = [
        'admin' => 'Administrator',
        'operator' => 'Operator Sekolah',
    ];

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $attributes = ['is_active' => true];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'page_permissions' => 'array',
        ];
    }
}
