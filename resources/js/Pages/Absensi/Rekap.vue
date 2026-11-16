<script setup>
import { computed, ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    jumlahHari: { type: Number, default: 31 },
    tahunAjaranList: { type: Array, default: () => [] },
    tahun_ajaran_id: { type: [String, Number], default: '' },
    kelasList: {
        type: Array,
        default: () => [],
    },
    kelas_id: {
        type: [String, Number],
        default: '',
    },
    bulan: {
        type: [String, Number],
        default: () => new Date().getMonth() + 1,
    },
    tahun: {
        type: [String, Number],
        default: () => new Date().getFullYear(),
    },
    rekap: {
        type: Array,
        default: () => [],
    },
});

const filterTahunAjaranId = ref(props.tahun_ajaran_id || '');
const filterKelasId = ref(props.kelas_id || '');
const filterBulan = ref(String(Number(props.bulan || (new Date().getMonth() + 1))));
const filterTahun = ref(props.tahun || new Date().getFullYear());

const monthNames = [
    { value: '1', label: 'Januari' },
    { value: '2', label: 'Februari' },
    { value: '3', label: 'Maret' },
    { value: '4', label: 'April' },
    { value: '5', label: 'Mei' },
    { value: '6', label: 'Juni' },
    { value: '7', label: 'Juli' },
    { value: '8', label: 'Agustus' },
    { value: '9', label: 'September' },
    { value: '10', label: 'Oktober' },
    { value: '11', label: 'November' },
    { value: '12', label: 'Desember' },
];

const days = computed(() => Array.from({ length: props.jumlahHari }, (_, i) => i + 1));
const className = computed(() => props.kelasList.find(k => Number(k.id) === Number(props.kelas_id))?.nama_kelas || '-');
const period = computed(() => props.tahunAjaranList.find(t => Number(t.id) === Number(props.tahun_ajaran_id)));
const statusCode = status => ({ Hadir: 'H', Sakit: 'S', Izin: 'I', Alpa: 'A' }[status] || '-');
const printReport = () => window.print();

