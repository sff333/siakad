<script setup>
import { ref, computed, watch } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { judulKopRapor } from '@/Utils/kopRapor';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['close']);

const page = usePage();
const currentProfil = computed(() => page.props.profil_sekolah || {});

const activeTab = ref('identitas'); // 'identitas' | 'alamat' | 'logo'

const form = useForm({
    nama_sekolah: '',
    npsn: '',
    nss: '',
    akreditasi: 'A',
    alamat: '',
    kelurahan: '',
    kecamatan: '',
    kabupaten: '',
    provinsi: '',
    kode_pos: '',
    telepon: '',
    email: '',
    website: '',
    kepala_sekolah: '',
    nip_kepala_sekolah: '',
    tipe_garis_kop: 'ganda',
    logo: null,
    logo_kiri: null,
});

const logoPreview = ref(null);
const logoKiriPreview = ref(null);

const populateForm = () => {
    const p = currentProfil.value;
    form.nama_sekolah = p.nama_sekolah || 'SD NEGERI 01 UNGGULAN';
    form.npsn = p.npsn || '';
    form.nss = p.nss || '';
    form.akreditasi = p.akreditasi || 'A';
    form.alamat = p.alamat || '';
    form.kelurahan = p.kelurahan || '';
    form.kecamatan = p.kecamatan || '';
    form.kabupaten = p.kabupaten || '';
    form.provinsi = p.provinsi || '';
    form.kode_pos = p.kode_pos || '';
    form.telepon = p.telepon || '';
    form.email = p.email || '';
    form.website = p.website || '';
    form.kepala_sekolah = p.kepala_sekolah || '';
    form.nip_kepala_sekolah = p.nip_kepala_sekolah || '';
    form.tipe_garis_kop = p.tipe_garis_kop || 'ganda';
    form.logo = null;
    form.logo_kiri = null;
    logoPreview.value = p.logo ? `/storage/${p.logo}` : null;
    logoKiriPreview.value = p.logo_kiri ? `/storage/${p.logo_kiri}` : null;
};

watch(() => props.show, (newVal) => {
    if (newVal) {
        populateForm();
    }
}, { immediate: true });

watch(currentProfil, () => {
    if (props.show) {
        populateForm();
    }
});

const onLogoChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.logo = file;
        logoPreview.value = URL.createObjectURL(file);
    }
};

const onLogoKiriChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.logo_kiri = file;
        logoKiriPreview.value = URL.createObjectURL(file);
    }
};

const previewAddress = computed(() => {
    const parts = [
        form.alamat,
        form.kelurahan ? `Kel. ${form.kelurahan}` : null,
        form.kecamatan ? `Kec. ${form.kecamatan}` : null,
        form.kabupaten,
        form.provinsi,
        form.kode_pos ? `Kode Pos ${form.kode_pos}` : null,
    ].filter(Boolean);
    return parts.join(', ');
});

