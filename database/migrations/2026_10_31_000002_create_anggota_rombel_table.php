<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggota_rombel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->string('tahun_ajaran', 20);
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->restrictOnDelete();
            $table->timestamps();
            $table->unique(['siswa_id', 'tahun_ajaran']);
            $table->index(['tahun_ajaran', 'kelas_id']);
        });

        $years = DB::table('tahun_ajaran')->pluck('tahun_ajaran', 'id');
        $now = now();
        // Recover known historical classes from recorded attendance and grades.
        foreach (['absensi', 'nilai'] as $table) {
            DB::table($table)->orderBy('id')->chunkById(500, function ($rows) use ($years, $now) {
                foreach ($rows as $row) {
                    if (! isset($years[$row->tahun_ajaran_id])) {
                        continue;
                    }
                    DB::table('anggota_rombel')->updateOrInsert(
                        ['siswa_id' => $row->siswa_id, 'tahun_ajaran' => $years[$row->tahun_ajaran_id]],
                        ['kelas_id' => $row->kelas_id, 'created_at' => $now, 'updated_at' => $now]
                    );
                }
            });
        }
        // Preserve existing placements in the current academic year during migration.
        $current = DB::table('tahun_ajaran')->where('is_active', true)->first()
            ?? DB::table('tahun_ajaran')->orderByDesc('tahun_ajaran')->first();
        if ($current) {
            DB::table('siswa')->orderBy('id')->chunkById(500, function ($rows) use ($current, $now) {
                foreach ($rows as $row) {
                    DB::table('anggota_rombel')->updateOrInsert(
                        ['siswa_id' => $row->id, 'tahun_ajaran' => $current->tahun_ajaran],
                        ['kelas_id' => $row->kelas_id, 'created_at' => $now, 'updated_at' => $now]
                    );
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota_rombel');
    }
};
