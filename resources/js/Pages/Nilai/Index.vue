<script setup>
import { ref, watch } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import EkstrakurikulerForm from '@/Components/EkstrakurikulerForm.vue';

const props = defineProps({
    aturanPredikat: { type: Object, default: () => ({ A: 90, B: 80, C: 70, D: 60, E: 0 }) },
    mode: { type: String, default: 'pelajaran' },
    ekstrakurikulerList: { type: [Object, Array], default: () => ({}) },
    kelasList: {
        type: Array,
        default: () => [],
    },
    mapelList: {
        type: Array,
        default: () => [],
    },
    tahunAjaranList: {
        type: Array,
        default: () => [],
    },
    kelas_id: {
        type: [String, Number],
        default: '',
    },
    mapel_id: {
        type: [String, Number],
        default: '',
    },
    tahun_ajaran_id: {
        type: [String, Number],
        default: '',
    },
    nilaiList: {
        type: Object,
        default: () => ({}),
    },
    siswaList: {
        type: Array,
        default: () => [],
    },
});

const activeMode = ref(props.mode);
const filterKelasId = ref(props.kelas_id || '');
const filterMapelId = ref(props.mapel_id || '');
const filterTahunAjaranId = ref(props.tahun_ajaran_id || '');
watch(() => props.mapel_id, value => { filterMapelId.value = value || ''; });

