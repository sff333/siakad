<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    users: { type: Object, required: true },
    roles: { type: Object, required: true },
});

const page = usePage();
const updating = ref(false);

function changeStatus(user) {
    if (user.is_active && !window.confirm(`Nonaktifkan akun "${user.name}"?`)) return;

    updating.value = true;
    router.patch(`/pengguna/${user.id}/status`, { is_active: !user.is_active }, {
        preserveScroll: true,
        onFinish: () => { updating.value = false; },
    });
}
</script>

<template>
    <AppLayout title="Manajemen Pengguna" pageTitle="Manajemen Pengguna">
        <div class="mb-4 flex justify-end">
            <Link href="/pengguna/create" class="inline-flex items-center gap-2 rounded-lg bg-primary-700 px-4 py-2.5 text-xs font-semibold text-white hover:bg-primary-800">
                <i class="fa-solid fa-plus" aria-hidden="true"></i>
                Tambah Pengguna
            </Link>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold text-slate-600">
                        <tr>
                            <th scope="col" class="px-4 py-3">Nama</th>
                            <th scope="col" class="px-4 py-3">Email</th>
                            <th scope="col" class="px-4 py-3">Peran</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                            <th scope="col" class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="user in users.data" :key="user.id">
                            <td class="px-4 py-3 font-medium">{{ user.name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ user.email }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-slate-600">{{ roles[user.role] }}</td>
                            <td class="whitespace-nowrap px-4 py-3">
                                <span class="rounded-full px-2 py-1 text-xs font-medium" :class="user.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600'">
                                    {{ user.is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-right">
                                <Link :href="`/pengguna/${user.id}/edit`" :aria-label="`Ubah pengguna ${user.name}`" class="rounded-md px-2 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-900">
                                    Ubah
                                </Link>
                                <button
                                    v-if="user.id !== page.props.auth.user.id"
                                    type="button"
                                    :disabled="updating"
                                    :aria-label="`${user.is_active ? 'Nonaktifkan' : 'Aktifkan'} akun ${user.name}`"
                                    class="rounded-md px-2 py-1 text-xs font-semibold disabled:opacity-60"
                                    :class="user.is_active ? 'text-rose-700 hover:bg-rose-50' : 'text-emerald-700 hover:bg-emerald-50'"
                                    @click="changeStatus(user)"
                                >
                                    {{ user.is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </td>
                        </tr>
                        <tr v-if="!users.data.length">
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada pengguna.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <Pagination :links="users.links" />
        </div>
    </AppLayout>
</template>
