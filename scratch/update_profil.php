<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

App\Models\ProfilSekolah::where('id', 1)->update([
    'instansi_induk' => 'DINAS PENDIDIKAN DAN KEBUDAYAAN',
    'sub_instansi' => 'WILAYAH KECAMATAN MENTENG',
    'tipe_garis_kop' => 'ganda',
]);
echo "Profil sekolah initialized with kop surat fields successfully!\n";
