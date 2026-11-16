<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('role')->default('operator')->change();
        });

        DB::table('users')->whereIn('role', ['guru', 'wali_kelas'])->update(['role' => 'operator']);
    }

    public function down(): void
    {
        DB::table('users')->where('role', 'operator')->update(['role' => 'guru']);

        Schema::table('users', function (Blueprint $table): void {
            $table->enum('role', ['admin', 'guru', 'wali_kelas'])->default('admin')->change();
        });
    }
};
