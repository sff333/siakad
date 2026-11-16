<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class AnggotaRombel extends Model
{
    protected $table = 'anggota_rombel';

    protected $fillable = ['siswa_id', 'tahun_ajaran', 'kelas_id'];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public static function syncCurrentClasses(string $year): void
    {
        DB::table('siswa')->update(['kelas_id' => null]);
        self::where('tahun_ajaran', $year)->whereNotNull('kelas_id')->chunkById(500, function ($rows) {
            foreach ($rows as $row) {
                DB::table('siswa')->where('id', $row->siswa_id)->update(['kelas_id' => $row->kelas_id]);
            }
        });
    }
}
