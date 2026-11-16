<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    siswa: {
        type: Object,
        required: true,
    },
    kelasList: {
        type: Array,
        default: () => [],
    },
});

const formatDateInput = (dateStr) => {
    if (!dateStr) return '';
    return dateStr.substring(0, 10);
};

const fotoPreview = ref(props.siswa.foto ? `/storage/${props.siswa.foto}` : null);

const form = useForm({
    _method: 'PUT',
    // I. Identitas Diri
    nis: props.siswa.nis || '',
    nisn: props.siswa.nisn || '',
    nik: props.siswa.nik || '',
    nama: props.siswa.nama || '',
    nama_panggilan: props.siswa.nama_panggilan || '',
    jenis_kelamin: props.siswa.jenis_kelamin || 'L',
    tempat_lahir: props.siswa.tempat_lahir || '',
    tanggal_lahir: formatDateInput(props.siswa.tanggal_lahir),
    agama: props.siswa.agama || 'Islam',
    kewarganegaraan: props.siswa.kewarganegaraan || 'WNI',
    anak_ke: props.siswa.anak_ke ?? '',
    jumlah_saudara: props.siswa.jumlah_saudara ?? '',
    golongan_darah: props.siswa.golongan_darah || '-',
    tinggi_badan: props.siswa.tinggi_badan ?? '',
    berat_badan: props.siswa.berat_badan ?? '',
    kelas_id: props.siswa.kelas_id || '',
    status: props.siswa.status || 'aktif',
    foto: null,

    // II. Domisili
    alamat: props.siswa.alamat || '',
    rt: props.siswa.rt || '',
    rw: props.siswa.rw || '',
    kelurahan: props.siswa.kelurahan || '',
    kecamatan: props.siswa.kecamatan || '',
    kabupaten: props.siswa.kabupaten || '',
    provinsi: props.siswa.provinsi || '',
    kode_pos: props.siswa.kode_pos || '',
    tinggal_bersama: props.siswa.tinggal_bersama || 'Orang Tua Kandung',

    // III. Orang Tua & Wali
    telepon_ortu: props.siswa.telepon_ortu || '',
    nama_ayah: props.siswa.nama_ayah || '',
    nik_ayah: props.siswa.nik_ayah || '',
    pekerjaan_ayah: props.siswa.pekerjaan_ayah || '',

    nama_ibu: props.siswa.nama_ibu || '',
    nik_ibu: props.siswa.nik_ibu || '',
    pekerjaan_ibu: props.siswa.pekerjaan_ibu || '',

    nama_wali: props.siswa.nama_wali || '',
    hubungan_wali: props.siswa.hubungan_wali || '',
    telepon_wali: props.siswa.telepon_wali || '',

    // IV. Sekolah Asal & Penerimaan
    tanggal_masuk: formatDateInput(props.siswa.tanggal_masuk),
    asal_sekolah: props.siswa.asal_sekolah || '',
    nomor_ijazah_sebelumnya: props.siswa.nomor_ijazah_sebelumnya || '',
});

const onFotoChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.foto = file;
        fotoPreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post(`/siswa/${props.siswa.id}`, {
        forceFormData: true,
    });
};
</script>

