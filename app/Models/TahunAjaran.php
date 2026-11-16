<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahunAjaran extends Model
{
    protected static function booted(): void
    {
        static::saved(function (TahunAjaran $tahun) {
            if ($tahun->is_active && ($tahun->wasRecentlyCreated || $tahun->wasChanged(['is_active', 'tahun_ajaran']))) {
                AnggotaRombel::syncCurrentClasses($tahun->tahun_ajaran);
            }
        });
    }

    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'tahun_ajaran', 'semester', 'is_active', 'tanggal_mulai', 'tanggal_selesai',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class);
    }

    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }

    public function getFullNameAttribute(): string
    {
        return $this->tahun_ajaran.' - '.$this->semester;
    }
}
