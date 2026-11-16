<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
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
    tanggal: {
        type: String,
        default: () => new Date().toISOString().slice(0, 10),
    },
    absensi: {
        type: Object,
        default: () => ({}),
    },
    siswaList: {
        type: Array,
        default: () => [],
    },
});

const filterTahunAjaranId = ref(props.tahun_ajaran_id || '');
const filterKelasId = ref(props.kelas_id || '');
const filterTanggal = ref(props.tanggal || new Date().toISOString().slice(0, 10));

const filter = () => {
    if (!filterKelasId.value) return;
    router.get('/absensi', {
        tahun_ajaran_id: filterTahunAjaranId.value,
        kelas_id: filterKelasId.value,
        tanggal: filterTanggal.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

watch([filterTahunAjaranId, filterKelasId, filterTanggal], () => {
    filter();
});

const buildInitialForm = () => {
    const absensiMap = {};
    const keteranganMap = {};
    (props.siswaList || []).forEach((s) => {
        const existing = props.absensi?.[s.id];
        absensiMap[s.id] = existing?.status || 'Hadir';
        keteranganMap[s.id] = existing?.keterangan || '';
    });
    return {
        tahun_ajaran_id: props.tahun_ajaran_id,
        kelas_id: props.kelas_id,
        tanggal: props.tanggal,
        absensi: absensiMap,
        keterangan: keteranganMap,
    };
};

const form = useForm(buildInitialForm());

watch(() => props.siswaList, () => {
    const init = buildInitialForm();
    form.tahun_ajaran_id = init.tahun_ajaran_id;
    form.kelas_id = init.kelas_id;
    form.tanggal = init.tanggal;
    form.absensi = init.absensi;
    form.keterangan = init.keterangan;
}, { deep: true });

const markAllHadir = () => {
    (props.siswaList || []).forEach((s) => {
        form.absensi[s.id] = 'Hadir';
    });
};

const submit = () => {
    form.post('/absensi', {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout title="Presensi" pageTitle="Presensi">
        <div class="space-y-4">
            <!-- Filter Bar -->
            <div class="bg-white rounded-xl p-3 border border-slate-200 shadow-2xs">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2.5">
                    <select v-model="filterTahunAjaranId" aria-label="Tahun ajaran presensi" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs"><option value="">Pilih tahun ajaran</option><option v-for="t in tahunAjaranList" :key="t.id" :value="t.id">{{ t.tahun_ajaran }} ({{ t.semester }})</option></select>
                    <select v-model="filterKelasId" class="px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs text-slate-700 focus:bg-white focus:border-primary-600 outline-none transition cursor-pointer">
                        <option value="">Pilih Kelas</option>
                        <option v-for="k in kelasList" :key="k.id" :value="k.id">{{ k.nama_kelas }}</option>
                    </select>

                    <input v-model="filterTanggal" type="date" class="px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs text-slate-700 focus:bg-white focus:border-primary-600 outline-none transition font-mono cursor-pointer" />

                    <Link href="/absensi/rekap" class="sm:ml-auto px-3 py-1.5 rounded-lg bg-white hover:bg-slate-50 text-slate-700 text-xs font-medium border border-slate-300 transition inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-chart-column text-slate-400 text-[11px]"></i>
                        <span>Rekap</span>
                    </Link>
                </div>
            </div>

            <!-- Attendance Matrix Form -->
            <div v-if="kelas_id && siswaList.length > 0" class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
                <div class="px-4 py-2.5 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                    <span class="text-xs font-semibold text-slate-800">{{ siswaList.length }} Siswa</span>
                    <button type="button" @click="markAllHadir" class="px-2.5 py-1 rounded-md bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 text-[11px] font-medium transition cursor-pointer flex items-center gap-1.5">
                        <i class="fa-solid fa-check text-emerald-600"></i>
                        <span>Semua Hadir</span>
                    </button>
                </div>

                <form @submit.prevent="submit">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead class="bg-slate-50 text-slate-600 text-[11px] font-semibold border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-2.5 w-10 text-center">No</th>
                                    <th class="px-4 py-2.5">Siswa</th>
                                    <th class="px-4 py-2.5 text-center">Status Kehadiran</th>
                                    <th class="px-4 py-2.5">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr v-for="(s, index) in siswaList" :key="s.id" class="hover:bg-slate-50/60 transition-colors">
                                    <td class="px-4 py-2.5 text-center font-mono text-slate-400">{{ index + 1 }}</td>
                                    <td class="px-4 py-2.5">
                                        <p class="font-semibold text-slate-900">{{ s.nama }}</p>
                                        <p class="text-[11px] text-slate-400 font-mono">NIS: {{ s.nis }}</p>
                                    </td>
                                    <td class="px-4 py-2.5 text-center">
                                        <div class="inline-flex items-center gap-1 bg-slate-100 p-0.5 rounded-lg border border-slate-200">
                                            <label
                                                v-for="st in ['Hadir', 'Sakit', 'Izin', 'Alpa']"
                                                :key="st"
                                                class="px-2 py-0.5 rounded text-[11px] font-medium cursor-pointer transition select-none"
                                                :class="form.absensi[s.id] === st
                                                    ? (st === 'Hadir' ? 'bg-emerald-600 text-white' :
                                                       st === 'Sakit' ? 'bg-blue-600 text-white' :
                                                       st === 'Izin' ? 'bg-amber-600 text-white' : 'bg-rose-600 text-white')
                                                    : 'text-slate-600 hover:text-slate-900'"
                                            >
                                                <input type="radio" :name="`status_${s.id}`" :value="st" v-model="form.absensi[s.id]" class="sr-only" />
                                                <span>{{ st }}</span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2.5">
                                        <input
                                            v-model="form.keterangan[s.id]"
                                            type="text"
                                            placeholder="Keterangan..."
                                            class="w-full px-2.5 py-1 bg-slate-50 border border-slate-200 rounded text-xs focus:bg-white focus:border-primary-600 outline-none"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="px-4 py-3 bg-slate-50 border-t border-slate-200 flex justify-end">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-1.5 rounded-lg bg-primary-700 hover:bg-primary-800 text-white text-xs font-semibold shadow-2xs transition disabled:opacity-50 cursor-pointer"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Presensi' }}
                        </button>
                    </div>
                </form>
            </div>

            <div v-else-if="kelas_id" class="bg-white rounded-xl p-8 border border-slate-200 text-center text-xs text-slate-400">
                Tidak ada siswa di kelas ini.
            </div>

            <div v-else class="bg-white rounded-xl p-8 border border-slate-200 text-center text-xs text-slate-400">
                Pilih kelas untuk mencatat presensi.
            </div>
        </div>
    </AppLayout>
</template>
