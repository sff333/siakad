<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanPredikat extends Model
{
    public const DEFAULTS = ['A' => 90, 'B' => 80, 'C' => 70, 'D' => 60, 'E' => 0];

    protected $table = 'pengaturan_predikat';

    protected $fillable = ['kelas_id', 'tahun_ajaran_id', 'min_a', 'min_b', 'min_c', 'min_d'];

    public static function untuk($kelasId, $tahunAjaranId): array
    {
        if (! $kelasId || ! $tahunAjaranId) {
            return self::DEFAULTS;
        }
        $settings = self::where('kelas_id', $kelasId)->where('tahun_ajaran_id', $tahunAjaranId)->first();
        if (! $settings) {
            return self::DEFAULTS;
        }

        return ['A' => (float) $settings->min_a, 'B' => (float) $settings->min_b,
            'C' => (float) $settings->min_c, 'D' => (float) $settings->min_d, 'E' => 0];
    }
}
