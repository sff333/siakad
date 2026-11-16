<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import KopSuratModal from '@/Components/KopSuratModal.vue';

const props = defineProps({
    kelasList: {
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
    tahun_ajaran_id: {
        type: [String, Number],
        default: '',
    },
    siswaList: {
        type: Array,
        default: () => [],
    },
});

const filterKelasId = ref(props.kelas_id || '');
const filterTahunAjaranId = ref(props.tahun_ajaran_id || (props.tahunAjaranList?.[0]?.id || ''));
const showKopModal = ref(false);

const filter = () => {
    if (!filterKelasId.value) return;
    router.get('/rapor', {
        kelas_id: filterKelasId.value,
        tahun_ajaran_id: filterTahunAjaranId.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

watch([filterKelasId, filterTahunAjaranId], () => {
    filter();
});
</script>

<template>
    <AppLayout title="Rapor" pageTitle="Rapor">
        <div class="space-y-4">
            <!-- Filter Bar & Action -->
            <div class="bg-white rounded-xl p-3 border border-slate-200 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2.5">
                    <select v-model="filterKelasId" class="px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs text-slate-700 focus:bg-white focus:border-primary-600 outline-none transition cursor-pointer">
                        <option value="">Pilih Kelas</option>
                        <option v-for="k in kelasList" :key="k.id" :value="k.id">{{ k.nama_kelas }}</option>
                    </select>

                    <select v-model="filterTahunAjaranId" class="px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs text-slate-700 focus:bg-white focus:border-primary-600 outline-none transition cursor-pointer">
                        <option value="">Pilih Tahun Ajaran</option>
                        <option v-for="t in tahunAjaranList" :key="t.id" :value="t.id">{{ t.tahun_ajaran }} (Sem {{ t.semester }})</option>
                    </select>
                </div>

                <button
                    type="button"
                    @click="showKopModal = true"
                    class="px-3 py-1.5 rounded-lg bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 text-xs font-medium transition cursor-pointer inline-flex items-center gap-1.5 shrink-0"
                >
                    <i class="fa-solid fa-heading text-slate-400 text-[11px]"></i>
                    <span>Kop Surat</span>
                </button>
            </div>

            <!-- Siswa List Table -->
            <div v-if="kelas_id && siswaList.length > 0" class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
                <div class="px-4 py-2.5 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-800">{{ siswaList.length }} Siswa</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-50 text-slate-600 text-[11px] font-semibold border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-2.5 w-10 text-center">No</th>
                                <th class="px-4 py-2.5">Siswa</th>
                                <th class="px-3 py-2.5 text-center">L/P</th>
                                <th class="px-3 py-2.5 text-center">Rata-rata Nilai</th>
                                <th class="px-4 py-2.5 text-right">Aksi</th>
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
                                    <span class="px-1.5 py-0.5 rounded text-[11px]" :class="s.jenis_kelamin === 'L' ? 'bg-blue-50 text-blue-700' : 'bg-rose-50 text-rose-700'">
                                        {{ s.jenis_kelamin }}
                                    </span>
                                </td>
                                <td class="px-3 py-2.5 text-center font-mono font-bold text-slate-800">
                                    {{ s.rata_rata != null ? Number(s.rata_rata).toFixed(1) : '-' }}
                                </td>
                                <td class="px-4 py-2.5 text-right">
                                    <Link
                                        :href="`/rapor/${s.id}?tahun_ajaran_id=${filterTahunAjaranId}`"
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded bg-primary-700 hover:bg-primary-800 text-white font-medium text-xs transition shadow-2xs"
                                    >
                                        <i class="fa-solid fa-print text-[10px]"></i>
                                        <span>Cetak Rapor</span>
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-else-if="kelas_id" class="bg-white rounded-xl p-8 border border-slate-200 text-center text-xs text-slate-400">
                Tidak ada siswa di kelas ini.
            </div>

            <div v-else class="bg-white rounded-xl p-8 border border-slate-200 text-center text-xs text-slate-400">
                Pilih kelas untuk menampilkan rapor siswa.
            </div>
        </div>

        <!-- Kop Surat Modal -->
        <KopSuratModal :show="showKopModal" @close="showKopModal = false" />
    </AppLayout>
</template>