const submit = () => {
    form.post('/rapor/kop-surat', {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            emit('close');
        },
    });
};
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 sm:p-6">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="emit('close')"></div>

        <!-- Modal Box -->
        <div class="relative bg-white rounded-xl shadow-2xl border border-slate-200 w-full max-w-4xl overflow-hidden flex flex-col max-h-[92vh] z-10">
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-primary-50 text-primary-700 border border-primary-200 flex items-center justify-center text-sm shadow-2xs">
                        <i class="fa-solid fa-heading"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 leading-tight">Pengaturan Kepala Surat (Kop Rapor)</h3>
                        <p class="text-[11px] text-slate-500">Sesuaikan data instansi, lambang resmi, dan pengesahan dokumen rapor</p>
                    </div>
                </div>
                <button
                    type="button"
                    @click="emit('close')"
                    class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-200/60 transition flex items-center justify-center text-sm cursor-pointer"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div class="p-6 overflow-y-auto space-y-6">
                <!-- LIVE PREVIEW KOP SURAT RAPOR -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-[11px] font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                            <i class="fa-solid fa-eye text-primary-700"></i> Pratinjau Lembar Kop Surat
                        </label>
                        <span class="text-[10px] text-slate-400 font-mono">Standar Permendikbud</span>
                    </div>

                    <div class="bg-white p-5 rounded-lg border border-slate-300 text-center relative overflow-hidden">
                        <div class="flex items-center justify-between gap-4">
                            <!-- Logo Kiri -->
                            <div class="w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center shrink-0">
                                <img
                                    v-if="logoKiriPreview"
                                    :src="logoKiriPreview"
                                    alt="Logo Kiri"
                                    class="max-h-full max-w-full object-contain"
                                />
                                <div v-else class="w-14 h-14 rounded border border-dashed border-slate-300 bg-slate-50 flex flex-col items-center justify-center text-slate-400 text-[9px] p-1">
                                    <i class="fa-solid fa-landmark text-xs mb-0.5"></i>
                                    <span>Logo Pemda</span>
                                </div>
                            </div>

                            <!-- Teks Header Kop Surat -->
                            <div class="flex-1 px-2">
                                <h4 class="text-xs sm:text-sm font-bold text-slate-900 uppercase tracking-wider leading-tight">
                                    {{ judulKopRapor(form.kabupaten) }}
                                </h4>
                                <h3 class="text-sm sm:text-lg font-black text-slate-900 uppercase tracking-widest text-primary-700 mt-0.5">
                                    {{ form.nama_sekolah || 'SD NEGERI 01 UNGGULAN' }}
                                </h3>
                                <p class="text-[11px] text-slate-600 mt-1 leading-snug">
                                    {{ previewAddress || 'Alamat Lengkap Satuan Pendidikan' }}
                                </p>
                                <p class="text-[10px] text-slate-500 font-mono mt-0.5">
                                    <span v-if="form.telepon">Telp: {{ form.telepon }}</span>
                                    <span v-if="form.email"> &bull; Pos-el: {{ form.email }}</span>
                                    <span v-if="form.website"> &bull; Website: {{ form.website }}</span>
                                </p>
                                <p class="text-[10px] font-semibold text-slate-600 mt-0.5 font-mono">
                                    NPSN: {{ form.npsn || '-' }} &bull; NSS: {{ form.nss || '-' }} &bull; AKREDITASI: {{ form.akreditasi || 'A' }}
                                </p>
                            </div>

                            <!-- Logo Kanan -->
                            <div class="w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center shrink-0">
                                <img
                                    v-if="logoPreview"
                                    :src="logoPreview"
                                    alt="Logo Sekolah"
                                    class="max-h-full max-w-full object-contain"
                                />
                                <div v-else class="w-14 h-14 rounded border border-dashed border-slate-300 bg-slate-50 flex flex-col items-center justify-center text-slate-400 text-[9px] p-1">
                                    <i class="fa-solid fa-graduation-cap text-xs mb-0.5"></i>
                                    <span>Logo Sekolah</span>
                                </div>
                            </div>
                        </div>

                        <!-- Garis Pembatas Kop -->
                        <div class="mt-3">
                            <div v-if="form.tipe_garis_kop === 'ganda'" class="space-y-0.5">
                                <div class="h-0.5 bg-slate-950 w-full"></div>
                                <div class="h-px bg-slate-950 w-full"></div>
                            </div>
                            <div v-else-if="form.tipe_garis_kop === 'tunggal'" class="h-0.5 bg-slate-950 w-full"></div>
                            <div v-else class="h-px bg-slate-400 w-full"></div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <div class="flex border-b border-slate-200">
                    <button
                        type="button"
                        @click="activeTab = 'identitas'"
                        class="px-4 py-2.5 text-xs font-semibold border-b-2 transition flex items-center gap-1.5 cursor-pointer"
                        :class="activeTab === 'identitas' ? 'border-primary-600 text-primary-700' : 'border-transparent text-slate-500 hover:text-slate-800'"
                    >
                        <i class="fa-solid fa-landmark text-xs"></i> 1. Instansi & Sekolah
                    </button>
                    <button
                        type="button"
                        @click="activeTab = 'alamat'"
                        class="px-4 py-2.5 text-xs font-semibold border-b-2 transition flex items-center gap-1.5 cursor-pointer"
                        :class="activeTab === 'alamat' ? 'border-primary-600 text-primary-700' : 'border-transparent text-slate-500 hover:text-slate-800'"
                    >
                        <i class="fa-solid fa-map-location-dot text-xs"></i> 2. Alamat & Kontak
                    </button>
                    <button
                        type="button"
                        @click="activeTab = 'logo'"
                        class="px-4 py-2.5 text-xs font-semibold border-b-2 transition flex items-center gap-1.5 cursor-pointer"
                        :class="activeTab === 'logo' ? 'border-primary-600 text-primary-700' : 'border-transparent text-slate-500 hover:text-slate-800'"
                    >
                        <i class="fa-solid fa-image text-xs"></i> 3. Lambang & Penandatangan
                    </button>
                </div>

                <!-- Form Inputs -->
                <form id="kopSuratForm" @submit.prevent="submit" class="space-y-4">
                    <!-- Tab 1: Identitas Pokok -->
                    <div v-show="activeTab === 'identitas'" class="space-y-4">
                        <div>
                            <label for="judul-kop-rapor" class="block text-xs font-semibold text-slate-700 mb-1">Judul Kop Rapor</label>
                            <input id="judul-kop-rapor" :value="judulKopRapor(form.kabupaten)" type="text" readonly class="w-full px-3 py-2 rounded-lg border border-slate-300 bg-slate-50 text-xs sm:text-sm text-slate-700" />
                            <p class="mt-1 text-[11px] text-slate-500">Judul otomatis mengikuti data Kabupaten / Kota pada tab Alamat &amp; Kontak.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Satuan Pendidikan (Baris Utama)</label>
                            <input
                                v-model="form.nama_sekolah"
                                type="text"
                                required
                                placeholder="SD NEGERI 01 UNGGULAN"
                                class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition font-semibold"
                            />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">NPSN</label>
                                <input
                                    v-model="form.npsn"
                                    type="text"
                                    placeholder="cth. 10203040"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition font-mono"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">NSS</label>
                                <input
                                    v-model="form.nss"
                                    type="text"
                                    placeholder="Nomor Statistik Sekolah"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition font-mono"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Status Akreditasi</label>
                                <select
                                    v-model="form.akreditasi"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition bg-white"
                                >
                                    <option value="A">Akreditasi A (Unggul)</option>
                                    <option value="B">Akreditasi B (Baik)</option>
                                    <option value="C">Akreditasi C (Cukup)</option>
                                    <option value="Belum">Belum Terakreditasi</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Model Garis Pemisah Kop</label>
                            <div class="grid grid-cols-3 gap-3">
                                <label class="p-2.5 rounded-lg border cursor-pointer text-center text-xs transition" :class="form.tipe_garis_kop === 'ganda' ? 'border-primary-600 bg-primary-50 text-primary-700 font-semibold' : 'border-slate-200 hover:bg-slate-50'">
                                    <input type="radio" value="ganda" v-model="form.tipe_garis_kop" class="sr-only" />
                                    <span>Garis Ganda (Resmi)</span>
                                </label>
                                <label class="p-2.5 rounded-lg border cursor-pointer text-center text-xs transition" :class="form.tipe_garis_kop === 'tunggal' ? 'border-primary-600 bg-primary-50 text-primary-700 font-semibold' : 'border-slate-200 hover:bg-slate-50'">
                                    <input type="radio" value="tunggal" v-model="form.tipe_garis_kop" class="sr-only" />
                                    <span>Garis Tebal Tunggal</span>
                                </label>
                                <label class="p-2.5 rounded-lg border cursor-pointer text-center text-xs transition" :class="form.tipe_garis_kop === 'tipis' ? 'border-primary-600 bg-primary-50 text-primary-700 font-semibold' : 'border-slate-200 hover:bg-slate-50'">
                                    <input type="radio" value="tipis" v-model="form.tipe_garis_kop" class="sr-only" />
                                    <span>Garis Tipis Minimalis</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 2: Alamat & Kontak -->
                    <div v-show="activeTab === 'alamat'" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Jalan / Gedung</label>
                            <input
                                v-model="form.alamat"
                                type="text"
                                placeholder="Jl. Pendidikan No. 12"
                                class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition"
                            />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Kelurahan / Desa</label>
                                <input
                                    v-model="form.kelurahan"
                                    type="text"
                                    placeholder="Kelurahan"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition"
                                />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Kecamatan</label>
                                <input
                                    v-model="form.kecamatan"
                                    type="text"
                                    placeholder="Kecamatan"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Kabupaten / Kota</label>
                                <input
                                    v-model="form.kabupaten"
                                    type="text"
                                    placeholder="Kabupaten / Kota"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition"
                                />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Provinsi</label>
                                <input
                                    v-model="form.provinsi"
                                    type="text"
                                    placeholder="Provinsi"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition"
                                />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Kode Pos</label>
                                <input
                                    v-model="form.kode_pos"
                                    type="text"
                                    placeholder="Kode Pos"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition font-mono"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Telepon</label>
                                <input
                                    v-model="form.telepon"
                                    type="text"
                                    placeholder="(021) 123456"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition font-mono"
                                />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Email Resmi</label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    placeholder="sdn01@sekolah.sch.id"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition"
                                />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Website Laman</label>
                                <input
                                    v-model="form.website"
                                    type="text"
                                    placeholder="www.sdn01.sch.id"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Tab 3: Logo & Penandatangan -->
                    <div v-show="activeTab === 'logo'" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Logo Kiri -->
                            <div class="p-4 rounded-lg border border-slate-200 bg-slate-50 space-y-2">
                                <label class="block text-xs font-bold text-slate-800">
                                    <i class="fa-solid fa-landmark text-primary-600 mr-1"></i> Logo Dinas / Pemda (Sisi Kiri)
                                </label>
                                <p class="text-[11px] text-slate-500">Lambang daerah pemerintah kabupaten/kota atau Tut Wuri Handayani.</p>
                                <input
                                    type="file"
                                    accept="image/*"
                                    @change="onLogoKiriChange"
                                    class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer"
                                />
                            </div>

                            <!-- Logo Kanan -->
                            <div class="p-4 rounded-lg border border-slate-200 bg-slate-50 space-y-2">
                                <label class="block text-xs font-bold text-slate-800">
                                    <i class="fa-solid fa-graduation-cap text-primary-600 mr-1"></i> Logo Satuan Pendidikan (Sisi Kanan)
                                </label>
                                <p class="text-[11px] text-slate-500">Lambang resmi sekolah yang dicetak pada sisi kanan kepala surat.</p>
                                <input
                                    type="file"
                                    accept="image/*"
                                    @change="onLogoChange"
                                    class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer"
                                />
                            </div>
                        </div>

                        <!-- Data Pengesahan Rapor (Kepala Sekolah) -->
                        <div class="pt-3 border-t border-slate-200">
                            <h5 class="text-xs font-bold uppercase tracking-wider text-slate-800 mb-3 flex items-center gap-1.5">
                                <i class="fa-solid fa-signature text-primary-700"></i> Pejabat Pengesahan Lembar Rapor
                            </h5>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Kepala Sekolah & Gelar</label>
                                    <input
                                        v-model="form.kepala_sekolah"
                                        type="text"
                                        placeholder="Drs. Ahmad Sudirman, M.Pd"
                                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition font-semibold"
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">NIP Kepala Sekolah</label>
                                    <input
                                        v-model="form.nip_kepala_sekolah"
                                        type="text"
                                        placeholder="196501011990031001"
                                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition font-mono"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                <span class="text-[11px] text-slate-500">
                    <i class="fa-solid fa-circle-check text-emerald-600 mr-1"></i> Perubahan tersimpan untuk seluruh cetak rapor
                </span>

                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        @click="emit('close')"
                        class="px-4 py-2 rounded-lg bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs font-semibold transition cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        form="kopSuratForm"
                        :disabled="form.processing"
                        class="px-5 py-2 rounded-lg bg-primary-700 hover:bg-primary-800 text-white text-xs font-semibold shadow-2xs transition cursor-pointer flex items-center gap-1.5"
                    >
                        <i v-if="form.processing" class="fa-solid fa-spinner fa-spin text-xs"></i>
                        <i v-else class="fa-solid fa-floppy-disk text-xs"></i>
                        <span>Simpan Kepala Surat</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