const filter = () => {
    if (!filterKelasId.value) return;
    router.get('/absensi/rekap', {
        tahun_ajaran_id: filterTahunAjaranId.value,
        kelas_id: filterKelasId.value,
        bulan: filterBulan.value,
        tahun: filterTahun.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

watch([filterTahunAjaranId, filterKelasId, filterBulan, filterTahun], () => {
    filter();
});
</script>

<template>
    <AppLayout title="Rekap Presensi" pageTitle="Rekap Presensi">
        <div class="space-y-4 print:hidden">
            <!-- Filter Bar -->
            <div class="bg-white rounded-xl p-3 border border-slate-200 shadow-2xs print:hidden">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2.5">
                    <select v-model="filterTahunAjaranId" aria-label="Tahun ajaran presensi" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs"><option value="">Pilih tahun ajaran</option><option v-for="t in tahunAjaranList" :key="t.id" :value="t.id">{{ t.tahun_ajaran }} ({{ t.semester }})</option></select>
                    <select v-model="filterKelasId" aria-label="Kelas rekap" class="px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs text-slate-700 focus:bg-white focus:border-primary-600 outline-none transition cursor-pointer">
                        <option value="">Pilih Kelas</option>
                        <option v-for="k in kelasList" :key="k.id" :value="k.id">{{ k.nama_kelas }}</option>
                    </select>

                    <select v-model="filterBulan" aria-label="Bulan rekap" class="px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs text-slate-700 focus:bg-white focus:border-primary-600 outline-none transition cursor-pointer">
                        <option v-for="m in monthNames" :key="m.value" :value="m.value">{{ m.label }}</option>
                    </select>

                    <input v-model="filterTahun" aria-label="Tahun rekap" type="number" class="w-24 px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs text-slate-700 focus:bg-white focus:border-primary-600 outline-none transition font-mono" />

                    <div class="sm:ml-auto flex items-center gap-2">
                        <button type="button" @click="printReport" :disabled="!kelas_id || !rekap.length" class="px-3 py-1.5 rounded-lg bg-white hover:bg-slate-50 text-slate-700 text-xs font-medium border border-slate-300 transition cursor-pointer inline-flex items-center gap-1.5 disabled:opacity-40 disabled:cursor-not-allowed">
                            <i class="fa-solid fa-print text-slate-400 text-[11px]"></i>
                            <span>Cetak</span>
                        </button>
                        <Link href="/absensi" class="px-3 py-1.5 rounded-lg bg-white hover:bg-slate-50 text-slate-700 text-xs font-medium border border-slate-300 transition inline-flex items-center gap-1.5">
                            <span>Input Harian</span>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div v-if="kelas_id && rekap.length > 0" class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
                <div class="px-4 py-2.5 border-b border-slate-200 bg-slate-50">
                    <span class="text-xs font-semibold text-slate-800">
                        {{ monthNames.find(m => m.value == bulan)?.label }} {{ tahun }} &bull; {{ rekap.length }} Siswa
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-50 text-slate-600 text-[11px] font-semibold border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-2.5 w-10 text-center">No</th>
                                <th class="px-4 py-2.5">Siswa</th>
                                <th class="px-3 py-2.5 text-center text-emerald-700">Hadir</th>
                                <th class="px-3 py-2.5 text-center text-blue-700">Sakit</th>
                                <th class="px-3 py-2.5 text-center text-amber-700">Izin</th>
                                <th class="px-3 py-2.5 text-center text-rose-700">Alpa</th>
                                <th class="px-3 py-2.5 text-center font-bold">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="(row, index) in rekap" :key="row.siswa.id" class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-4 py-2.5 text-center font-mono text-slate-400">{{ index + 1 }}</td>
                                <td class="px-4 py-2.5">
                                    <p class="font-semibold text-slate-900">{{ row.siswa.nama }}</p>
                                    <p class="text-[11px] text-slate-400 font-mono">NIS: {{ row.siswa.nis }}</p>
                                </td>
                                <td class="px-3 py-2.5 text-center font-mono text-emerald-700 font-medium">
                                    {{ row.hadir }}
                                </td>
                                <td class="px-3 py-2.5 text-center font-mono text-blue-700 font-medium">
                                    {{ row.sakit }}
                                </td>
                                <td class="px-3 py-2.5 text-center font-mono text-amber-700 font-medium">
                                    {{ row.izin }}
                                </td>
                                <td class="px-3 py-2.5 text-center font-mono text-rose-700 font-medium">
                                    {{ row.alpa }}
                                </td>
                                <td class="px-3 py-2.5 text-center font-mono font-bold text-slate-900">
                                    {{ row.total }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-else class="bg-white rounded-xl p-8 border border-slate-200 text-center text-xs text-slate-400">
                {{ kelas_id ? 'Belum ada anggota rombel pada kelas dan tahun ajaran yang dipilih.' : 'Pilih kelas dan bulan untuk melihat rekapitulasi.' }}
            </div>
        </div>
        <section v-if="kelas_id && rekap.length" class="attendance-print" aria-label="Cetak rekap absensi bulanan">
            <header class="attendance-print-heading">
                <h1>Rekap Absensi</h1>
                <p>Kelas: {{ className }}</p>
                <p>Bulan: {{ monthNames.find(m => Number(m.value) === Number(bulan))?.label }} {{ tahun }}<span v-if="period"> · Tahun ajaran {{ period.tahun_ajaran }} ({{ period.semester }})</span></p>
            </header>
            <table class="attendance-print-table">
                <colgroup><col class="attendance-number" /><col class="attendance-name" /><col v-for="day in days" :key="day" /><col v-for="status in ['H', 'S', 'I', 'A']" :key="status" class="attendance-summary" /></colgroup>
                <thead>
                    <tr><th rowspan="2" scope="col">No</th><th rowspan="2" scope="col">Nama Siswa</th><th :colspan="days.length" scope="colgroup">Tanggal</th><th colspan="4" scope="colgroup">Jumlah</th></tr>
                    <tr><th v-for="day in days" :key="day" scope="col">{{ day }}</th><th scope="col">H</th><th scope="col">S</th><th scope="col">I</th><th scope="col">A</th></tr>
                </thead>
                <tbody>
                    <tr v-for="(row, index) in rekap" :key="row.siswa.id">
                        <td>{{ index + 1 }}</td><td class="attendance-student-name">{{ row.siswa.nama }}</td>
                        <td v-for="day in days" :key="day">{{ statusCode(row.harian?.[day]) }}</td>
                        <td>{{ row.hadir }}</td><td>{{ row.sakit }}</td><td>{{ row.izin }}</td><td>{{ row.alpa }}</td>
                    </tr>
                </tbody>
            </table>
            <p class="attendance-print-legend">Keterangan: H = Hadir · S = Sakit · I = Izin · A = Alpa · - = Belum diisi</p>
        </section>
    </AppLayout>
</template>

<style>
.attendance-print { display: none; }
@page attendance-monthly {
    size: A4 landscape;
    margin: 10mm;
}
@media print {
    /* Keep the entire print document on the same named page, including page one. */
    html:has(.attendance-print),
    body:has(.attendance-print),
    .learning-app:has(.attendance-print),
    .app-workspace:has(.attendance-print),
    .learning-content:has(.attendance-print) { page: attendance-monthly; }
    body:has(.attendance-print) { font-family: Arial, sans-serif !important; margin: 0 !important; }
    .learning-app:has(.attendance-print),
    .app-workspace:has(.attendance-print),
    .learning-content:has(.attendance-print) { display: block !important; min-height: 0 !important; margin: 0 !important; }
    .learning-content:has(.attendance-print) > :not(.attendance-print) { display: none !important; }
    .attendance-print { display: block; page: attendance-monthly; color: #000; width: 100%; }
    .attendance-print-heading { margin: 0 0 5mm; text-align: center; }
    .attendance-print-heading h1 { font-size: 16pt; font-weight: 700; margin: 0 0 2mm; }
    .attendance-print-heading p { font-size: 10pt; margin: 1mm 0; }
    .attendance-print-table { width: 100%; border-collapse: collapse; table-layout: fixed; font-size: 8pt; line-height: 1.2; }
    .attendance-print-table th, .attendance-print-table td { border: 0.25mm solid #000; padding: 1mm 0.3mm; text-align: center; vertical-align: middle; color: #000; }
    .attendance-print-table th { font-weight: 700; background: #fff; }
    .attendance-print-table tbody tr { break-inside: avoid; }
    .attendance-print-table thead { display: table-header-group; }
    .attendance-print-table .attendance-number { width: 8mm; }
    .attendance-print-table .attendance-name { width: 50mm; }
    .attendance-print-table .attendance-summary { width: 6mm; }
    .attendance-print-table .attendance-student-name { text-align: left; padding-left: 1.5mm; overflow-wrap: anywhere; }
    .attendance-print-legend { font-size: 8pt; margin-top: 3mm; }
}
</style>
