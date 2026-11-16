<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Akses dibatasi" pageTitle="Akses dibatasi">
        <section class="rounded-xl border border-slate-200 bg-white p-6">
            <h1 class="text-lg font-semibold">Anda tidak memiliki akses ke halaman ini.</h1>
            <p class="mt-2 text-sm text-slate-500">Hubungi administrator jika Anda memerlukan akses.</p>
            <Link href="/dashboard" class="mt-4 inline-block text-sm font-semibold text-primary-700">Kembali ke Dashboard</Link>
        </section>
    </AppLayout>
</template>
