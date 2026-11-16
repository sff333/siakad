<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    siswa: {
        type: Object,
        required: true,
    },
});

const formatTanggal = (dateStr) => {
    if (!dateStr) return '-';
    try {
        const d = new Date(dateStr);
        if (isNaN(d.getTime())) return dateStr;
        return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    } catch {
        return dateStr;
    }
};
</script>

<template>
    <AppLayout title="Detail Siswa" pageTitle="Detail Siswa">
        <div class="max-w-4xl mx-auto space-y-5">
            <!-- Breadcrumbs Action Bar -->
            <div class="flex items-center justify-between">
                <Link href="/siswa" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-primary-700 font-medium transition">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Kembali ke Direktori Siswa</span>
                </Link>
                <div class="flex items-center gap-2">
                    <Link
                        :href="`/rapor/${siswa.id}`"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-2xs transition"
                    >
                        <i class="fa-solid fa-file-invoice text-slate-400"></i>
                        <span>Buka E-Rapor</span>
                    </Link>
                    <Link
                        :href="`/siswa/${siswa.id}/edit`"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-primary-700 hover:bg-primary-800 text-white text-xs font-semibold shadow-2xs transition"
                    >
                        <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                        <span>Edit Data Buku Induk</span>
                    </Link>
                </div>
            </div>

            <!-- Profile Dossier Card -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
                <!-- Header Dossier Summary -->
                <div class="p-6 border-b border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row items-center sm:items-start gap-5 text-center sm:text-left">
                    <div class="w-24 h-24 rounded-xl bg-white border border-slate-200 text-slate-700 flex items-center justify-center font-bold text-3xl shrink-0 overflow-hidden shadow-2xs">
                        <img v-if="siswa.foto" :src="`/storage/${siswa.foto}`" class="w-full h-full object-cover" />
                        <span v-else class="text-slate-400">{{ (siswa.nama || 'S').charAt(0).toUpperCase() }}</span>
                    </div>

                    <div class="flex-1 space-y-1.5">
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5">
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">{{ siswa.nama || 'Belum diisi' }}</h2>
                            <span v-if="siswa.nama_panggilan" class="text-xs text-slate-500 font-medium">({{ siswa.nama_panggilan }})</span>
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider"
                                :class="siswa.status === 'aktif' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200'"
                            >
                                <span class="w-1.5 h-1.5 rounded-full" :class="siswa.status === 'aktif' ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                                {{ siswa.status === 'aktif' ? 'Siswa Aktif' : siswa.status }}
                            </span>
                        </div>

                        <div class="flex items-center justify-center sm:justify-start gap-3 text-xs text-slate-500 flex-wrap">
                            <span class="font-mono">NIS: <strong class="text-slate-800 font-semibold">{{ siswa.nis || '-' }}</strong></span>
                            <span class="text-slate-300">&bull;</span>
                            <span class="font-mono">NISN: <strong class="text-slate-800 font-semibold">{{ siswa.nisn || '-' }}</strong></span>
                            <span class="text-slate-300">&bull;</span>
                            <span class="font-mono">NIK: <strong class="text-slate-800 font-semibold">{{ siswa.nik || '-' }}</strong></span>
                            <span class="text-slate-300">&bull;</span>
                            <span>Rombel: <strong class="text-slate-800 font-semibold">{{ siswa.kelas?.nama_kelas || 'Belum Terdaftar' }}</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Buku Induk Detailed Sections -->
                <div class="p-6 sm:p-8 space-y-8">
                    <!-- I. Identitas Diri -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-primary-700 mb-4 pb-2 border-b border-slate-200 flex items-center gap-2">
                            <i class="fa-solid fa-id-card text-xs"></i>
                            <span>I. Keterangan Identitas Peserta Didik</span>
                        </h4>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-1">
                            <table class="w-full text-xs border-collapse">
                                <tbody>
                                    <tr class="align-top">
                                        <td class="py-1.5 pr-2 w-44 text-slate-500 whitespace-nowrap">Nomor Induk Siswa (NIS)</td>
                                        <td class="py-1.5 px-2 w-4 text-center text-slate-400">:</td>
                                        <td class="py-1.5 font-mono font-semibold text-slate-900">{{ siswa.nis || '-' }}</td>
                                    </tr>
                                    <tr class="align-top">
                                        <td class="py-1.5 pr-2 text-slate-500 whitespace-nowrap">NISN Nasional</td>
                                        <td class="py-1.5 px-2 text-center text-slate-400">:</td>
                                        <td class="py-1.5 font-mono font-semibold text-slate-900">{{ siswa.nisn || '-' }}</td>
                                    </tr>
                                    <tr class="align-top">
                                        <td class="py-1.5 pr-2 text-slate-500 whitespace-nowrap">Nomor Induk Kependudukan (NIK)</td>
                                        <td class="py-1.5 px-2 text-center text-slate-400">:</td>
                                        <td class="py-1.5 font-mono font-semibold text-slate-900">{{ siswa.nik || '-' }}</td>
                                    </tr>
                                    <tr class="align-top">
                                        <td class="py-1.5 pr-2 text-slate-500 whitespace-nowrap">Nama Lengkap Siswa</td>
                                        <td class="py-1.5 px-2 text-center text-slate-400">:</td>
                                        <td class="py-1.5 font-bold text-slate-900">{{ siswa.nama || 'Belum diisi' }}</td>
                                    </tr>
                                    <tr class="align-top">
                                        <td class="py-1.5 pr-2 text-slate-500 whitespace-nowrap">Nama Panggilan</td>
                                        <td class="py-1.5 px-2 text-center text-slate-400">:</td>
                                        <td class="py-1.5 font-semibold text-slate-900">{{ siswa.nama_panggilan || '-' }}</td>
                                    </tr>
                                    <tr class="align-top">
                                        <td class="py-1.5 pr-2 text-slate-500 whitespace-nowrap">Jenis Kelamin</td>
                                        <td class="py-1.5 px-2 text-center text-slate-400">:</td>
                                        <td class="py-1.5 font-semibold text-slate-900">{{ siswa.jenis_kelamin === 'L' ? 'Laki-laki' : siswa.jenis_kelamin === 'P' ? 'Perempuan' : 'Belum diisi' }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="w-full text-xs border-collapse">
                                <tbody>
                                    <tr class="align-top">
                                        <td class="py-1.5 pr-2 w-44 text-slate-500 whitespace-nowrap">Tempat, Tanggal Lahir</td>
                                        <td class="py-1.5 px-2 w-4 text-center text-slate-400">:</td>
                                        <td class="py-1.5 font-semibold text-slate-900">
                                            {{ siswa.tempat_lahir || '-' }}, {{ formatTanggal(siswa.tanggal_lahir) }}
                                        </td>
                                    </tr>
                                    <tr class="align-top">
                                        <td class="py-1.5 pr-2 text-slate-500 whitespace-nowrap">Agama</td>
                                        <td class="py-1.5 px-2 text-center text-slate-400">:</td>
                                        <td class="py-1.5 font-semibold text-slate-900">{{ siswa.agama || '-' }}</td>
                                    </tr>
                                    <tr class="align-top">
                                        <td class="py-1.5 pr-2 text-slate-500 whitespace-nowrap">Kewarganegaraan</td>
                                        <td class="py-1.5 px-2 text-center text-slate-400">:</td>
                                        <td class="py-1.5 font-semibold text-slate-900">{{ siswa.kewarganegaraan || 'WNI' }}</td>
                                    </tr>
                                    <tr class="align-top">
                                        <td class="py-1.5 pr-2 text-slate-500 whitespace-nowrap">Rombongan Belajar</td>
                                        <td class="py-1.5 px-2 text-center text-slate-400">:</td>
                                        <td class="py-1.5 font-semibold text-slate-900">{{ siswa.kelas?.nama_kelas || '-' }}</td>
                                    </tr>
                                    <tr class="align-top">
                                        <td class="py-1.5 pr-2 text-slate-500 whitespace-nowrap">Status Siswa</td>
                                        <td class="py-1.5 px-2 text-center text-slate-400">:</td>
                                        <td class="py-1.5 font-semibold text-slate-900 capitalize">{{ siswa.status }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- II. Alamat Domisili -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-primary-700 mb-4 pb-2 border-b border-slate-200 flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-xs"></i>
                            <span>II. Alamat Domisili & Tempat Tinggal</span>
                        </h4>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-1">
                            <table class="w-full text-xs border-collapse">
                                <tbody>
                                    <tr class="align-top">
                                        <td class="py-1.5 pr-2 w-44 text-slate-500 whitespace-nowrap">Alamat Lengkap</td>
                                        <td class="py-1.5 px-2 w-4 text-center text-slate-400">:</td>
                                        <td class="py-1.5 font-semibold text-slate-900">{{ siswa.alamat || '-' }}</td>
                                    </tr>
                                    <tr class="align-top">
                                        <td class="py-1.5 pr-2 text-slate-500 whitespace-nowrap">RT / RW</td>
                                        <td class="py-1.5 px-2 text-center text-slate-400">:</td>
                                        <td class="py-1.5 font-mono font-semibold text-slate-900">{{ siswa.rt || '-' }} / {{ siswa.rw || '-' }}</td>
                                    </tr>
                                    <tr class="align-top">
                                        <td class="py-1.5 pr-2 text-slate-500 whitespace-nowrap">Kelurahan / Desa</td>
                                        <td class="py-1.5 px-2 text-center text-slate-400">:</td>
                                        <td class="py-1.5 font-semibold text-slate-900">{{ siswa.kelurahan || '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="w-full text-xs border-collapse">
                                <tbody>
                                    <tr class="align-top">
                                        <td class="py-1.5 pr-2 w-44 text-slate-500 whitespace-nowrap">Kecamatan</td>
                                        <td class="py-1.5 px-2 w-4 text-center text-slate-400">:</td>
                                        <td class="py-1.5 font-semibold text-slate-900">{{ siswa.kecamatan || '-' }}</td>
                                    </tr>
                                    <tr class="align-top">
                                        <td class="py-1.5 pr-2 text-slate-500 whitespace-nowrap">Kabupaten / Kota</td>
                                        <td class="py-1.5 px-2 text-center text-slate-400">:</td>
                                        <td class="py-1.5 font-semibold text-slate-900">{{ siswa.kabupaten || '-' }}</td>
                                    </tr>
                                    <tr class="align-top">
                                        <td class="py-1.5 pr-2 text-slate-500 whitespace-nowrap">Tinggal Bersama</td>
                                        <td class="py-1.5 px-2 text-center text-slate-400">:</td>
                                        <td class="py-1.5 font-semibold text-slate-900">{{ siswa.tinggal_bersama || '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- III. Orang Tua & Wali -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-primary-700 mb-4 pb-2 border-b border-slate-200 flex items-center gap-2">
                            <i class="fa-solid fa-users text-xs"></i>
                            <span>III. Keterangan Orang Tua & Wali</span>
                        </h4>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-1">
                            <table class="w-full text-xs border-collapse">
                                <tbody>
                                    <tr class="align-top">
                                        <td class="py-1.5 pr-2 w-44 text-slate-500 whitespace-nowrap">Nama Ayah Kandung</td>
                                        <td class="py-1.5 px-2 w-4 text-center text-slate-400">:</td>
                                        <td class="py-1.5 font-bold text-slate-900">{{ siswa.nama_ayah || '-' }}</td>
                                    </tr>
                                    <tr class="align-top">
                                        <td class="py-1.5 pr-2 text-slate-500 whitespace-nowrap">NIK Ayah</td>
                                        <td class="py-1.5 px-2 text-center text-slate-400">:</td>
                                        <td class="py-1.5 font-mono font-semibold text-slate-900">{{ siswa.nik_ayah || '-' }}</td>
                                    </tr>
                                    <tr class="align-top">
                                        <td class="py-1.5 pr-2 text-slate-500 whitespace-nowrap">Pekerjaan Ayah</td>
                                        <td class="py-1.5 px-2 text-center text-slate-400">:</td>
                                        <td class="py-1.5 font-semibold text-slate-900">{{ siswa.pekerjaan_ayah || '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="w-full text-xs border-collapse">
                                <tbody>
                                    <tr class="align-top">
                                        <td class="py-1.5 pr-2 w-44 text-slate-500 whitespace-nowrap">Nama Ibu Kandung</td>
                                        <td class="py-1.5 px-2 w-4 text-center text-slate-400">:</td>
                                        <td class="py-1.5 font-bold text-slate-900">{{ siswa.nama_ibu || '-' }}</td>
                                    </tr>
                                    <tr class="align-top">
                                        <td class="py-1.5 pr-2 text-slate-500 whitespace-nowrap">NIK Ibu</td>
                                        <td class="py-1.5 px-2 text-center text-slate-400">:</td>
                                        <td class="py-1.5 font-mono font-semibold text-slate-900">{{ siswa.nik_ibu || '-' }}</td>
                                    </tr>
                                    <tr class="align-top">
                                        <td class="py-1.5 pr-2 text-slate-500 whitespace-nowrap">No. Telepon / WA Ortu</td>
                                        <td class="py-1.5 px-2 text-center text-slate-400">:</td>
                                        <td class="py-1.5 font-mono font-semibold text-slate-900">{{ siswa.telepon_ortu || '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Nilai Preview Dossier -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">Rekapitulasi Nilai Akademik Terakhir</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Penilaian capaian pembelajaran siswa</p>
                    </div>
                    <Link :href="`/rapor/${siswa.id}`" class="text-xs text-primary-700 font-semibold hover:text-primary-900 hover:underline">
                        Lihat Lembar Rapor Digital &rarr;
                    </Link>
                </div>

                <div v-if="siswa.nilai && siswa.nilai.length > 0" class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-50 text-slate-600 font-bold uppercase text-[11px] border-b border-slate-200">
                            <tr>
                                <th class="px-5 py-3">Mata Pelajaran</th>
                                <th class="px-4 py-3 text-center">Tugas (30%)</th>
                                <th class="px-4 py-3 text-center">UTS (30%)</th>
                                <th class="px-4 py-3 text-center">UAS (40%)</th>
                                <th class="px-4 py-3 text-center">Nilai Akhir</th>
                                <th class="px-4 py-3 text-center">Predikat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="n in siswa.nilai" :key="n.id" class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-5 py-3 font-semibold text-slate-900">{{ n.mata_pelajaran?.nama_mapel }}</td>
                                <td class="px-4 py-3 text-center font-mono">{{ n.nilai_tugas }}</td>
                                <td class="px-4 py-3 text-center font-mono">{{ n.nilai_uts }}</td>
                                <td class="px-4 py-3 text-center font-mono">{{ n.nilai_uas }}</td>
                                <td class="px-4 py-3 text-center font-bold font-mono" :class="n.nilai_akhir >= 70 ? 'text-emerald-700' : 'text-rose-600'">
                                    {{ n.nilai_akhir }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-800 border border-slate-200">
                                        {{ n.predikat }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="p-8 text-center text-slate-400 text-xs">
                    Belum ada riwayat nilai terdaftar pada periode ini.
                </div>
            </div>
        </div>
    </AppLayout>
</template>
