<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('nis')->nullable()->change();
            $table->string('nama')->nullable()->change();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->change();
        });
        Schema::table('guru', function (Blueprint $table) {
            $table->string('nama')->nullable()->change();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('nis')->nullable(false)->change();
            $table->string('nama')->nullable(false)->change();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable(false)->change();
        });
        Schema::table('guru', function (Blueprint $table) {
            $table->string('nama')->nullable(false)->change();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable(false)->change();
        });
    }
};
