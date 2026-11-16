<script setup>
import { computed, ref, watch } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { useBiodataPrint } from '@/Composables/useBiodataPrint';

const page = usePage();
const { printingId, printError, handlePrint } = useBiodataPrint('siswa');

const props = defineProps({
    siswa: {
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

const search = ref(props.filters.search || '');
const kelas_id = ref(props.filters.kelas_id || '');
const status = ref(props.filters.status || '');
const recapUrl = computed(() => `/siswa/rekap?${new URLSearchParams({
    search: props.filters.search || '',
    kelas_id: props.filters.kelas_id || '',
    status: props.filters.status || '',
})}`);

let searchTimeout = null;
const handleFilter = () => {
    router.get('/siswa', {
        search: search.value,
        kelas_id: kelas_id.value,
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

watch([kelas_id, status], () => {
    handleFilter();
});

const handleDelete = (id, name) => {
    if (confirm(`Hapus data siswa "${name}"?`)) {
        router.delete(`/siswa/${id}`);
    }
};
</script>

<template>
    <AppLayout title="Siswa" pageTitle="Siswa">
        <div class="space-y-4">
            <p v-if="printError" role="alert" class="rounded-lg bg-rose-50 p-3 text-sm text-rose-700">{{ printError }}</p>
            <!-- Filter & Action Bar -->
            <div class="bg-white rounded-xl p-3 border border-slate-200 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    <div class="relative">
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Cari siswa..."
                            class="px-3 py-1.5 pl-8 bg-slate-50 border border-slate-300 rounded-lg text-xs placeholder:text-slate-400 focus:bg-white focus:border-primary-600 outline-none w-48 sm:w-56 transition"
                        />
                        <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[11px]"></i>
                    </div>

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
                        v-model="status"
                        class="px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs text-slate-700 focus:bg-white focus:border-primary-600 outline-none transition cursor-pointer"
                    >
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="lulus">Lulus</option>
                        <option value="pindah">Pindah</option>
                        <option value="keluar">Keluar</option>
                    </select>
                </div>

                <div class="flex flex-wrap gap-2">
                <button v-if="page.props.auth.can_manage_users" type="button" @click="handlePrint('rekap', { url: recapUrl, selector: '[data-rekap-siswa]', title: 'Cetak rekap data siswa' })" :disabled="printingId !== null" :aria-busy="printingId === 'rekap'" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50 disabled:opacity-60">
                    <i class="fa-solid" :class="printingId === 'rekap' ? 'fa-spinner fa-spin' : 'fa-print'" aria-hidden="true"></i>Rekap Data Siswa
                </button>
                <Link href="/siswa/import" class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-primary-200 text-primary-700 hover:bg-primary-50 rounded-lg text-xs font-medium"><i class="fa-solid fa-file-import"></i>Import Siswa</Link>
                <Link :href="kelas_id ? `/rombel?kelas_id=${kelas_id}` : '/rombel'" class="inline-flex items-center gap-2 px-3 py-2 border border-primary-200 text-primary-700 rounded-lg text-xs font-medium">Atur Rombel</Link>
                <Link
                    href="/siswa/create"
                    class="inline-flex items-center gap-1.5 bg-primary-700 hover:bg-primary-800 text-white font-medium text-xs px-3 py-1.5 rounded-lg transition shrink-0"
                >
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Tambah Siswa</span>
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
                                <th class="px-3 py-2.5">Nama Siswa</th>
                                <th class="px-3 py-2.5">NIS</th>
                                <th class="px-3 py-2.5">NISN</th>
                                <th class="px-3 py-2.5">Kelas</th>
                                <th class="px-3 py-2.5 text-center">L/P</th>
                                <th class="px-3 py-2.5">Nama Wali / Ortu</th>
                                <th class="px-3 py-2.5">Telepon Wali / Ortu</th>
                                <th class="px-3 py-2.5">Status</th>
                                <th class="px-4 py-2.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="s in siswa.data" :key="s.id" class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-4 py-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs border border-slate-200 overflow-hidden">
                                        <img v-if="s.foto" :src="`/storage/${s.foto}`" :alt="`Foto ${s.nama || 'siswa'}`" class="w-full h-full object-cover" />
                                        <span v-else>{{ (s.nama || 'S').charAt(0).toUpperCase() }}</span>
                                    </div>
                                </td>
                                <td class="px-3 py-2.5 font-semibold text-slate-900">{{ s.nama || 'Belum diisi' }}</td>
                                <td class="px-3 py-2.5 font-mono text-slate-600">{{ s.nis || '-' }}</td>
                                <td class="px-3 py-2.5 font-mono text-slate-600">{{ s.nisn || '-' }}</td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700">
                                        {{ s.kelas?.nama_kelas || '-' }}
                                    </span>
                                </td>
                                <td class="px-3 py-2.5 text-center font-medium">
                                    <span class="px-1.5 py-0.5 rounded text-[11px]" :class="s.jenis_kelamin === 'L' ? 'bg-blue-50 text-blue-700' : 'bg-rose-50 text-rose-700'">
                                        {{ s.jenis_kelamin || '-' }}
                                    </span>
                                </td>
                                <td class="px-3 py-2.5 text-slate-600">
                                    {{ s.nama_wali || s.nama_ayah || s.nama_ibu || '-' }}
                                </td>
                                <td class="px-3 py-2.5 font-mono text-slate-600">{{ s.telepon_wali || s.telepon_ortu || '-' }}</td>
                                <td class="px-3 py-2.5">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium capitalize"
                                        :class="{
                                            'bg-emerald-50 text-emerald-800': s.status === 'aktif',
                                            'bg-blue-50 text-blue-800': s.status === 'lulus',
                                            'bg-amber-50 text-amber-800': s.status === 'pindah',
                                            'bg-slate-100 text-slate-700': s.status === 'keluar',
                                        }"
                                    >
                                        {{ s.status }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <Link
                                            :href="`/siswa/${s.id}`"
                                            class="p-1 rounded text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition"
                                            title="Detail"
                                        >
                                            <i class="fa-solid fa-address-card text-xs"></i>
                                        </Link>
                                        <button
                                            v-if="page.props.auth.can_manage_users"
                                            type="button"
                                            @click="handlePrint(s.id)"
                                            :disabled="printingId !== null"
                                            :aria-busy="printingId === s.id"
                                            class="p-1 rounded text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition"
                                            title="Cetak Data Siswa"
                                            :aria-label="`Cetak data siswa ${s.nama || ''}`"
                                        >
                                            <i class="fa-solid text-xs" :class="printingId === s.id ? 'fa-spinner fa-spin' : 'fa-print'" aria-hidden="true"></i>
                                        </button>
                                        <Link
                                            :href="`/siswa/${s.id}/edit`"
                                            class="p-1 rounded text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition"
                                            title="Edit"
                                        >
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </Link>
                                        <button
                                            @click="handleDelete(s.id, s.nama)"
                                            type="button"
                                            class="p-1 rounded text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus"
                                        >
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!siswa.data || siswa.data.length === 0">
                                <td colspan="10" class="px-4 py-8 text-center text-slate-400 text-xs">
                                    Tidak ada data
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <Pagination :links="siswa.links" />
            </div>
        </div>
    </AppLayout>
</template>
