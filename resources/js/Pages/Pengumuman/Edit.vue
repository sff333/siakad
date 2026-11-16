<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    pengumuman: {
        type: Object,
        required: true,
    },
});

const formatDateInput = (dateStr) => {
    if (!dateStr) return '';
    return dateStr.substring(0, 10);
};

const form = useForm({
    judul: props.pengumuman.judul || '',
    kategori: props.pengumuman.kategori || 'Akademik',
    isi: props.pengumuman.isi || '',
    tanggal_publikasi: formatDateInput(props.pengumuman.tanggal_publikasi),
    is_published: Boolean(props.pengumuman.is_published),
});

const submit = () => {
    form.put(`/pengumuman/${props.pengumuman.id}`);
};
</script>

<template>
    <AppLayout title="Edit Pengumuman" pageTitle="Edit Pengumuman">
        <div class="max-w-3xl mx-auto space-y-4">
            <!-- Back Action & Meta -->
            <div class="flex items-center justify-between">
                <Link
                    href="/pengumuman"
                    class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-primary-700 font-medium transition"
                >
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Kembali ke Daftar Warta</span>
                </Link>
                <span class="text-[11px] font-mono text-slate-400">ID: #{{ pengumuman.id }}</span>
            </div>

            <!-- Form Panel -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-2xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Penyuntingan Maklumat Resmi</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Perubahan yang disimpan langsung diperbarui di portal publik.</p>
                    </div>
                </div>

                <form @submit.prevent="submit" class="p-6 sm:p-8 space-y-5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Judul Maklumat / Pengumuman <span class="text-rose-600">*</span>
                        </label>
                        <input
                            v-model="form.judul"
                            type="text"
                            required
                            class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition font-semibold"
                        />
                        <p v-if="form.errors.judul" class="text-xs text-rose-600 mt-1">{{ form.errors.judul }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Kategori Warta <span class="text-rose-600">*</span>
                            </label>
                            <select
                                v-model="form.kategori"
                                required
                                class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition bg-white"
                            >
                                <option value="Akademik">Akademik & Pembelajaran</option>
                                <option value="Kegiatan">Kegiatan & Ekstrakurikuler</option>
                                <option value="Kedinasan">Kedinasan & Resmi</option>
                                <option value="Libur">Kalender & Hari Libur</option>
                                <option value="Umum">Informasi Umum</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Tanggal Publikasi <span class="text-rose-600">*</span>
                            </label>
                            <input
                                v-model="form.tanggal_publikasi"
                                type="date"
                                required
                                class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition font-mono"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Isi Lengkap Maklumat / Edaran <span class="text-rose-600">*</span>
                        </label>
                        <textarea
                            v-model="form.isi"
                            required
                            rows="6"
                            class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition leading-relaxed"
                        ></textarea>
                        <p v-if="form.errors.isi" class="text-xs text-rose-600 mt-1">{{ form.errors.isi }}</p>
                    </div>

                    <div class="pt-1">
                        <label class="flex items-center gap-2.5 text-xs text-slate-700 cursor-pointer select-none">
                            <input
                                v-model="form.is_published"
                                type="checkbox"
                                class="w-4 h-4 rounded text-primary-600 border-slate-300 focus:ring-primary-600"
                            />
                            <span>Tayangkan warta ini ke publik</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-5 border-t border-slate-200">
                        <Link
                            href="/pengumuman"
                            class="px-4 py-2 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold transition"
                        >
                            Batal
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-5 py-2 rounded-lg bg-primary-700 hover:bg-primary-800 text-white text-xs font-semibold shadow-2xs transition cursor-pointer flex items-center gap-1.5 disabled:opacity-60"
                        >
                            <i v-if="form.processing" class="fa-solid fa-spinner fa-spin text-xs"></i>
                            <i v-else class="fa-solid fa-floppy-disk text-xs"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
