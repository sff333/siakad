<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    account: { type: Object, default: null },
    roles: { type: Object, required: true },
    pages: { type: Object, required: true },
});

const page = usePage();
const editing = Boolean(props.account);
const ownAccount = editing && props.account.id === page.props.auth.user.id;
const title = editing ? 'Ubah Pengguna' : 'Tambah Pengguna';

const form = useForm({
    name: props.account?.name ?? '',
    email: props.account?.email ?? '',
    role: props.account?.role ?? 'operator',
    password: '',
    password_confirmation: '',
    page_permissions: [...(props.account?.page_permissions ?? [])],
});

const inputClass = 'mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm outline-none focus:border-primary-600 focus:ring-1 focus:ring-primary-600 disabled:bg-slate-100';

function submit() {
    if (editing) {
        form.put(`/pengguna/${props.account.id}`);
    } else {
        form.post('/pengguna');
    }
}
</script>

<template>
    <AppLayout :title="title" :pageTitle="title">
        <form @submit.prevent="submit" class="mx-auto max-w-xl space-y-4 rounded-xl border border-slate-200 bg-white p-5 sm:p-6">
            <div>
                <label for="name" class="text-xs font-semibold text-slate-700">Nama</label>
                <input id="name" v-model="form.name" type="text" autocomplete="name" required maxlength="255" :class="inputClass" />
            </div>
            <div>
                <label for="email" class="text-xs font-semibold text-slate-700">Email</label>
                <input id="email" v-model="form.email" type="email" autocomplete="email" required maxlength="255" :class="inputClass" />
            </div>
            <div>
                <label for="role" class="text-xs font-semibold text-slate-700">Peran</label>
                <select id="role" v-model="form.role" required :disabled="ownAccount" :class="inputClass">
                    <option v-for="(label, value) in roles" :key="value" :value="value">{{ label }}</option>
                </select>
                <p v-if="ownAccount" class="mt-1 text-xs text-slate-500">Peran akun sendiri hanya bisa diubah oleh Administrator lain.</p>
            </div>
            <div>
                <label for="password" class="text-xs font-semibold text-slate-700">{{ editing ? 'Kata Sandi Baru' : 'Kata Sandi' }}</label>
                <input id="password" v-model="form.password" type="password" autocomplete="new-password" :required="!editing" minlength="8" maxlength="72" :class="inputClass" />
                <p class="mt-1 text-xs text-slate-500">{{ editing ? 'Isi minimal 8 karakter untuk mengganti kata sandi.' : 'Minimal 8 karakter.' }}</p>
            </div>
            <div>
                <label for="password_confirmation" class="text-xs font-semibold text-slate-700">Konfirmasi Kata Sandi</label>
                <input id="password_confirmation" v-model="form.password_confirmation" type="password" autocomplete="new-password" :required="!editing || !!form.password" :class="inputClass" />
            </div>
            <fieldset class="rounded-lg border border-slate-200 p-4">
                <legend class="px-1 text-sm font-semibold">Hak Akses Halaman</legend>
                <p v-if="form.role === 'admin'" class="text-xs text-slate-500">Administrator memiliki akses penuh ke seluruh halaman.</p>
                <template v-else>
                    <p class="mb-3 text-xs text-slate-500">Pilih halaman yang boleh dibuka operator, termasuk aksi tambah, ubah, hapus, dan import di halaman tersebut. Cetak biodata/rekap siswa dan guru, manajemen pengguna, serta log aktivitas tetap khusus administrator. Tanpa pilihan, operator hanya dapat membuka Dashboard tanpa ringkasan data dan keluar.</p>
                    <div class="mb-3 flex gap-3 text-xs">
                        <button type="button" @click="form.page_permissions = Object.keys(pages)" class="font-semibold text-primary-700">Pilih semua</button>
                        <button type="button" @click="form.page_permissions = []" class="text-slate-600">Kosongkan</button>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <label v-for="(label, key) in pages" :key="key" class="flex items-start gap-2 text-xs text-slate-700">
                            <input v-model="form.page_permissions" type="checkbox" :value="key" class="mt-0.5 accent-primary-700" />
                            <span>{{ label }}</span>
                        </label>
                    </div>
                    <p v-if="form.errors.page_permissions" role="alert" class="mt-2 text-xs text-rose-700">{{ form.errors.page_permissions }}</p>
                </template>
            </fieldset>
            <div class="flex justify-end gap-2 border-t border-slate-200 pt-4">
                <Link href="/pengguna" class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Batal</Link>
                <button type="submit" :disabled="form.processing" class="rounded-lg bg-primary-700 px-4 py-2 text-xs font-semibold text-white hover:bg-primary-800 disabled:opacity-60">Simpan</button>
            </div>
        </form>
    </AppLayout>
</template>
