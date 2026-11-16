<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profil_sekolah', function (Blueprint $table) {
            $table->string('instansi_induk')->nullable()->after('id')->default('PEMERINTAH KOTA / DINAS PENDIDIKAN');
            $table->string('sub_instansi')->nullable()->after('instansi_induk');
            $table->string('logo_kiri')->nullable()->after('logo');
            $table->string('tipe_garis_kop')->nullable()->default('ganda')->after('logo_kiri');
        });
    }

    public function down(): void
    {
        Schema::table('profil_sekolah', function (Blueprint $table) {
            $table->dropColumn(['instansi_induk', 'sub_instansi', 'logo_kiri', 'tipe_garis_kop']);
        });
    }
};
