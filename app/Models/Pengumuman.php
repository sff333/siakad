<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';

    protected $fillable = [
        'judul', 'isi', 'kategori', 'user_id', 'is_published', 'tanggal_publikasi'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'tanggal_publikasi' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
