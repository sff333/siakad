<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            $table->text('capaian_kompetensi')->nullable();
        });

        Schema::create('nilai_ekstrakurikuler', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->string('kegiatan');
            $table->string('predikat', 30);
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->index(['siswa_id', 'tahun_ajaran_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_ekstrakurikuler');
        Schema::table('nilai', function (Blueprint $table) {
            $table->dropColumn('capaian_kompetensi');
        });
    }
};
