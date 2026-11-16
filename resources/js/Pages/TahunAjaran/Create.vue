<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const form = useForm({
    tahun_ajaran: '',
    semester: 'Ganjil',
    tanggal_mulai: '',
    tanggal_selesai: '',
});

const submit = () => {
    form.post('/tahun-ajaran');
};
</script>

<template>
    <AppLayout title="Tambah Tahun Ajaran" pageTitle="Tambah Tahun Ajaran">
        <div class="max-w-xl mx-auto space-y-4">
            <div class="flex items-center justify-between">
                <Link href="/tahun-ajaran" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-primary-700 font-medium transition">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Kembali ke Kalender</span>
                </Link>
            </div>

            <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200 shadow-2xs">
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tahun Ajaran <span class="text-rose-600">*</span></label>
                        <input v-model="form.tahun_ajaran" type="text" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition font-mono" placeholder="cth. 2026/2027" />
                        <p v-if="form.errors.tahun_ajaran" class="text-xs text-rose-600 mt-1">{{ form.errors.tahun_ajaran }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Semester <span class="text-rose-600">*</span></label>
                        <select v-model="form.semester" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition bg-white">
                            <option value="Ganjil">Semester Ganjil (1)</option>
                            <option value="Genap">Semester Genap (2)</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Mulai Semester</label>
                            <input v-model="form.tanggal_mulai" type="date" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition font-mono" />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Berakhir Semester</label>
                            <input v-model="form.tanggal_selesai" type="date" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition font-mono" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-5 border-t border-slate-200">
                        <Link href="/tahun-ajaran" class="px-4 py-2 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold transition">
                            Batal
                        </Link>
                        <button type="submit" :disabled="form.processing" class="px-5 py-2 rounded-lg bg-primary-700 hover:bg-primary-800 text-white text-xs font-semibold shadow-2xs transition cursor-pointer flex items-center gap-1.5 disabled:opacity-60">
                            <i v-if="form.processing" class="fa-solid fa-spinner fa-spin text-xs"></i>
                            <i v-else class="fa-solid fa-floppy-disk text-xs"></i>
                            <span>Simpan Periode</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
