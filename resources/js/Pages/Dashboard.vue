<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    restricted: Boolean,
    userCounts: { type: Object, default: null },
    totalSiswa: { type: Number, default: 0 },
    totalGuru: { type: Number, default: 0 },
    totalKelas: { type: Number, default: 0 },
    totalMapel: { type: Number, default: 0 },
    tahunAjaranAktif: { type: Object, default: null },
    siswaPerKelas: { type: Array, default: () => [] },
    siswaLaki: { type: Number, default: 0 },
    siswaPerempuan: { type: Number, default: 0 },
    absensiHariIni: { type: Number, default: 0 },
    hadirHariIni: { type: Number, default: 0 },
    statistikAbsensi: { type: Array, default: () => [] },
});
const metrics = computed(() => [
    { label: 'Siswa aktif', value: props.totalSiswa, icon: 'fa-user-graduate', href: '/siswa' },
    { label: 'Guru aktif', value: props.totalGuru, icon: 'fa-chalkboard-user', href: '/guru' },
    { label: 'Kelas', value: props.totalKelas, icon: 'fa-school', href: '/kelas' },
    { label: 'Mata pelajaran kelas', value: props.totalMapel, icon: 'fa-book-open', href: '/kelas' },
]);
const maxClass = computed(() => Math.max(1, ...props.siswaPerKelas.map(k => k.siswa_count)));
const totalGender = computed(() => props.siswaLaki + props.siswaPerempuan);
const malePercent = computed(() => totalGender.value ? props.siswaLaki / totalGender.value * 100 : 0);
const attendanceRate = computed(() => props.absensiHariIni ? Math.round(props.hadirHariIni / props.absensiHariIni * 100) + '%' : 'Belum dicatat');
const statuses = [
    { key: 'hadir', label: 'Hadir', color: 'bg-emerald-500' },
    { key: 'sakit', label: 'Sakit', color: 'bg-amber-400' },
    { key: 'izin', label: 'Izin', color: 'bg-sky-400' },
    { key: 'alpa', label: 'Alpa', color: 'bg-rose-400' },
];
const dailyTotal = row => statuses.reduce((sum, s) => sum + row[s.key], 0);
const maxAttendance = computed(() => Math.max(1, ...props.statistikAbsensi.map(dailyTotal)));
const hasAttendance = computed(() => props.statistikAbsensi.some(row => dailyTotal(row) > 0));
const dateLabel = date => new Date(date + 'T00:00:00').toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
</script>