<template>
    <AppLayout title="Edit Siswa" pageTitle="Edit Siswa">
        <div class="max-w-4xl mx-auto space-y-4">
            <!-- Navigation Back -->
            <div class="flex items-center justify-between">
                <Link href="/siswa" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-primary-700 font-medium transition">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Kembali ke Buku Induk Siswa</span>
                </Link>
                <span class="text-[11px] font-mono text-slate-400">NIS: {{ siswa.nis }}</span>
            </div>

            <!-- Form Container -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Perbarui Lembar Buku Induk</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">{{ siswa.nama }} (NISN: {{ siswa.nisn || '-' }})</p>
                    </div>
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-200 text-slate-700">
                        EDIT DATA
                    </span>
                </div>

                <form @submit.prevent="submit" class="p-6 sm:p-8 space-y-8">
                    <!-- I. Data Diri & Identitas Pokok -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-primary-700 pb-2 border-b border-slate-200 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-id-card text-xs"></i>
                            <span>I. Keterangan Identitas Peserta Didik</span>
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nomor Induk Siswa (NIS) <span class="text-rose-600">*</span></label>
                                <input v-model="form.nis" type="text" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition font-mono" />
                                <p v-if="form.errors.nis" class="text-xs text-rose-600 mt-1">{{ form.errors.nis }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">NISN Nasional <span class="text-rose-600">*</span></label>
                                <input v-model="form.nisn" type="text" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition font-mono" />
                                <p v-if="form.errors.nisn" class="text-xs text-rose-600 mt-1">{{ form.errors.nisn }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">NIK (16 Digit)</label>
                                <input v-model="form.nik" type="text" maxlength="16" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition font-mono" />
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lengkap (Sesuai Akta) <span class="text-rose-600">*</span></label>
                                <input v-model="form.nama" type="text" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 focus:ring-1 focus:ring-primary-600 outline-none transition font-semibold" />
                                <p v-if="form.errors.nama" class="text-xs text-rose-600 mt-1">{{ form.errors.nama }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Panggilan</label>
                                <input v-model="form.nama_panggilan" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Jenis Kelamin <span class="text-rose-600">*</span></label>
                                <select v-model="form.jenis_kelamin" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition bg-white">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tempat Lahir</label>
                                <input v-model="form.tempat_lahir" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Lahir <span class="text-rose-600">*</span></label>
                                <input v-model="form.tanggal_lahir" type="date" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition font-mono" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Agama</label>
                                <select v-model="form.agama" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition bg-white">
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen Protestan">Kristen Protestan</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Rombel Kelas</label>
                                <select v-model="form.kelas_id" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition bg-white font-semibold">
                                    <option value="">-- Belum Ditentukan --</option>
                                    <option v-for="k in kelasList" :key="k.id" :value="k.id">
                                        {{ k.nama_kelas }} (Tingkat {{ k.tingkat }})
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Siswa</label>
                                <select v-model="form.status" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition bg-white font-medium">
                                    <option value="aktif">Aktif</option>
                                    <option value="lulus">Lulus</option>
                                    <option value="pindah">Mutasi Keluar</option>
                                    <option value="keluar">Keluar / DO</option>
                                </select>
                            </div>

                            <!-- Foto Siswa -->
                            <div class="sm:col-span-2 flex items-center gap-4 p-3 rounded-lg border border-slate-200 bg-slate-50">
                                <div class="w-14 h-16 rounded-md border border-slate-300 bg-white flex items-center justify-center shrink-0 overflow-hidden">
                                    <img v-if="fotoPreview" :src="fotoPreview" class="w-full h-full object-cover" />
                                    <i v-else class="fa-solid fa-camera text-slate-300 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Ganti Pas Foto Siswa (3x4)</label>
                                    <input type="file" accept="image/*" @change="onFotoChange" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- II. Alamat & Domisili -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-primary-700 pb-2 border-b border-slate-200 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-xs"></i>
                            <span>II. Alamat Domisili & Tempat Tinggal</span>
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="sm:col-span-2 md:col-span-4">
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Jalan / Dusun</label>
                                <input v-model="form.alamat" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">RT / RW</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input v-model="form.rt" type="text" placeholder="RT" class="w-full px-2.5 py-2 rounded-lg border border-slate-300 text-xs font-mono" />
                                    <input v-model="form.rw" type="text" placeholder="RW" class="w-full px-2.5 py-2 rounded-lg border border-slate-300 text-xs font-mono" />
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kelurahan / Desa</label>
                                <input v-model="form.kelurahan" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:border-primary-600 outline-none transition" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kecamatan</label>
                                <input v-model="form.kecamatan" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:border-primary-600 outline-none transition" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kabupaten / Kota</label>
                                <input v-model="form.kabupaten" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:border-primary-600 outline-none transition" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Provinsi</label>
                                <input v-model="form.provinsi" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:border-primary-600 outline-none transition" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kode Pos</label>
                                <input v-model="form.kode_pos" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:border-primary-600 outline-none transition font-mono" />
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tinggal Bersama</label>
                                <select v-model="form.tinggal_bersama" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:border-primary-600 outline-none transition bg-white">
                                    <option value="Orang Tua Kandung">Orang Tua Kandung</option>
                                    <option value="Wali / Kerabat">Wali / Kerabat</option>
                                    <option value="Panti Asuhan">Panti Asuhan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- III. Orang Tua & Wali -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-primary-700 pb-2 border-b border-slate-200 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-users text-xs"></i>
                            <span>III. Keterangan Orang Tua & Wali</span>
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Ayah -->
                            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/60 space-y-3">
                                <h5 class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                    <i class="fa-solid fa-user text-slate-400"></i> Data Ayah Kandung
                                </h5>

                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">Nama Lengkap Ayah</label>
                                    <input v-model="form.nama_ayah" type="text" class="w-full px-3 py-1.5 rounded-lg border border-slate-300 text-xs focus:border-primary-600 outline-none transition" />
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">NIK Ayah (16 Digit)</label>
                                    <input v-model="form.nik_ayah" type="text" maxlength="16" class="w-full px-3 py-1.5 rounded-lg border border-slate-300 text-xs focus:border-primary-600 outline-none transition font-mono" />
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">Pekerjaan Utama</label>
                                    <input v-model="form.pekerjaan_ayah" type="text" class="w-full px-3 py-1.5 rounded-lg border border-slate-300 text-xs focus:border-primary-600 outline-none transition" />
                                </div>
                            </div>

                            <!-- Ibu -->
                            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/60 space-y-3">
                                <h5 class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                    <i class="fa-solid fa-user text-slate-400"></i> Data Ibu Kandung
                                </h5>

                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">Nama Lengkap Ibu</label>
                                    <input v-model="form.nama_ibu" type="text" class="w-full px-3 py-1.5 rounded-lg border border-slate-300 text-xs focus:border-primary-600 outline-none transition" />
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">NIK Ibu (16 Digit)</label>
                                    <input v-model="form.nik_ibu" type="text" maxlength="16" class="w-full px-3 py-1.5 rounded-lg border border-slate-300 text-xs focus:border-primary-600 outline-none transition font-mono" />
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">Pekerjaan Utama</label>
                                    <input v-model="form.pekerjaan_ibu" type="text" class="w-full px-3 py-1.5 rounded-lg border border-slate-300 text-xs focus:border-primary-600 outline-none transition" />
                                </div>
                            </div>
                        </div>

                        <!-- Kontak & Wali -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mt-4 pt-3 border-t border-slate-100">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">No. Telepon / WA Orang Tua</label>
                                <input v-model="form.telepon_ortu" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition font-mono" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Wali (Opsional)</label>
                                <input v-model="form.nama_wali" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Hubungan Wali</label>
                                <input v-model="form.hubungan_wali" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">No. Telepon Wali</label>
                                <input v-model="form.telepon_wali" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-primary-600 outline-none transition font-mono" />
                            </div>
                        </div>
                    </div>

                    <!-- Action Bar -->
                    <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-200">
                        <Link href="/siswa" class="px-4 py-2 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold transition">
                            Batal
                        </Link>
                        <button type="submit" :disabled="form.processing" class="px-5 py-2 rounded-lg bg-primary-700 hover:bg-primary-800 text-white text-xs font-semibold shadow-2xs transition cursor-pointer flex items-center gap-1.5 disabled:opacity-60">
                            <i v-if="form.processing" class="fa-solid fa-spinner fa-spin text-xs"></i>
                            <i v-else class="fa-solid fa-floppy-disk text-xs"></i>
                            <span>Simpan Perubahan Data Siswa</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
