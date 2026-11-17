<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    profil: {
        type: Object,
        default: () => null,
    },
});

const logoPreview = ref(props.profil?.logo ? `/storage/${props.profil.logo}` : null);

const form = useForm({
    _method: 'PUT',
    nama_sekolah: props.profil?.nama_sekolah || '',
    npsn: props.profil?.npsn || '',
    nss: props.profil?.nss || '',
    akreditasi: props.profil?.akreditasi || 'A',
    alamat: props.profil?.alamat || '',
    kelurahan: props.profil?.kelurahan || '',
    kecamatan: props.profil?.kecamatan || '',
    kabupaten: props.profil?.kabupaten || '',
    provinsi: props.profil?.provinsi || '',
    kode_pos: props.profil?.kode_pos || '',
    telepon: props.profil?.telepon || '',
    email: props.profil?.email || '',
    website: props.profil?.website || '',
    kepala_sekolah: props.profil?.kepala_sekolah || '',
    nip_kepala_sekolah: props.profil?.nip_kepala_sekolah || '',
    logo: null,
    visi: props.profil?.visi || '',
    misi: props.profil?.misi || '',
});

const onFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.logo = file;
        logoPreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post('/profil-sekolah', {
        forceFormData: true,
    });
};
</script>

<template>
    <AppLayout title="Profil Sekolah" pageTitle="Profil Sekolah">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header Summary Card -->
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-2xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-xl border border-slate-200 bg-slate-50 p-1.5 flex items-center justify-center shrink-0 shadow-2xs">
                        <img src="/images/logo.png" alt="Logo Sekolah" class="w-full h-full object-contain" />
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h2 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight">
                                {{ form.nama_sekolah || 'Nama Satuan Pendidikan' }}
                            </h2>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-primary-50 text-primary-800 border border-primary-200">
                                Akreditasi {{ form.akreditasi || 'A' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-slate-500 mt-1 flex-wrap font-mono">
                            <span>NPSN: {{ form.npsn || '-' }}</span>
                            <span class="text-slate-300">&bull;</span>
                            <span>NSS: {{ form.nss || '-' }}</span>
                            <span class="text-slate-300">&bull;</span>
                            <span class="font-sans text-slate-600">Kepsek: {{ form.kepala_sekolah || '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="shrink-0 w-full sm:w-auto">
                    <button
                        type="button"
                        @click="submit"
                        :disabled="form.processing"
                        class="w-full sm:w-auto px-4 py-2 rounded-lg bg-primary-700 hover:bg-primary-800 text-white text-xs font-semibold shadow-2xs transition inline-flex items-center justify-center gap-1.5 disabled:opacity-60 cursor-pointer"
                    >
                        <i v-if="form.processing" class="fa-solid fa-spinner fa-spin text-xs"></i>
                        <i v-else class="fa-solid fa-floppy-disk text-xs"></i>
                        <span>Simpan Profil</span>
                    </button>
                </div>
            </div>

            <!-- Form Container -->
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Section 1: Identitas Pokok & Logo -->
                <div class="bg-white border border-slate-200 rounded-xl shadow-2xs overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-slate-200 bg-slate-50/50 flex items-center justify-between">
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">1. Identitas Pokok & Lambang Resmi</h3>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6 space-y-5">
                        <!-- Logo Upload Row -->
                        <div class="p-4 rounded-lg border border-slate-200 bg-slate-50 flex flex-col sm:flex-row items-center gap-5">
                            <div class="w-20 h-20 rounded-xl bg-white border border-slate-200 overflow-hidden flex items-center justify-center shrink-0 shadow-2xs p-2">
                               <img src="/images/logo-sekolah.png" alt="Logo Sekolah" class="w-full h-full object-contain" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <div class="sm:col-span-2 md:col-span-3">
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Nama Resmi Satuan Pendidikan <span class="text-rose-600">*</span>
                                </label>
                                <input
                                    v-model="form.nama_sekolah"
                                    type="text"
                                    required
                                    placeholder="Contoh: SD Negeri Unggulan 01"
                                    class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition font-semibold"
                                />
                                <p v-if="form.errors.nama_sekolah" class="text-xs text-rose-600 mt-1 font-medium">{{ form.errors.nama_sekolah }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nomor Pokok Sekolah Nasional (NPSN)</label>
                                <input
                                    v-model="form.npsn"
                                    type="text"
                                    placeholder="8 digit NPSN"
                                    class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition font-mono"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nomor Statistik Sekolah (NSS)</label>
                                <input
                                    v-model="form.nss"
                                    type="text"
                                    placeholder="NSS Satuan Pendidikan"
                                    class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition font-mono"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Akreditasi Badan Akreditasi</label>
                                <select
                                    v-model="form.akreditasi"
                                    class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition bg-white"
                                >
                                    <option value="A">Terakreditasi A (Unggul)</option>
                                    <option value="B">Terakreditasi B (Baik)</option>
                                    <option value="C">Terakreditasi C (Cukup)</option>
                                    <option value="Belum">Belum Terakreditasi</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Alamat Domisili -->
                <div class="bg-white border border-slate-200 rounded-xl shadow-2xs overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-slate-200 bg-slate-50/50">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">2. Domisili & Wilayah Administratif</h3>
                    </div>

                    <div class="p-5 sm:p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Jalan / Gedung</label>
                            <input
                                v-model="form.alamat"
                                type="text"
                                placeholder="Jl. Ki Hajar Dewantara No. 01"
                                class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition"
                            />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kelurahan / Desa</label>
                                <input
                                    v-model="form.kelurahan"
                                    type="text"
                                    class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-primary-600 outline-none transition"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kecamatan</label>
                                <input
                                    v-model="form.kecamatan"
                                    type="text"
                                    class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-primary-600 outline-none transition"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kabupaten / Kota</label>
                                <input
                                    v-model="form.kabupaten"
                                    type="text"
                                    class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-primary-600 outline-none transition"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Provinsi</label>
                                <input
                                    v-model="form.provinsi"
                                    type="text"
                                    class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-primary-600 outline-none transition"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kode Pos</label>
                                <input
                                    v-model="form.kode_pos"
                                    type="text"
                                    class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-primary-600 outline-none transition font-mono"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Kontak & Kanal Digital -->
                <div class="bg-white border border-slate-200 rounded-xl shadow-2xs overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-slate-200 bg-slate-50/50">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">3. Kontak Resmi & Kanal Publikasi</h3>
                    </div>

                    <div class="p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Telepon Satuan Pendidikan</label>
                            <input
                                v-model="form.telepon"
                                type="text"
                                placeholder="(021) 1234567"
                                class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-primary-600 outline-none transition font-mono"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email Resmi</label>
                            <input
                                v-model="form.email"
                                type="email"
                                placeholder="sdn01@sekolah.sch.id"
                                class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-primary-600 outline-none transition"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Website Resmi</label>
                            <input
                                v-model="form.website"
                                type="text"
                                placeholder="www.sdn01.sch.id"
                                class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-primary-600 outline-none transition"
                            />
                        </div>
                    </div>
                </div>

                <!-- Section 4: Pimpinan Sekolah -->
                <div class="bg-white border border-slate-200 rounded-xl shadow-2xs overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-slate-200 bg-slate-50/50">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">4. Pimpinan Lembaga (Kepala Sekolah)</h3>
                    </div>

                    <div class="p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lengkap Kepala Sekolah Beserta Gelar</label>
                            <input
                                v-model="form.kepala_sekolah"
                                type="text"
                                placeholder="Contoh: Dra. Hj. Siti Rahmawati, M.Pd."
                                class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-primary-600 outline-none transition font-semibold"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nomor Induk Pegawai (NIP)</label>
                            <input
                                v-model="form.nip_kepala_sekolah"
                                type="text"
                                placeholder="19780512 200312 1 002"
                                class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-primary-600 outline-none transition font-mono"
                            />
                        </div>
                    </div>
                </div>

                <!-- Section 5: Visi & Misi Pendidikan (disembunyikan) -->
                <div v-if="false" class="bg-white border border-slate-200 rounded-xl shadow-2xs overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-slate-200 bg-slate-50/50">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">5. Haluan Pedagogik & Visi-Misi Lembaga</h3>
                    </div>

                    <div class="p-5 sm:p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Visi Satuan Pendidikan</label>
                            <textarea
                                v-model="form.visi"
                                rows="3"
                                placeholder="Tuliskan rumusan visi sekolah..."
                                class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition leading-relaxed"
                            ></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Misi Satuan Pendidikan</label>
                            <textarea
                                v-model="form.misi"
                                rows="4"
                                placeholder="Tuliskan butir-butir misi sekolah per baris..."
                                class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition leading-relaxed"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- Sticky Bottom Save Bar -->
                <div class="flex items-center justify-between p-4 bg-white border border-slate-200 rounded-xl shadow-2xs">
                    <div class="text-[11px] text-slate-500 flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-slate-400"></i>
                        <span>Data disimpan ke basis data Dapodik lokal sistem akademik.</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-5 py-2 rounded-lg bg-primary-700 hover:bg-primary-800 text-white text-xs font-semibold shadow-2xs transition inline-flex items-center gap-1.5 disabled:opacity-60 cursor-pointer"
                        >
                            <i v-if="form.processing" class="fa-solid fa-spinner fa-spin text-xs"></i>
                            <i v-else class="fa-solid fa-floppy-disk text-xs"></i>
                            <span>Simpan Seluruh Konfigurasi</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