const filter = () => {
    router.get('/nilai', {
        mode: activeMode.value,
        kelas_id: filterKelasId.value,
        mapel_id: filterMapelId.value,
        tahun_ajaran_id: filterTahunAjaranId.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

watch([activeMode, filterKelasId, filterMapelId, filterTahunAjaranId], () => {
    filter();
});

const buildInitialForm = () => {
    const nilaiMap = {};
    (props.siswaList || []).forEach((s) => {
        const existing = props.nilaiList?.[s.id];
        nilaiMap[s.id] = {
            tugas: existing?.nilai_tugas ?? '',
            uts: existing?.nilai_uts ?? '',
            uas: existing?.nilai_uas ?? '',
            capaian_kompetensi: existing?.capaian_kompetensi ?? '',
        };
    });
    return {
        kelas_id: props.kelas_id,
        mata_pelajaran_id: props.mapel_id,
        tahun_ajaran_id: props.tahun_ajaran_id,
        nilai: nilaiMap,
    };
};

const form = useForm(buildInitialForm());

watch(() => [props.kelas_id, props.mapel_id, props.tahun_ajaran_id], () => {
    const init = buildInitialForm();
    form.kelas_id = init.kelas_id;
    form.mata_pelajaran_id = init.mata_pelajaran_id;
    form.tahun_ajaran_id = init.tahun_ajaran_id;
    form.nilai = init.nilai;
    form.clearErrors();
});

const calculatePreview = (studentId) => {
    const row = form.nilai[studentId];
    if (!row) return { akhir: '-', predikat: '-' };
    const t = parseFloat(row.tugas);
    const u = parseFloat(row.uts);
    const a = parseFloat(row.uas);

    if (isNaN(t) && isNaN(u) && isNaN(a)) {
        return { akhir: '-', predikat: '-' };
    }

    const tVal = isNaN(t) ? 0 : t;
    const uVal = isNaN(u) ? 0 : u;
    const aVal = isNaN(a) ? 0 : a;

    const akhir = Math.round(((tVal * 0.3) + (uVal * 0.3) + (aVal * 0.4)) * 100) / 100;
    const predikat = Object.entries(props.aturanPredikat).find(([, minimum]) => akhir >= Number(minimum))?.[0] || 'E';

    return { akhir, predikat };
};

const submit = () => {
    form.post('/nilai', {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout title="Nilai" pageTitle="Nilai">
        <div class="space-y-4">
            <div role="tablist" aria-label="Jenis nilai" class="flex gap-1 p-1 bg-white border border-slate-200 rounded-xl w-fit">
                <button id="tab-pelajaran" role="tab" :aria-selected="activeMode === 'pelajaran'" aria-controls="panel-pelajaran" @click="activeMode = 'pelajaran'" class="px-4 py-2 rounded-lg text-sm font-medium" :class="activeMode === 'pelajaran' ? 'bg-primary-50 text-primary-700' : 'text-slate-500 hover:bg-slate-50'">Nilai Pelajaran</button>
                <button id="tab-ekstrakurikuler" role="tab" :aria-selected="activeMode === 'ekstrakurikuler'" aria-controls="panel-ekstrakurikuler" @click="activeMode = 'ekstrakurikuler'" class="px-4 py-2 rounded-lg text-sm font-medium" :class="activeMode === 'ekstrakurikuler' ? 'bg-primary-50 text-primary-700' : 'text-slate-500 hover:bg-slate-50'">Ekstrakurikuler</button>
            </div>
            <!-- Filter Bar -->
            <div class="bg-white rounded-xl p-3 border border-slate-200 shadow-2xs">
                <div class="flex flex-wrap items-center gap-2.5">
                    <select aria-label="Kelas" v-model="filterKelasId" class="px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs text-slate-700 focus:bg-white focus:border-primary-600 outline-none transition cursor-pointer">
                        <option value="">Pilih Kelas</option>
                        <option v-for="k in kelasList" :key="k.id" :value="k.id">{{ k.nama_kelas }}</option>
                    </select>

                    <select v-if="activeMode === 'pelajaran'" aria-label="Mata pelajaran" v-model="filterMapelId" class="px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs text-slate-700 focus:bg-white focus:border-primary-600 outline-none transition cursor-pointer">
                        <option value="">Pilih Mapel</option>
                        <option v-for="m in mapelList" :key="m.id" :value="m.id">{{ m.nama_mapel }}</option>
                    </select>

                    <select aria-label="Tahun ajaran" v-model="filterTahunAjaranId" class="px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs text-slate-700 focus:bg-white focus:border-primary-600 outline-none transition cursor-pointer">
                        <option value="">Pilih Tahun Ajaran</option>
                        <option v-for="t in tahunAjaranList" :key="t.id" :value="t.id">{{ t.tahun_ajaran }} (Sem {{ t.semester }})</option>
                    </select>
                    <template v-if="filterKelasId && activeMode === 'pelajaran'">
                        <Link v-if="filterTahunAjaranId" :href="`/nilai/predikat?kelas_id=${filterKelasId}&tahun_ajaran_id=${filterTahunAjaranId}${filterMapelId ? '&mapel_id=' + filterMapelId : ''}`" class="sm:ml-auto inline-flex items-center gap-2 border border-primary-200 text-primary-700 px-3 py-2 rounded-lg text-xs font-semibold hover:bg-primary-50"><i aria-hidden="true" class="fa-solid fa-sliders"></i>Atur Predikat</Link>
                        <button v-else type="button" disabled title="Pilih tahun ajaran terlebih dahulu" class="sm:ml-auto px-3 py-2 border border-slate-200 text-slate-400 rounded-lg text-xs">Atur Predikat</button>
                    </template>
                </div>
            </div>

            <section v-if="activeMode === 'ekstrakurikuler'" id="panel-ekstrakurikuler" role="tabpanel" aria-labelledby="tab-ekstrakurikuler">
                <EkstrakurikulerForm v-if="kelas_id && tahun_ajaran_id && siswaList.length" :siswa-list="siswaList" :records="ekstrakurikulerList" :kelas-id="kelas_id" :tahun-ajaran-id="tahun_ajaran_id" />
                <div v-else class="bg-white rounded-xl p-8 border border-slate-200 text-center text-sm text-slate-500">{{ kelas_id && tahun_ajaran_id ? 'Tidak ada siswa di kelas ini.' : 'Pilih kelas dan tahun ajaran untuk mengisi ekstrakurikuler.' }}</div>
            </section>
            <section v-else id="panel-pelajaran" role="tabpanel" aria-labelledby="tab-pelajaran">
            <!-- Grade Matrix Table Form -->
            <p v-if="kelas_id && mapel_id && tahun_ajaran_id && siswaList.length" class="text-xs text-slate-500 mb-3">Capaian kompetensi akan ditampilkan pada bagian A rapor untuk mata pelajaran dan semester ini.</p>
            <div v-if="kelas_id && mapel_id && tahun_ajaran_id && siswaList.length > 0" class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
                <div class="px-4 py-2.5 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-800">{{ siswaList.length }} Siswa</span>
                    <span class="text-[11px] text-slate-400 font-mono">Tugas 30% &bull; UTS 30% &bull; UAS 40%</span>
                </div>

                <form @submit.prevent="submit">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead class="bg-slate-50 text-slate-600 text-[11px] font-semibold border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-2.5 w-10 text-center">No</th>
                                    <th class="px-4 py-2.5">Siswa</th>
                                    <th class="px-3 py-2.5 w-24 text-center">Tugas (30%)</th>
                                    <th class="px-3 py-2.5 w-24 text-center">UTS (30%)</th>
                                    <th class="px-3 py-2.5 w-24 text-center">UAS (40%)</th>
                                    <th class="px-3 py-2.5 w-20 text-center">Akhir</th>
                                    <th class="px-3 py-2.5 w-16 text-center">Predikat</th>
                                    <th class="px-4 py-2.5 min-w-64">Capaian Kompetensi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr v-for="(s, index) in siswaList" :key="s.id" class="hover:bg-slate-50/60 transition-colors">
                                    <td class="px-4 py-2.5 text-center font-mono text-slate-400">{{ index + 1 }}</td>
                                    <td class="px-4 py-2.5">
                                        <p class="font-semibold text-slate-900">{{ s.nama }}</p>
                                        <p class="text-[11px] text-slate-400 font-mono">NIS: {{ s.nis }}</p>
                                    </td>
                                    <td class="px-3 py-2.5 text-center">
                                        <input
                                            v-model="form.nilai[s.id].tugas"
                                            :aria-label="`Nilai tugas ${s.nama}`"
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            max="100"
                                            placeholder="0"
                                            class="w-20 px-2 py-1 text-center bg-slate-50 border border-slate-200 rounded text-xs focus:bg-white focus:border-primary-600 outline-none font-mono"
                                        />
                                    </td>
                                    <td class="px-3 py-2.5 text-center">
                                        <input
                                            v-model="form.nilai[s.id].uts"
                                            :aria-label="`Nilai UTS ${s.nama}`"
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            max="100"
                                            placeholder="0"
                                            class="w-20 px-2 py-1 text-center bg-slate-50 border border-slate-200 rounded text-xs focus:bg-white focus:border-primary-600 outline-none font-mono"
                                        />
                                    </td>
                                    <td class="px-3 py-2.5 text-center">
                                        <input
                                            v-model="form.nilai[s.id].uas"
                                            :aria-label="`Nilai UAS ${s.nama}`"
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            max="100"
                                            placeholder="0"
                                            class="w-20 px-2 py-1 text-center bg-slate-50 border border-slate-200 rounded text-xs focus:bg-white focus:border-primary-600 outline-none font-mono"
                                        />
                                    </td>
                                    <td class="px-3 py-2.5 text-center font-mono font-bold text-slate-900">
                                        {{ calculatePreview(s.id).akhir }}
                                    </td>
                                    <td class="px-3 py-2.5 text-center font-bold">
                                        <span
                                            v-if="calculatePreview(s.id).predikat !== '-'"
                                            class="px-2 py-0.5 rounded text-[11px]"
                                            :class="{
                                                'bg-emerald-50 text-emerald-800': calculatePreview(s.id).predikat === 'A',
                                                'bg-blue-50 text-blue-800': calculatePreview(s.id).predikat === 'B',
                                                'bg-amber-50 text-amber-800': calculatePreview(s.id).predikat === 'C',
                                                'bg-rose-50 text-rose-800': calculatePreview(s.id).predikat === 'D' || calculatePreview(s.id).predikat === 'E',
                                            }"
                                        >
                                            {{ calculatePreview(s.id).predikat }}
                                        </span>
                                        <span v-else class="text-slate-400">-</span>
                                    </td>
                                    <td class="px-4 py-2.5 align-top">
                                        <textarea v-model="form.nilai[s.id].capaian_kompetensi" :aria-label="`Capaian kompetensi ${s.nama}`" rows="3" maxlength="3000" placeholder="Tuliskan kompetensi yang dicapai dan yang perlu ditingkatkan..." class="w-full min-w-60 p-3 bg-slate-50 border border-slate-200 rounded-lg text-xs outline-none focus:bg-white focus:border-primary-500 resize-y"></textarea>
                                        <p v-if="form.errors[`nilai.${s.id}.capaian_kompetensi`]" class="text-xs text-rose-600 mt-1">{{ form.errors[`nilai.${s.id}.capaian_kompetensi`] }}</p>
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
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Nilai' }}
                        </button>
                    </div>
                </form>
            </div>

            <div v-else-if="kelas_id && mapel_id && tahun_ajaran_id" class="bg-white rounded-xl p-8 border border-slate-200 text-center text-xs text-slate-400">
                Tidak ada siswa di kelas ini.
            </div>

            <div v-else class="bg-white rounded-xl p-8 border border-slate-200 text-center text-xs text-slate-400">
                Pilih kelas, mata pelajaran, dan tahun ajaran untuk menginput nilai.
            </div>
            </section>
        </div>
    </AppLayout>
</template>