<template>
    <AppLayout title="Dashboard" pageTitle="Dashboard">
        <p v-if="restricted" class="rounded-xl border border-slate-200 bg-white p-6 text-sm text-slate-600">Gunakan menu untuk membuka halaman yang diizinkan. Hubungi administrator jika Anda memerlukan akses tambahan.</p>
        <div v-else class="space-y-5">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <p class="text-sm text-slate-500">{{ tahunAjaranAktif ? `${tahunAjaranAktif.tahun_ajaran} · Semester ${tahunAjaranAktif.semester}` : 'Belum ada tahun ajaran aktif' }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4" :class="userCounts ? 'xl:grid-cols-5' : 'xl:grid-cols-4'">
                <Link v-for="item in metrics" :key="item.label" :href="item.href" class="bg-white rounded-xl border border-slate-200 p-5 hover:border-primary-200 transition">
                    <div class="flex items-center justify-between gap-2"><span class="text-xs text-slate-500">{{ item.label }}</span><i class="fa-solid text-primary-400" :class="item.icon"></i></div>
                    <p class="text-3xl font-bold text-slate-900 mt-3">{{ item.value }}</p>
                </Link>
                <Link v-if="userCounts" href="/pengguna" class="bg-white rounded-xl border border-slate-200 p-5 hover:border-primary-200 transition">
                    <div class="flex items-center justify-between gap-2"><span class="text-xs text-slate-500">Pengguna</span><i class="fa-solid fa-users-gear text-primary-400"></i></div>
                    <p class="text-3xl font-bold text-slate-900 mt-3">{{ userCounts.admin + userCounts.operator }}</p>
                    <p class="mt-1 text-xs text-slate-600">{{ userCounts.admin }} admin · {{ userCounts.operator }} operator</p>
                </Link>
            </div>
            <div class="grid lg:grid-cols-3 gap-5">
                <section class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-5">
                    <div class="flex items-center justify-between mb-5"><h2 class="text-sm font-semibold">Jumlah siswa per kelas</h2><span class="text-xs text-slate-400">Siswa aktif</span></div>
                    <div v-if="siswaPerKelas.length" class="space-y-4">
                        <div v-for="kelas in siswaPerKelas" :key="kelas.id" class="flex items-center gap-3 text-xs">
                            <Link :href="`/kelas/${kelas.id}`" class="w-24 shrink-0 text-slate-600 hover:text-primary-700">{{ kelas.nama_kelas }}</Link>
                            <div class="flex-1 bg-slate-100 rounded-full h-3 overflow-hidden" role="img" :aria-label="`${kelas.nama_kelas}: ${kelas.siswa_count} siswa`"><div class="h-full bg-primary-500 rounded-full" :style="{width: `${kelas.siswa_count / maxClass * 100}%`}"></div></div>
                            <span class="w-6 text-right font-semibold tabular-nums">{{ kelas.siswa_count }}</span>
                        </div>
                    </div>
                    <p v-else class="py-10 text-center text-sm text-slate-400">Belum ada data kelas.</p>
                </section>
                <section class="bg-white rounded-xl border border-slate-200 p-5">
                    <h2 class="text-sm font-semibold">Komposisi siswa</h2>
                    <div class="relative w-36 h-36 mx-auto my-5 rounded-full flex items-center justify-center" role="img" :aria-label="`${siswaLaki} siswa laki-laki dan ${siswaPerempuan} siswa perempuan`" :style="{background: totalGender ? `conic-gradient(var(--color-primary-500) 0% ${malePercent}%, var(--color-primary-200) ${malePercent}% 100%)` : '#e2e8f0'}">
                        <div class="rounded-full bg-white w-28 h-28 flex flex-col items-center justify-center"><span class="text-2xl font-bold">{{ totalGender }}</span><span class="text-[11px] text-slate-400">Siswa aktif</span></div>
                    </div>
                    <div class="flex justify-between text-xs text-slate-500 gap-3"><span><i class="inline-block w-2 h-2 bg-primary-500 rounded-full mr-1"></i>Laki-laki <strong class="text-slate-700">{{ siswaLaki }}</strong></span><span><i class="inline-block w-2 h-2 bg-primary-200 rounded-full mr-1"></i>Perempuan <strong class="text-slate-700">{{ siswaPerempuan }}</strong></span></div>
                </section>
            </div>
            <section class="bg-white rounded-xl border border-slate-200 p-5">
                <div class="flex flex-wrap justify-between gap-3 items-start"><div><h2 class="text-sm font-semibold">Presensi 7 hari terakhir</h2><p class="text-xs text-slate-400 mt-1">Jumlah siswa berdasarkan presensi yang sudah dicatat.</p></div><p class="text-xs text-slate-500">Kehadiran hari ini: <strong class="text-slate-800">{{ attendanceRate }}</strong></p></div>
                <div v-if="hasAttendance" class="mt-6 grid grid-cols-7 gap-2 sm:gap-6">
                    <div v-for="row in statistikAbsensi" :key="row.tanggal" class="text-center min-w-0" :title="`${dateLabel(row.tanggal)}: ${statuses.map(s => `${s.label} ${row[s.key]}`).join(', ')}`">
                        <div class="h-36 flex flex-col justify-end items-center border-b border-slate-100"><span class="text-[10px] text-slate-400 mb-1">{{ dailyTotal(row) || '—' }}</span><div class="w-full max-w-14 flex flex-col-reverse rounded-t-md overflow-hidden" role="img" :aria-label="`${dateLabel(row.tanggal)}: ${statuses.map(s => `${s.label} ${row[s.key]}`).join(', ')}`" :style="{height: `${dailyTotal(row) / maxAttendance * 110}px`}"><div v-for="s in statuses" :key="s.key" :class="s.color" :style="{height: `${dailyTotal(row) ? row[s.key] / dailyTotal(row) * 100 : 0}%`}"></div></div></div>
                        <span class="block text-[10px] text-slate-500 mt-2">{{ dateLabel(row.tanggal) }}</span>
                    </div>
                </div>
                <p v-else class="py-10 text-center text-sm text-slate-400">Belum ada presensi dalam 7 hari terakhir.</p>
                <div class="flex flex-wrap gap-4 mt-4"><span v-for="s in statuses" :key="s.key" class="text-xs text-slate-500 flex items-center gap-1.5"><i class="w-2 h-2 rounded-full" :class="s.color"></i>{{ s.label }}</span></div>
            </section>
        </div>
    </AppLayout>
</template>
