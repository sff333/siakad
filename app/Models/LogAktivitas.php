<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';

    protected $fillable = ['user_id', 'nama_pengguna', 'peran', 'aktivitas', 'halaman'];

    public static function catat(User $user, string $aktivitas, ?string $halaman = null): void
    {
        rescue(fn () => self::create([
            'user_id' => $user->getKey(),
            'nama_pengguna' => $user->name,
            'peran' => User::ROLES[$user->role] ?? $user->role,
            'aktivitas' => $aktivitas,
            'halaman' => $halaman,
        ]));
    }
}
