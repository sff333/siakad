<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    kelas: { type: Object, required: true },
    tahunAjaran: { type: Object, required: true },
    guruList: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    tahun_ajaran_id: props.tahunAjaran.id,
    is_pilar: false,
    kode_mapel: '',
    nama_mapel: '',
    kkm: 70,
    guru_id: '',
    is_muatan_lokal: false,
    status_aktif: true,
});

const submit = () => {
    form.post(`/kelas/${props.kelas.id}/mapel`);
};
</script>

<template>
    <AppLayout title="Tambah Mapel" pageTitle="Tambah Mata Pelajaran">
        <div class="max-w-2xl mx-auto space-y-4">
            <p class="text-sm text-slate-600"><strong>{{ kelas.nama_kelas }}</strong> · {{ tahunAjaran.tahun_ajaran }} ({{ tahunAjaran.semester }})</p>
            <div class="flex items-center justify-between">
                <Link :href="`/kelas/${kelas.id}?tahun_ajaran_id=${tahunAjaran.id}&tab=mapel`" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-primary-700 font-medium transition">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Kembali ke Struktur Mapel</span>
                </Link>
            </div>

            <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200 shadow-2xs">
                <form @submit.prevent="submit" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kode Mata Pelajaran <span class="text-rose-600">*</span></label>
                            <input v-model="form.kode_mapel" type="text" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition uppercase font-mono" placeholder="cth. MTK, BIN, PAI" />
                            <p v-if="form.errors.kode_mapel" class="text-xs text-rose-600 mt-1">{{ form.errors.kode_mapel }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nilai KKM Minimal (0 - 100) <span class="text-rose-600">*</span></label>
                            <input v-model="form.kkm" type="number" min="0" max="100" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition font-mono" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Mata Pelajaran <span class="text-rose-600">*</span></label>
                        <input v-model="form.nama_mapel" type="text" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition font-semibold" placeholder="cth. Matematika, Bahasa Indonesia" />
                        <p v-if="form.errors.nama_mapel" class="text-xs text-rose-600 mt-1">{{ form.errors.nama_mapel }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Guru Pengampu Utama</label>
                        <select v-model="form.guru_id" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition bg-white">
                            <option value="">-- Belum Ditentukan / Guru Kelas --</option>
                            <option v-for="g in guruList" :key="g.id" :value="g.id">{{ g.nama }}</option>
                        </select>
                    </div>

                    <div class="space-y-3 pt-2">
                        <label class="flex items-center gap-2.5 text-xs text-slate-700 cursor-pointer">
                            <input v-model="form.is_muatan_lokal" type="checkbox" class="w-4 h-4 rounded text-primary-600 border-slate-300 focus:ring-primary-600" />
                            <span>Mata Pelajaran Muatan Lokal (Mulok Daerah)</span>
                        </label>

                        <label class="flex items-center gap-2.5 text-xs text-slate-700 cursor-pointer">
                            <input v-model="form.status_aktif" type="checkbox" class="w-4 h-4 rounded text-primary-600 border-slate-300 focus:ring-primary-600" />
                            <span>Status Aktif dalam Kurikulum</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-5 border-t border-slate-200">
                        <Link :href="`/kelas/${kelas.id}?tahun_ajaran_id=${tahunAjaran.id}&tab=mapel`" class="px-4 py-2 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold transition">
                            Batal
                        </Link>
                        <button type="submit" :disabled="form.processing" class="px-5 py-2 rounded-lg bg-primary-700 hover:bg-primary-800 text-white text-xs font-semibold shadow-2xs transition cursor-pointer flex items-center gap-1.5 disabled:opacity-60">
                            <i v-if="form.processing" class="fa-solid fa-spinner fa-spin text-xs"></i>
                            <i v-else class="fa-solid fa-floppy-disk text-xs"></i>
                            <span>Simpan Mata Pelajaran</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
