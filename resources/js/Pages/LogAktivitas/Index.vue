<script setup>
import Pagination from '@/Components/Pagination.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    logs: { type: Object, required: true },
});

const formatter = new Intl.DateTimeFormat('id-ID', {
    dateStyle: 'medium',
    timeStyle: 'medium',
    timeZone: 'Asia/Jakarta',
});
</script>

<template>
    <AppLayout title="Log Aktivitas" pageTitle="Log Aktivitas">
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold text-slate-600">
                        <tr>
                            <th scope="col" class="whitespace-nowrap px-4 py-3">Waktu (WIB)</th>
                            <th scope="col" class="px-4 py-3">Pengguna</th>
                            <th scope="col" class="px-4 py-3">Aktivitas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="log in logs.data" :key="log.id">
                            <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                <time :datetime="log.created_at">{{ formatter.format(new Date(log.created_at)) }}</time>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ log.nama_pengguna }}</div>
                                <div class="text-xs text-slate-500">{{ log.peran }}</div>
                            </td>
                            <td class="px-4 py-3">
                                {{ log.aktivitas }}
                                <div v-if="log.halaman" class="break-all text-xs text-slate-500">{{ log.halaman }}</div>
                            </td>
                        </tr>
                        <tr v-if="!logs.data.length">
                            <td colspan="3" class="px-4 py-8 text-center text-slate-500">Belum ada aktivitas tercatat.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <Pagination :links="logs.links" />
        </div>
    </AppLayout>
</template>
