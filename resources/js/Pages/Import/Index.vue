<script setup>
import { computed, ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    jenis: { type: String, required: true },
    columns: { type: Array, default: () => [] },
    columnLabels: { type: Object, default: () => ({}) },
    kelasList: { type: Array, default: () => [] },
    importErrors: { type: Array, default: () => [] },
});
const label = computed(() => props.jenis === 'siswa' ? 'Siswa' : 'Guru');
const form = useForm({ file: null });
const selectedNewFile = ref(false);
const errors = computed(() => selectedNewFile.value ? [] : props.importErrors);
const selectFile = (event) => {
    form.file = event.target.files[0] || null;
    form.clearErrors();
    selectedNewFile.value = true;
};
const submit = () => {
    selectedNewFile.value = false;
    form.post(`/${props.jenis}/import`, { forceFormData: true, preserveScroll: true });
};
</script>

<template>
    <AppLayout :title="`Import ${label}`" :pageTitle="`Import Data ${label}`">
        <div class="max-w-4xl mx-auto space-y-5">
            <Link :href="`/${jenis}`" class="inline-flex items-center gap-2 text-xs font-medium text-slate-600 hover:text-primary-700">
                <i class="fa-solid fa-arrow-left"></i>Kembali ke Data {{ label }}
            </Link>
            <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
                <div class="p-5 border-b border-slate-200">
                    <h1 class="text-lg font-semibold text-slate-900">Import Data {{ label }}</h1>
                    <p class="mt-1 text-sm text-slate-500">Tambahkan banyak data sekaligus menggunakan file CSV.</p>
                </div>
                <div class="p-5 space-y-5">
                    <div class="p-4 bg-primary-50 border border-primary-200 rounded-lg space-y-3">
                        <h2 class="text-sm font-semibold text-primary-900">1. Siapkan data sesuai template</h2>
                        <p class="text-xs text-slate-600 leading-relaxed">Unduh template lalu buka di Excel. Setiap isian memiliki kolom terpisah dengan judul, seperti NIS, Nama, dan Jenis Kelamin. Isi data tepat di bawah baris judul tanpa mengubah judulnya, lalu simpan sebagai CSV UTF-8. Maksimal 2 MB dan 2.000 baris data.</p>
                        <a :href="`/${jenis}/import/template`" class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-primary-200 rounded-lg text-xs font-semibold text-primary-700 hover:bg-primary-100">
                            <i class="fa-solid fa-download"></i>Unduh Template {{ label }} (.csv)
                        </a>
                    </div>
                    <form @submit.prevent="submit" class="space-y-4">
                        <h2 class="text-sm font-semibold text-slate-900">2. Unggah file dan mulai impor</h2>
                        <div class="border border-dashed border-slate-300 bg-slate-50 rounded-lg p-5 space-y-3">
                            <label for="import-file" class="block text-xs font-semibold text-slate-700">File CSV {{ label }} <span class="text-rose-600">*</span></label>
                            <input id="import-file" type="file" accept=".csv,text/csv" required :disabled="form.processing" @change="selectFile" class="block w-full text-xs text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-primary-100 file:px-3 file:py-2 file:text-primary-800 file:font-semibold cursor-pointer" />
                            <p v-if="form.file" class="text-xs text-slate-600">{{ form.file.name }} · {{ (form.file.size / 1024).toFixed(1) }} KB</p>
                            <p class="text-xs text-slate-500">Impor menambahkan data baru. NIS siswa dan NIP guru yang sudah terdaftar akan ditolak.</p>
                            <p class="text-xs text-primary-700">Semua kolom bersifat opsional, termasuk NIS, nama, dan jenis kelamin. Isi data yang tersedia; baris yang seluruhnya kosong akan dilewati.</p>
                        </div>
                        <div v-if="errors.length || form.errors.file" role="alert" class="p-4 border border-rose-200 bg-rose-50 rounded-lg text-xs text-rose-800 space-y-2">
                            <p class="font-semibold">Impor dibatalkan. Perbaiki file lalu unggah kembali.</p>
                            <ul class="list-disc pl-4 space-y-1">
                                <li v-for="(error, index) in errors.length ? errors : [form.errors.file]" :key="index">{{ error }}</li>
                            </ul>
                            <p>Maksimal 20 kesalahan ditampilkan. Data hanya disimpan setelah seluruh baris lolos pemeriksaan.</p>
                        </div>
                        <div v-if="form.progress" role="status" class="text-xs text-primary-700">Mengunggah file: {{ form.progress.percentage }}%</div>
                        <div class="flex justify-end gap-2 border-t border-slate-200 pt-4">
                            <Link :href="`/${jenis}`" class="px-4 py-2 border border-slate-300 rounded-lg text-xs font-semibold text-slate-700">Kembali</Link>
                            <button type="submit" :disabled="form.processing || !form.file" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-700 hover:bg-primary-800 text-white text-xs font-semibold disabled:opacity-50 cursor-pointer">
                                <i :class="form.processing ? 'fa-solid fa-spinner fa-spin' : 'fa-solid fa-file-import'"></i>
                                {{ form.processing ? 'Memproses...' : `Import Data ${label}` }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4">
                <h2 class="text-sm font-semibold text-slate-900">Petunjuk pengisian kolom</h2>
                <ul class="list-disc pl-4 text-xs text-slate-600 space-y-2 leading-relaxed">
                    <li><strong>Tidak ada kolom data yang wajib diisi saat impor.</strong> Kolom yang belum tersedia boleh dikosongkan atau dihapus dari file. Tetap sertakan minimal satu judul kolom dan satu baris berisi data.</li>
                    <li>NIS yang kosong disimpan tanpa nomor dan dapat dilengkapi kemudian. Jika suatu kolom diisi, format nilainya tetap diperiksa.</li>
                    <li><strong>Jenis Kelamin</strong>: L untuk laki-laki atau P untuk perempuan.</li>
                    <li>Semua tanggal menggunakan format <strong>YYYY-MM-DD</strong>, contoh 2015-08-17.</li>
                    <li>Atur kolom nomor identitas dan telepon sebagai teks agar angka nol di depan tetap tersimpan.</li>
                    <template v-if="jenis === 'siswa'">
                        <li><strong>Status</strong>: aktif, lulus, pindah, atau keluar. Jika kosong, diisi aktif.</li>
                        <li><strong>Kewarganegaraan</strong>: jika kosong, diisi WNI.</li>
                        <li><strong>Kelas ID</strong>: isi ID kelas dari daftar di bawah atau kosongkan jika belum memiliki kelas. Penempatan mengikuti tahun ajaran aktif.</li>
                    </template>
                    <li v-else><strong>Status Aktif</strong>: 1 untuk aktif, 0 untuk nonaktif. Jika kosong, diisi 1. NIP boleh kosong.</li>
                </ul>
                <details class="text-xs">
                    <summary class="cursor-pointer font-semibold text-primary-700">Lihat semua kolom yang didukung ({{ columns.length }})</summary>
                    <div class="flex flex-wrap gap-1.5 mt-3"><span v-for="column in columns" :key="column" class="px-2 py-1 bg-slate-100 rounded text-slate-600">{{ columnLabels[column] || column }}</span></div>
                </details>
                <div v-if="jenis === 'siswa'" class="border-t border-slate-100 pt-4">
                    <h3 class="text-xs font-semibold text-slate-800 mb-2">Referensi ID Kelas</h3>
                    <div v-if="kelasList.length" class="overflow-x-auto max-h-64 overflow-y-auto">
                        <table class="w-full text-xs text-left">
                            <thead class="bg-slate-50"><tr><th class="p-2">kelas_id</th><th class="p-2">Nama Kelas</th></tr></thead>
                            <tbody><tr v-for="kelas in kelasList" :key="kelas.id" class="border-t border-slate-100"><td class="p-2 font-mono">{{ kelas.id }}</td><td class="p-2">{{ kelas.nama_kelas }}</td></tr></tbody>
                        </table>
                    </div>
                    <p v-else class="text-xs text-slate-500">Belum ada kelas. Kosongkan kolom kelas_id atau tambahkan kelas terlebih dahulu.</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
