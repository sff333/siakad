<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    guru: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    _method: 'PUT',
    nip: props.guru.nip || '',
    nama: props.guru.nama || '',
    jenis_kelamin: props.guru.jenis_kelamin || 'L',
    tempat_lahir: props.guru.tempat_lahir || '',
    tanggal_lahir: props.guru.tanggal_lahir ? props.guru.tanggal_lahir.substring(0, 10) : '',
    alamat: props.guru.alamat || '',
    telepon: props.guru.telepon || '',
    email: props.guru.email || '',
    jabatan: props.guru.jabatan || '',
    pendidikan_terakhir: props.guru.pendidikan_terakhir || 'S1',
    status_aktif: Boolean(props.guru.status_aktif),
    foto: null,
});

const submit = () => {
    form.post(`/guru/${props.guru.id}`);
};
</script>

<template>
    <AppLayout title="Edit Guru" pageTitle="Edit Guru">
        <div class="max-w-3xl mx-auto space-y-4">
            <div class="flex items-center justify-between">
                <Link href="/guru" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-primary-700 font-medium transition">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Kembali ke Direktori Guru</span>
                </Link>
                <span class="text-[11px] font-mono text-slate-400">{{ guru.nip || 'Non-NIP' }}</span>
            </div>

            <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200 shadow-2xs">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Section 1: Identitas Pribadi -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800 pb-2 border-b border-slate-200 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-id-card text-primary-700"></i>
                            <span>1. Identitas Pribadi & Kepegawaian</span>
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lengkap & Gelar Akademik <span class="text-rose-600">*</span></label>
                                <input v-model="form.nama" type="text" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition font-semibold" placeholder="cth. Budi Santoso, S.Pd." />
                                <p v-if="form.errors.nama" class="text-xs text-rose-600 mt-1">{{ form.errors.nama }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">NIP (Nomor Induk Pegawai)</label>
                                <input v-model="form.nip" type="text" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition font-mono" placeholder="198501012010011001" />
                                <p v-if="form.errors.nip" class="text-xs text-rose-600 mt-1">{{ form.errors.nip }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Jenis Kelamin <span class="text-rose-600">*</span></label>
                                <select v-model="form.jenis_kelamin" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition bg-white">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tempat Lahir</label>
                                <input v-model="form.tempat_lahir" type="text" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Lahir</label>
                                <input v-model="form.tanggal_lahir" type="date" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition font-mono" />
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Penugasan & Pendidikan -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800 pb-2 border-b border-slate-200 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-briefcase text-primary-700"></i>
                            <span>2. Penugasan & Kualifikasi</span>
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Jabatan / Tugas Utama</label>
                                <input v-model="form.jabatan" type="text" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pendidikan Terakhir</label>
                                <select v-model="form.pendidikan_terakhir" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition bg-white">
                                    <option value="D3">Diploma 3 (D3)</option>
                                    <option value="S1">Sarjana (S1 / D4)</option>
                                    <option value="S2">Magister (S2)</option>
                                    <option value="S3">Doktor (S3)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Telepon / WhatsApp</label>
                                <input v-model="form.telepon" type="text" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition font-mono" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Email Resmi</label>
                                <input v-model="form.email" type="email" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition" />
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Ganti Pas Foto Guru</label>
                                <input @input="form.foto = $event.target.files[0]" type="file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer" />
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Alamat & Status -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800 pb-2 border-b border-slate-200 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-primary-700"></i>
                            <span>3. Alamat Domisili & Status</span>
                        </h4>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Tempat Tinggal Lengkap</label>
                                <textarea v-model="form.alamat" rows="2" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition"></textarea>
                            </div>

                            <div class="flex items-center gap-2.5 pt-1">
                                <input v-model="form.status_aktif" type="checkbox" id="status_aktif" class="w-4 h-4 rounded text-primary-600 border-slate-300 focus:ring-primary-600" />
                                <label for="status_aktif" class="text-xs font-semibold text-slate-700 cursor-pointer">Status Aktif Melaksanakan Tugas Pendidik</label>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end gap-2.5 pt-5 border-t border-slate-200">
                        <Link href="/guru" class="px-4 py-2 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold transition">
                            Batal
                        </Link>
                        <button type="submit" :disabled="form.processing" class="px-5 py-2 rounded-lg bg-primary-700 hover:bg-primary-800 text-white text-xs font-semibold shadow-2xs transition cursor-pointer flex items-center gap-1.5 disabled:opacity-60">
                            <i v-if="form.processing" class="fa-solid fa-spinner fa-spin text-xs"></i>
                            <i v-else class="fa-solid fa-floppy-disk text-xs"></i>
                            <span>Simpan Perubahan Data Guru</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
