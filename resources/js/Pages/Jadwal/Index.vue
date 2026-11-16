<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    jadwal: {
        type: Object,
        required: true,
    },
    kelasList: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

const kelas_id = ref(props.filters.kelas_id || '');
const hari = ref(props.filters.hari || '');

const handleFilter = () => {
    router.get('/jadwal', {
        kelas_id: kelas_id.value,
        hari: hari.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

watch([kelas_id, hari], () => {
    handleFilter();
});

const handleDelete = (id) => {
    if (confirm('Hapus jadwal pelajaran ini?')) {
        router.delete(`/jadwal/${id}`);
    }
};
</script>

<template>
    <AppLayout title="Jadwal" pageTitle="Jadwal">
        <div class="space-y-4">
            <!-- Filter & Action Bar -->
            <div class="bg-white rounded-xl p-3 border border-slate-200 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    <select
                        v-model="kelas_id"
                        class="px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs text-slate-700 focus:bg-white focus:border-primary-600 outline-none transition cursor-pointer"
                    >
                        <option value="">Semua Kelas</option>
                        <option v-for="k in kelasList" :key="k.id" :value="k.id">
                            {{ k.nama_kelas }}
                        </option>
                    </select>

                    <select
                        v-model="hari"
                        class="px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs text-slate-700 focus:bg-white focus:border-primary-600 outline-none transition cursor-pointer"
                    >
                        <option value="">Semua Hari</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                    </select>
                </div>

                <Link
                    href="/jadwal/create"
                    class="inline-flex items-center gap-1.5 bg-primary-700 hover:bg-primary-800 text-white font-medium text-xs px-3 py-1.5 rounded-lg transition shrink-0"
                >
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Tambah Jadwal</span>
                </Link>
            </div>

            <!-- Table Container -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-50 text-slate-600 text-[11px] font-semibold border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-2.5">Hari</th>
                                <th class="px-3 py-2.5">Waktu</th>
                                <th class="px-3 py-2.5">Kelas</th>
                                <th class="px-4 py-2.5">Mata Pelajaran</th>
                                <th class="px-4 py-2.5">Guru</th>
                                <th class="px-4 py-2.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="j in jadwal.data" :key="j.id" class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-4 py-2.5 font-semibold text-slate-900">
                                    {{ j.hari }}
                                </td>
                                <td class="px-3 py-2.5 font-mono text-[11px] text-slate-600">
                                    {{ j.jam_mulai }} - {{ j.jam_selesai }}
                                </td>
                                <td class="px-3 py-2.5 font-medium text-slate-800">
                                    {{ j.kelas?.nama_kelas || '-' }}
                                </td>
                                <td class="px-4 py-2.5 font-medium text-slate-900">
                                    {{ j.mapel?.nama_mapel || '-' }}
                                </td>
                                <td class="px-4 py-2.5 text-slate-600">
                                    {{ j.guru?.nama || '-' }}
                                </td>
                                <td class="px-4 py-2.5 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <Link
                                            :href="`/jadwal/${j.id}/edit`"
                                            class="p-1 rounded text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition"
                                            title="Edit"
                                        >
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </Link>
                                        <button
                                            @click="handleDelete(j.id)"
                                            type="button"
                                            class="p-1 rounded text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus"
                                        >
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!jadwal.data || jadwal.data.length === 0">
                                <td colspan="6" class="px-4 py-8 text-center text-slate-400 text-xs">
                                    Tidak ada data
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <Pagination :links="jadwal.links" />
            </div>
        </div>
    </AppLayout>
</template>
