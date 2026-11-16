<script setup>
import { computed, ref } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
const page = usePage();
const school = computed(() => page.props.profil_sekolah?.nama_sekolah || 'Sistem Informasi Akademik');
const showPassword = ref(false);
const form = useForm({ email: '', password: '', remember: false });
const submit = () => form.post('/login', { onFinish: () => form.reset('password') });
</script>

<template>
    <Head title="Masuk" />
    <main class="min-h-screen flex items-center justify-center bg-slate-50 px-4 py-8">
        <div class="w-full max-w-sm rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
            <div class="mb-7 text-center">
                <div class="mx-auto mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                    <i aria-hidden="true" class="fa-solid fa-graduation-cap text-xl"></i>
                </div>
                <h1 class="text-xl font-bold text-slate-900">Sistem Informasi Akademik</h1>
                <p v-if="page.props.profil_sekolah?.nama_sekolah" class="mt-2 text-sm text-slate-500">{{ school }}</p>
            </div>

            <div v-if="form.errors.email || form.errors.password" id="login-error" role="alert" class="mb-5 rounded-lg border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700">
                {{ form.errors.email || form.errors.password }}
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                    <input id="email" v-model="form.email" type="email" autocomplete="username" required autofocus placeholder="nama@sekolah.sch.id" :aria-invalid="!!form.errors.email" :aria-describedby="form.errors.email ? 'login-error' : undefined" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-100" />
                </div>
                <div>
                    <label for="password" class="mb-2 block text-sm font-medium text-slate-700">Kata sandi</label>
                    <div class="relative">
                        <input id="password" v-model="form.password" :type="showPassword ? 'text' : 'password'" autocomplete="current-password" required placeholder="Masukkan kata sandi" :aria-invalid="!!form.errors.password" :aria-describedby="form.errors.password ? 'login-error' : undefined" class="w-full rounded-lg border border-slate-300 bg-white py-2.5 pl-3 pr-11 text-sm outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-100" />
                        <button type="button" @click="showPassword = !showPassword" :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'" :aria-pressed="showPassword" class="absolute inset-y-0 right-0 px-3 text-slate-400 hover:text-primary-600">
                            <i aria-hidden="true" class="fa-solid" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>
                <label class="flex cursor-pointer items-center gap-2 text-sm text-slate-500">
                    <input v-model="form.remember" type="checkbox" class="h-4 w-4 accent-primary-600" />
                    Ingat saya
                </label>
                <button type="submit" :disabled="form.processing" class="flex w-full items-center justify-center gap-2 rounded-lg bg-primary-600 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-700 disabled:cursor-wait disabled:opacity-60">
                    <i v-if="form.processing" aria-hidden="true" class="fa-solid fa-spinner fa-spin"></i>
                    {{ form.processing ? 'Memproses...' : 'Masuk' }}
                </button>
            </form>
        </div>
    </main>
</template>
