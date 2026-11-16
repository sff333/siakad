<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    kelas: { type: Object, required: true },
    tahunAjaran: { type: Object, required: true },
    kelasList: {
        type: Array,
        default: () => [],
    },
    mapelList: {
        type: Array,
        default: () => [],
    },
    guruList: {
        type: Array,
        default: () => [],
    },
    tahunAjaranAktif: {
        type: Object,
        default: () => null,
    },
});

const form = useForm({
    kelas_id: props.kelas.id,
    mata_pelajaran_id: '',
    guru_id: '',
    hari: 'Senin',
    jam_mulai: '07:30',
    jam_selesai: '09:00',
    tahun_ajaran_id: props.tahunAjaran.id,
});

const submit = () => {
    form.post(`/kelas/${props.kelas.id}/jadwal`);
};
</script>

<template>
    <AppLayout title="Tambah Jadwal" pageTitle="Tambah Jadwal">
        <div class="max-w-2xl mx-auto space-y-4">
            <p class="text-sm text-slate-600"><strong>{{ kelas.nama_kelas }}</strong> · {{ tahunAjaran.tahun_ajaran }} ({{ tahunAjaran.semester }})</p>
            <div class="flex items-center justify-between">
                <Link :href="`/kelas/${kelas.id}?tahun_ajaran_id=${tahunAjaran.id}&tab=jadwal`" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-primary-700 font-medium transition">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Kembali ke Jadwal</span>
                </Link>
            </div>

            <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200 shadow-2xs">
                <form @submit.prevent="submit" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Rombel / Kelas <span class="text-rose-600">*</span></label>
                            <div class="w-full px-3.5 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm">{{ kelas.nama_kelas }}</div>
                            <p v-if="form.errors.kelas_id" class="text-xs text-rose-600 mt-1">{{ form.errors.kelas_id }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Hari Pelajaran <span class="text-rose-600">*</span></label>
                            <select v-model="form.hari" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition bg-white">
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Jam Mulai <span class="text-rose-600">*</span></label>
                            <input v-model="form.jam_mulai" type="time" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition font-mono" />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Jam Selesai <span class="text-rose-600">*</span></label>
                            <input v-model="form.jam_selesai" type="time" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition font-mono" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Mata Pelajaran <span class="text-rose-600">*</span></label>
                        <select v-model="form.mata_pelajaran_id" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition bg-white font-semibold">
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            <option v-for="m in mapelList" :key="m.id" :value="m.id">
                                {{ m.nama_mapel }} ({{ m.kode_mapel }})
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Guru Pengampu</label>
                        <select v-model="form.guru_id" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition bg-white">
                            <option value="">-- Pilih Guru Pengampu --</option>
                            <option v-for="g in guruList" :key="g.id" :value="g.id">{{ g.nama }}</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-5 border-t border-slate-200">
                        <Link :href="`/kelas/${kelas.id}?tahun_ajaran_id=${tahunAjaran.id}&tab=jadwal`" class="px-4 py-2 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold transition">
                            Batal
                        </Link>
                        <button type="submit" :disabled="form.processing" class="px-5 py-2 rounded-lg bg-primary-700 hover:bg-primary-800 text-white text-xs font-semibold shadow-2xs transition cursor-pointer flex items-center gap-1.5 disabled:opacity-60">
                            <i v-if="form.processing" class="fa-solid fa-spinner fa-spin text-xs"></i>
                            <i v-else class="fa-solid fa-floppy-disk text-xs"></i>
                            <span>Simpan Jadwal</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
