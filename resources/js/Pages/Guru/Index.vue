<script setup>
import { computed, ref, watch } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { useBiodataPrint } from '@/Composables/useBiodataPrint';

const { printingId, printError, handlePrint } = useBiodataPrint('guru');

const props = defineProps({
    guru: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

const search = ref(props.filters.search || '');
const status = ref(props.filters.status || '');
const page = usePage();
const recapUrl = computed(() => {
    const filters = new URLSearchParams({
        search: props.filters.search || '',
        status: props.filters.status || '',
    });
    return `/guru/rekap?${filters}`;
});

let searchTimeout = null;
const handleFilter = () => {
    router.get('/guru', {
        search: search.value,
        status: status.value,
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

watch(status, () => {
    handleFilter();
});

const handleDelete = (id, name) => {
    if (confirm(`Hapus data guru "${name}"?`)) {
        router.delete(`/guru/${id}`);
    }
};
</script>

<template>
    <AppLayout title="Guru" pageTitle="Guru">
        <div class="space-y-4">
            <p v-if="printError" role="alert" class="rounded-lg bg-rose-50 p-3 text-sm text-rose-700">{{ printError }}</p>
            <!-- Filter & Action Bar -->
            <div class="bg-white rounded-xl p-3 border border-slate-200 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    <div class="relative">
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Cari guru..."
                            class="px-3 py-1.5 pl-8 bg-slate-50 border border-slate-300 rounded-lg text-xs placeholder:text-slate-400 focus:bg-white focus:border-primary-600 outline-none w-48 sm:w-56 transition"
                        />
                        <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[11px]"></i>
                    </div>

                    <select
                        v-model="status"
                        class="px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs text-slate-700 focus:bg-white focus:border-primary-600 outline-none transition cursor-pointer"
                    >
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="tidak_aktif">Nonaktif</option>
                    </select>
                </div>

                <div class="flex flex-wrap gap-2">
                <Link href="/guru/import" class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-primary-200 text-primary-700 hover:bg-primary-50 rounded-lg text-xs font-medium"><i class="fa-solid fa-file-import"></i>Import Guru</Link>
                <button v-if="page.props.auth.can_manage_users" type="button" @click="handlePrint('rekap', { url: recapUrl, selector: '[data-rekap-guru]', title: 'Cetak rekap data guru' })" :disabled="printingId !== null" :aria-busy="printingId === 'rekap'" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50 disabled:opacity-60">
                    <i class="fa-solid" :class="printingId === 'rekap' ? 'fa-spinner fa-spin' : 'fa-print'" aria-hidden="true"></i>Rekap Data Guru
                </button>
                <Link
                    href="/guru/create"
                    class="inline-flex items-center gap-1.5 bg-primary-700 hover:bg-primary-800 text-white font-medium text-xs px-3 py-1.5 rounded-lg transition shrink-0"
                >
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Tambah Guru</span>
                </Link>
                </div>
            </div>

            <!-- Table Container -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse whitespace-nowrap">
                        <thead class="bg-slate-50 text-slate-600 text-[11px] font-semibold border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-2.5">Foto</th>
                                <th class="px-3 py-2.5">Nama Guru</th>
                                <th class="px-3 py-2.5">NIP</th>
                                <th class="px-3 py-2.5">Jenis Kelamin</th>
                                <th class="px-3 py-2.5">Email</th>
                                <th class="px-3 py-2.5">Telepon</th>
                                <th class="px-3 py-2.5">Status</th>
                                <th class="px-4 py-2.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="g in guru.data" :key="g.id" class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-4 py-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs border border-slate-200 overflow-hidden">
                                        <img v-if="g.foto" :src="`/storage/${g.foto}`" :alt="`Foto ${g.nama || 'guru'}`" class="w-full h-full object-cover" />
                                        <span v-else>{{ (g.nama || 'G').charAt(0).toUpperCase() }}</span>
                                    </div>
                                </td>
                                <td class="px-3 py-2.5 font-semibold text-slate-900">{{ g.nama || 'Belum diisi' }}</td>
                                <td class="px-3 py-2.5 font-mono text-slate-600">
                                    {{ g.nip || '-' }}
                                </td>
                                <td class="px-3 py-2.5 text-slate-600">
                                    {{ g.jenis_kelamin === 'L' ? 'Laki-laki' : g.jenis_kelamin === 'P' ? 'Perempuan' : '-' }}
                                </td>
                                <td class="px-3 py-2.5 text-slate-600">{{ g.email || '-' }}</td>
                                <td class="px-3 py-2.5 font-mono text-slate-600">{{ g.telepon || '-' }}</td>
                                <td class="px-3 py-2.5">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium"
                                        :class="g.status_aktif ? 'bg-emerald-50 text-emerald-800' : 'bg-slate-100 text-slate-700'"
                                    >
                                        {{ g.status_aktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <Link
                                            :href="`/guru/${g.id}`"
                                            class="p-1 rounded text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition"
                                            title="Detail"
                                        >
                                            <i class="fa-solid fa-address-card text-xs"></i>
                                        </Link>
                                        <button
                                            v-if="page.props.auth.can_manage_users"
                                            type="button"
                                            @click="handlePrint(g.id)"
                                            :disabled="printingId !== null"
                                            :aria-busy="printingId === g.id"
                                            class="p-1 rounded text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition"
                                            title="Cetak Data Guru"
                                            :aria-label="`Cetak data guru ${g.nama || ''}`"
                                        >
                                            <i class="fa-solid text-xs" :class="printingId === g.id ? 'fa-spinner fa-spin' : 'fa-print'" aria-hidden="true"></i>
                                        </button>
                                        <Link
                                            :href="`/guru/${g.id}/edit`"
                                            class="p-1 rounded text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition"
                                            title="Edit"
                                        >
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </Link>
                                        <button
                                            @click="handleDelete(g.id, g.nama)"
                                            type="button"
                                            class="p-1 rounded text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus"
                                        >
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!guru.data || guru.data.length === 0">
                                <td colspan="8" class="px-4 py-8 text-center text-slate-400 text-xs">
                                    Tidak ada data
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <Pagination :links="guru.links" />
            </div>
        </div>
    </AppLayout>
</template>
