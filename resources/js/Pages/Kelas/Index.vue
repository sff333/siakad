<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    kelas: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

const tingkat = ref(props.filters.tingkat || '');

const handleFilter = () => {
    router.get('/kelas', {
        tingkat: tingkat.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

watch(tingkat, () => {
    handleFilter();
});

const handleDelete = (id, name) => {
    if (confirm(`Hapus kelas "${name}"?`)) {
        router.delete(`/kelas/${id}`);
    }
};
</script>

<template>
    <AppLayout title="Kelas" pageTitle="Kelas">
        <div class="space-y-4">
            <!-- Filter & Action Bar -->
            <div class="bg-white rounded-xl p-3 border border-slate-200 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <select
                        v-model="tingkat"
                        class="px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs text-slate-700 focus:bg-white focus:border-primary-600 outline-none transition cursor-pointer"
                    >
                        <option value="">Semua Tingkat</option>
                        <option v-for="t in 6" :key="t" :value="t">Tingkat {{ t }}</option>
                    </select>
                </div>

                <Link
                    href="/kelas/create"
                    class="inline-flex items-center gap-1.5 bg-primary-700 hover:bg-primary-800 text-white font-medium text-xs px-3 py-1.5 rounded-lg transition shrink-0"
                >
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Tambah Kelas</span>
                </Link>
            </div>

            <!-- Table Container -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-50 text-slate-600 text-[11px] font-semibold border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-2.5">Kelas</th>
                                <th class="px-3 py-2.5">Tingkat</th>
                                <th class="px-3 py-2.5">Wali Kelas</th>
                                <th class="px-3 py-2.5 text-center">Kapasitas</th>
                                <th class="px-4 py-2.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="k in kelas.data" :key="k.id" class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-4 py-2.5 font-semibold text-slate-900">
                                    {{ k.nama_kelas }}
                                </td>
                                <td class="px-3 py-2.5 text-slate-600">
                                    Kelas {{ k.tingkat }}
                                </td>
                                <td class="px-3 py-2.5 text-slate-700">
                                    {{ k.guru?.nama || '-' }}
                                </td>
                                <td class="px-3 py-2.5 text-center font-mono">
                                    {{ k.siswa_count || 0 }} / {{ k.kapasitas || 30 }}
                                </td>
                                <td class="px-4 py-2.5 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <Link
                                            :href="`/kelas/${k.id}`"
                                            class="p-1 rounded text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition"
                                            title="Detail"
                                            :aria-label="`Detail ${k.nama_kelas}`"
                                        >
                                            <i class="fa-solid fa-users text-xs"></i>
                                        </Link>
                                        <Link
                                            :href="`/kelas/${k.id}?tab=mapel`"
                                            class="p-1 rounded text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition"
                                            title="Mata Pelajaran"
                                            :aria-label="`Mata Pelajaran ${k.nama_kelas}`"
                                        >
                                            <i aria-hidden="true" class="fa-solid fa-book-open text-xs"></i>
                                        </Link>
                                        <Link
                                            :href="`/kelas/${k.id}?tab=jadwal`"
                                            class="p-1 rounded text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition"
                                            title="Jadwal Pelajaran"
                                            :aria-label="`Jadwal Pelajaran ${k.nama_kelas}`"
                                        >
                                            <i aria-hidden="true" class="fa-solid fa-calendar-days text-xs"></i>
                                        </Link>
                                        <Link
                                            :href="`/kelas/${k.id}/edit`"
                                            class="p-1 rounded text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition"
                                            title="Edit"
                                        >
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </Link>
                                        <button
                                            @click="handleDelete(k.id, k.nama_kelas)"
                                            type="button"
                                            class="p-1 rounded text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus"
                                        >
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!kelas.data || kelas.data.length === 0">
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400 text-xs">
                                    Tidak ada data
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <Pagination :links="kelas.links" />
            </div>
        </div>
    </AppLayout>
</template>
