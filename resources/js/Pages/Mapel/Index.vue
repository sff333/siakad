<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    mapel: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

const search = ref(props.filters.search || '');

let searchTimeout = null;
const handleFilter = () => {
    router.get('/mapel', {
        search: search.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(handleFilter, 300);
});

const handleDelete = (id, name) => {
    if (confirm(`Hapus mata pelajaran "${name}"?`)) {
        router.delete(`/mapel/${id}`);
    }
};
</script>

<template>
    <AppLayout title="Mata Pelajaran" pageTitle="Mata Pelajaran">
        <div class="space-y-4">
            <!-- Filter & Action Bar -->
            <div class="bg-white rounded-xl p-3 border border-slate-200 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <div class="relative">
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Cari mata pelajaran..."
                            class="px-3 py-1.5 pl-8 bg-slate-50 border border-slate-300 rounded-lg text-xs placeholder:text-slate-400 focus:bg-white focus:border-primary-600 outline-none w-48 sm:w-56 transition"
                        />
                        <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[11px]"></i>
                    </div>
                </div>

                <Link
                    href="/mapel/create"
                    class="inline-flex items-center gap-1.5 bg-primary-700 hover:bg-primary-800 text-white font-medium text-xs px-3 py-1.5 rounded-lg transition shrink-0"
                >
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Tambah Mapel</span>
                </Link>
            </div>

            <!-- Table Container -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-50 text-slate-600 text-[11px] font-semibold border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-2.5">Kode</th>
                                <th class="px-4 py-2.5">Mata Pelajaran</th>
                                <th class="px-3 py-2.5 text-center">KKM</th>
                                <th class="px-4 py-2.5">Guru Pengampu</th>
                                <th class="px-4 py-2.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="m in mapel.data" :key="m.id" class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-4 py-2.5 font-mono font-bold text-slate-800">
                                    {{ m.kode_mapel }}
                                </td>
                                <td class="px-4 py-2.5 font-semibold text-slate-900">
                                    {{ m.nama_mapel }}
                                </td>
                                <td class="px-3 py-2.5 text-center font-mono font-bold text-slate-700">
                                    {{ m.kkm }}
                                </td>
                                <td class="px-4 py-2.5 text-slate-700">
                                    {{ m.guru?.nama || '-' }}
                                </td>
                                <td class="px-4 py-2.5 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <Link
                                            :href="`/mapel/${m.id}/edit`"
                                            class="p-1 rounded text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition"
                                            title="Edit"
                                        >
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </Link>
                                        <button
                                            @click="handleDelete(m.id, m.nama_mapel)"
                                            type="button"
                                            class="p-1 rounded text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus"
                                        >
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!mapel.data || mapel.data.length === 0">
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400 text-xs">
                                    Tidak ada data
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <Pagination :links="mapel.links" />
            </div>
        </div>
    </AppLayout>
</template>
