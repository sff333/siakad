<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->restrictOnDelete();
            $table->foreignId('tahun_ajaran_id')->nullable()->constrained('tahun_ajaran')->restrictOnDelete();
            $table->dropUnique(['kode_mapel']);
            $table->unique(['kelas_id', 'tahun_ajaran_id', 'kode_mapel'], 'mapel_kelas_periode_kode_unique');
        });

        DB::transaction(function () {
            $current = DB::table('tahun_ajaran')->where('is_active', true)->first()
                ?? DB::table('tahun_ajaran')->orderByDesc('tahun_ajaran')->first();
            $classes = DB::table('kelas')->pluck('id');
            $subjects = DB::table('mata_pelajaran')->get();
            foreach ($subjects as $subject) {
                $pairs = collect();
                foreach (['jadwal', 'nilai'] as $table) {
                    foreach (DB::table($table)->where('mata_pelajaran_id', $subject->id)->get(['kelas_id', 'tahun_ajaran_id']) as $record) {
                        $period = $record->tahun_ajaran_id ?? $current?->id;
                        if ($period) {
                            $pairs->put($record->kelas_id.':'.$period, [$record->kelas_id, $period]);
                        }
                    }
                }
                if ($current) {
                    foreach ($classes as $id) {
                        $pairs->put($id.':'.$current->id, [$id, $current->id]);
                    }
                }
                $first = true;
                foreach ($pairs as [$classId, $periodId]) {
                    $attributes = (array) $subject;
                    unset($attributes['id']);
                    $attributes['kelas_id'] = $classId;
                    $attributes['tahun_ajaran_id'] = $periodId;
                    if ($first) {
                        DB::table('mata_pelajaran')->where('id', $subject->id)->update($attributes);
                        $newId = $subject->id;
                        $first = false;
                    } else {
                        $newId = DB::table('mata_pelajaran')->insertGetId($attributes);
                    }
                    foreach (['jadwal', 'nilai'] as $table) {
                        DB::table($table)->where('mata_pelajaran_id', $subject->id)->where('kelas_id', $classId)
                            ->where(function ($q) use ($periodId, $current) {
                                $q->where('tahun_ajaran_id', $periodId);
                                if ($periodId === $current?->id) {
                                    $q->orWhereNull('tahun_ajaran_id');
                                }
                            })->update(['mata_pelajaran_id' => $newId, 'tahun_ajaran_id' => $periodId]);
                    }
                }
            }
        });
    }

    public function down(): void
    {
        // Preserve separate subject rows and their grade references rather than merging historical data.
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            $table->dropUnique('mapel_kelas_periode_kode_unique');
            $table->dropConstrainedForeignId('kelas_id');
            $table->dropConstrainedForeignId('tahun_ajaran_id');
        });
    }
};
