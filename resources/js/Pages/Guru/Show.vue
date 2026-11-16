<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    guru: {
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
    <AppLayout title="Detail Guru" pageTitle="Detail Guru">
        <div class="max-w-4xl mx-auto space-y-5">
            <!-- Breadcrumb Navigation Bar -->
            <div class="flex items-center justify-between">
                <Link href="/guru" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-primary-700 font-medium transition">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Kembali ke Direktori Guru</span>
                </Link>
                <div class="flex items-center gap-2">
                    <Link
                        :href="`/guru/${guru.id}/edit`"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-primary-700 hover:bg-primary-800 text-white text-xs font-semibold shadow-2xs transition"
                    >
                        <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                        <span>Edit Profil Pendidik</span>
                    </Link>
                </div>
            </div>

            <!-- Profile Hero Dossier Card -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
                <!-- Header Dossier Summary -->
                <div class="p-6 border-b border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row items-center sm:items-start gap-5 text-center sm:text-left">
                    <div class="w-20 h-20 rounded-xl bg-white border border-slate-200 text-slate-700 flex items-center justify-center font-bold text-2xl shrink-0 overflow-hidden shadow-2xs">
                        <img v-if="guru.foto" :src="`/storage/${guru.foto}`" class="w-full h-full object-cover" />
                        <span v-else class="text-slate-400">{{ (guru.nama || 'G').charAt(0).toUpperCase() }}</span>
                    </div>

                    <div class="flex-1 space-y-1.5">
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5">
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">{{ guru.nama || 'Belum diisi' }}</h2>
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider"
                                :class="guru.status_aktif ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200'"
                            >
                                <span class="w-1.5 h-1.5 rounded-full" :class="guru.status_aktif ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                                {{ guru.status_aktif ? 'Aktif Bertugas' : 'Nonaktif' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-center sm:justify-start gap-3 text-xs text-slate-500 flex-wrap">
                            <span class="font-mono">{{ guru.nip ? `NIP: ${guru.nip}` : 'Pendidik Non-PNS' }}</span>
                            <span class="text-slate-300">&bull;</span>
                            <span>Jabatan: <strong class="text-slate-800 font-semibold">{{ guru.jabatan || 'Guru Kelas' }}</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Aligned Details Sections -->
                <div class="p-6 sm:p-7 grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Column 1: Biodata Kepegawaian -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-primary-700 mb-3.5 pb-2 border-b border-slate-200 flex items-center gap-2">
                            <i class="fa-solid fa-id-badge text-xs"></i>
                            <span>Identitas Kepegawaian</span>
                        </h4>

                        <table class="w-full text-xs border-collapse">
                            <tbody>
                                <tr class="align-top">
                                    <td class="py-1.5 pr-2 w-36 sm:w-40 text-slate-500 whitespace-nowrap">Nomor Induk Pegawai</td>
                                    <td class="py-1.5 px-2 w-4 text-center text-slate-400">:</td>
                                    <td class="py-1.5 font-mono font-semibold text-slate-900">{{ guru.nip || '-' }}</td>
                                </tr>
                                <tr class="align-top">
                                    <td class="py-1.5 pr-2 text-slate-500 whitespace-nowrap">Nama Lengkap & Gelar</td>
                                    <td class="py-1.5 px-2 text-center text-slate-400">:</td>
                                    <td class="py-1.5 font-bold text-slate-900">{{ guru.nama || 'Belum diisi' }}</td>
                                </tr>
                                <tr class="align-top">
                                    <td class="py-1.5 pr-2 text-slate-500 whitespace-nowrap">Jenis Kelamin</td>
                                    <td class="py-1.5 px-2 text-center text-slate-400">:</td>
                                    <td class="py-1.5 font-semibold text-slate-900">
                                        {{ guru.jenis_kelamin === 'L' ? 'Laki-laki' : guru.jenis_kelamin === 'P' ? 'Perempuan' : 'Belum diisi' }}
                                    </td>
                                </tr>
                                <tr class="align-top">
                                    <td class="py-1.5 pr-2 text-slate-500 whitespace-nowrap">Tempat, Tanggal Lahir</td>
                                    <td class="py-1.5 px-2 text-center text-slate-400">:</td>
                                    <td class="py-1.5 font-semibold text-slate-900">
                                        {{ guru.tempat_lahir || '-' }}, {{ formatTanggal(guru.tanggal_lahir) }}
                                    </td>
                                </tr>
                                <tr class="align-top">
                                    <td class="py-1.5 pr-2 text-slate-500 whitespace-nowrap">Pendidikan Terakhir</td>
                                    <td class="py-1.5 px-2 text-center text-slate-400">:</td>
                                    <td class="py-1.5 font-semibold text-slate-900">{{ guru.pendidikan_terakhir || '-' }}</td>
                                </tr>
                                <tr class="align-top">
                                    <td class="py-1.5 pr-2 text-slate-500 whitespace-nowrap">Status Penugasan</td>
                                    <td class="py-1.5 px-2 text-center text-slate-400">:</td>
                                    <td class="py-1.5 font-semibold">
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[11px] font-bold uppercase" :class="guru.status_aktif ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-700'">
                                            <span class="w-1.5 h-1.5 rounded-full" :class="guru.status_aktif ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                                            {{ guru.status_aktif ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Column 2: Kontak & Domisili -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-primary-700 mb-3.5 pb-2 border-b border-slate-200 flex items-center gap-2">
                            <i class="fa-solid fa-address-book text-xs"></i>
                            <span>Kontak & Domisili</span>
                        </h4>

                        <table class="w-full text-xs border-collapse">
                            <tbody>
                                <tr class="align-top">
                                    <td class="py-1.5 pr-2 w-36 sm:w-40 text-slate-500 whitespace-nowrap">No. Telepon / WhatsApp</td>
                                    <td class="py-1.5 px-2 w-4 text-center text-slate-400">:</td>
                                    <td class="py-1.5 font-mono font-semibold text-slate-900">
                                        <a v-if="guru.telepon" :href="`tel:${guru.telepon}`" class="text-primary-700 hover:underline">
                                            {{ guru.telepon }}
                                        </a>
                                        <span v-else>-</span>
                                    </td>
                                </tr>
                                <tr class="align-top">
                                    <td class="py-1.5 pr-2 text-slate-500 whitespace-nowrap">Alamat Surel (Email)</td>
                                    <td class="py-1.5 px-2 text-center text-slate-400">:</td>
                                    <td class="py-1.5 font-semibold text-slate-900">
                                        <a v-if="guru.email" :href="`mailto:${guru.email}`" class="text-primary-700 hover:underline">
                                            {{ guru.email }}
                                        </a>
                                        <span v-else>-</span>
                                    </td>
                                </tr>
                                <tr class="align-top">
                                    <td class="py-1.5 pr-2 text-slate-500 whitespace-nowrap">Alamat Domisili</td>
                                    <td class="py-1.5 px-2 text-center text-slate-400">:</td>
                                    <td class="py-1.5 font-semibold text-slate-900 leading-relaxed">{{ guru.alamat || '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Assigned Classes & Subjects Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Wali Kelas Dossier -->
                <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-2xs">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 pb-2 border-b border-slate-200 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-door-open text-primary-700"></i>
                        <span>Penugasan Wali Kelas</span>
                    </h3>
                    <div v-if="guru.kelas && guru.kelas.length > 0" class="space-y-2">
                        <div v-for="k in guru.kelas" :key="k.id" class="p-3 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-800">{{ k.nama_kelas }}</span>
                            <span class="text-slate-500 font-medium">Tingkat {{ k.tingkat }}</span>
                        </div>
                    </div>
                    <p v-else class="text-xs text-slate-400 italic py-2">
                        Tidak sedang bertugas sebagai wali kelas di tahun ajaran ini.
                    </p>
                </div>

                <!-- Mata Pelajaran yang Diampu -->
                <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-2xs">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 pb-2 border-b border-slate-200 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-book-bookmark text-primary-700"></i>
                        <span>Mata Pelajaran yang Diampu</span>
                    </h3>
                    <div v-if="guru.mata_pelajaran && guru.mata_pelajaran.length > 0" class="space-y-2">
                        <div v-for="m in guru.mata_pelajaran" :key="m.id" class="p-3 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-800">{{ m.nama_mapel }}</span>
                            <span class="text-slate-500 font-mono text-[11px] font-semibold">{{ m.kode_mapel }}</span>
                        </div>
                    </div>
                    <p v-else class="text-xs text-slate-400 italic py-2">
                        Belum ada penugasan mata pelajaran spesifik.
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
