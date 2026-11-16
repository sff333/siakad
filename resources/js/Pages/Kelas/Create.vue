<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    guruList: {
        type: Array,
        default: () => [],
    },
    tahunAjaranList: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    nama_kelas: '',
    tingkat: 1,
    rombel: 'A',
    guru_id: '',
    tahun_ajaran_id: props.tahunAjaranList.find(t => t.is_active)?.id || (props.tahunAjaranList[0]?.id || ''),
    kapasitas: 30,
});

const submit = () => {
    form.post('/kelas');
};
</script>

<template>
    <AppLayout title="Tambah Kelas" pageTitle="Tambah Kelas">
        <div class="max-w-2xl mx-auto space-y-4">
            <div class="flex items-center justify-between">
                <Link href="/kelas" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-primary-700 font-medium transition">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Kembali ke Daftar Rombel</span>
                </Link>
            </div>

            <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200 shadow-2xs">
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Rombel / Ruang Kelas <span class="text-rose-600">*</span></label>
                        <input v-model="form.nama_kelas" type="text" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition font-semibold" placeholder="cth. Kelas 1-A" />
                        <p v-if="form.errors.nama_kelas" class="text-xs text-rose-600 mt-1">{{ form.errors.nama_kelas }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tingkat Kelas (1 s/d 6) <span class="text-rose-600">*</span></label>
                            <select v-model="form.tingkat" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition bg-white">
                                <option v-for="t in 6" :key="t" :value="t">Tingkat {{ t }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kode Rombel</label>
                            <input v-model="form.rombel" type="text" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition" placeholder="cth. A, B, atau Reguler" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Wali Kelas</label>
                            <select v-model="form.guru_id" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition bg-white">
                                <option value="">-- Pilih Guru Wali Kelas --</option>
                                <option v-for="g in guruList" :key="g.id" :value="g.id">{{ g.nama }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Daya Tampung / Kapasitas Siswa</label>
                            <input v-model="form.kapasitas" type="number" min="1" max="50" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition font-mono" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tahun Ajaran</label>
                        <select v-model="form.tahun_ajaran_id" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition bg-white">
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            <option v-for="ta in tahunAjaranList" :key="ta.id" :value="ta.id">
                                {{ ta.tahun_ajaran }} - Semester {{ ta.semester }} {{ ta.is_active ? '(Aktif)' : '' }}
                            </option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-5 border-t border-slate-200">
                        <Link href="/kelas" class="px-4 py-2 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold transition">
                            Batal
                        </Link>
                        <button type="submit" :disabled="form.processing" class="px-5 py-2 rounded-lg bg-primary-700 hover:bg-primary-800 text-white text-xs font-semibold shadow-2xs transition cursor-pointer flex items-center gap-1.5 disabled:opacity-60">
                            <i v-if="form.processing" class="fa-solid fa-spinner fa-spin text-xs"></i>
                            <i v-else class="fa-solid fa-floppy-disk text-xs"></i>
                            <span>Simpan Rombel</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
