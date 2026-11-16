<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    tahunAjaran: {
        type: Object,
        required: true,
    },
});

const formatDate = (value) => {
    if (!value) return '-';
    const date = new Date(`${String(value).slice(0, 10)}T00:00:00Z`);
    if (Number.isNaN(date.getTime())) return '-';
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric', timeZone: 'UTC',
    }).format(date);
};

const handleActivate = (id, label) => {
    if (confirm(`Aktifkan tahun ajaran "${label}"?`)) {
        router.post(`/tahun-ajaran/${id}/activate`);
    }
};

const handleDelete = (id, label) => {
    if (confirm(`Hapus tahun ajaran "${label}"?`)) {
        router.delete(`/tahun-ajaran/${id}`);
    }
};
</script>

<template>
    <AppLayout title="Tahun Ajaran" pageTitle="Tahun Ajaran">
        <div class="space-y-4">
            <!-- Action Bar -->
            <div class="flex items-center justify-between gap-3">
                <span class="text-xs text-slate-500 font-medium">Data periode akademik</span>

                <Link
                    href="/tahun-ajaran/create"
                    class="inline-flex items-center gap-1.5 bg-primary-700 hover:bg-primary-800 text-white font-medium text-xs px-3 py-1.5 rounded-lg transition shrink-0"
                >
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Tambah</span>
                </Link>
            </div>

            <!-- Table Container -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-50 text-slate-600 text-[11px] font-semibold border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-2.5">Tahun Ajaran</th>
                                <th class="px-4 py-2.5">Semester</th>
                                <th class="px-4 py-2.5">Periode</th>
                                <th class="px-4 py-2.5 text-center">Status</th>
                                <th class="px-4 py-2.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="t in tahunAjaran.data" :key="t.id" class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-4 py-2.5 font-bold text-slate-900 font-mono">{{ t.tahun_ajaran }}</td>
                                <td class="px-4 py-2.5 text-slate-700">Semester {{ t.semester }}</td>
                                <td class="px-4 py-2.5 text-slate-500 text-xs whitespace-nowrap">
                                    {{ formatDate(t.tanggal_mulai) }} s/d {{ formatDate(t.tanggal_selesai) }}
                                </td>
                                <td class="px-4 py-2.5 text-center">
                                    <span
                                        v-if="t.is_active"
                                        class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200"
                                    >
                                        Aktif
                                    </span>
                                    <button
                                        v-else
                                        @click="handleActivate(t.id, `${t.tahun_ajaran} - Semester ${t.semester}`)"
                                        type="button"
                                        class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-50 hover:bg-slate-100 border border-slate-300 text-slate-700 transition cursor-pointer"
                                    >
                                        Aktifkan
                                    </button>
                                </td>
                                <td class="px-4 py-2.5 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <Link
                                            :href="`/tahun-ajaran/${t.id}/edit`"
                                            class="px-2 py-1 rounded text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-medium transition"
                                        >
                                            Edit
                                        </Link>
                                        <button
                                            v-if="!t.is_active"
                                            @click="handleDelete(t.id, `${t.tahun_ajaran} - Semester ${t.semester}`)"
                                            type="button"
                                            class="p-1 rounded text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                        >
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!tahunAjaran.data || tahunAjaran.data.length === 0">
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400 text-xs">
                                    Tidak ada data
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <Pagination :links="tahunAjaran.links" />
            </div>
        </div>
    </AppLayout>
</template>
