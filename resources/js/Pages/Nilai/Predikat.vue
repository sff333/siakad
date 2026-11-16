<script setup>
import { computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    kelas: { type: Object, required: true },
    tahunAjaran: { type: Object, required: true },
    aturanPredikat: { type: Object, required: true },
    defaultPredikat: { type: Object, required: true },
    mapel_id: { type: [Number, String], default: null },
});
const form = useForm({
    kelas_id: props.kelas.id,
    tahun_ajaran_id: props.tahunAjaran.id,
    mapel_id: props.mapel_id,
    min_a: props.aturanPredikat.A,
    min_b: props.aturanPredikat.B,
    min_c: props.aturanPredikat.C,
    min_d: props.aturanPredikat.D,
});
const backUrl = computed(() => `/nilai?kelas_id=${props.kelas.id}&tahun_ajaran_id=${props.tahunAjaran.id}${props.mapel_id ? '&mapel_id=' + props.mapel_id : ''}`);
const letters = ['A', 'B', 'C', 'D', 'E'];
const minimum = letter => letter === 'E' ? 0 : Number(form[`min_${letter.toLowerCase()}`]);
const valid = computed(() => {
    const values = letters.map(minimum);
    return values.every(Number.isFinite) && values[0] <= 100 && values[3] > 0
        && values.every((value, index) => index === 4 || value > values[index + 1]);
});
const number = value => Number.isFinite(value) ? value.toLocaleString('id-ID', { maximumFractionDigits: 2 }) : '-';
const range = index => index === 0 ? `${number(minimum('A'))} s/d 100` : `${number(minimum(letters[index]))} sampai kurang dari ${number(minimum(letters[index - 1]))}`;
const restoreDefaults = () => {
    ['A', 'B', 'C', 'D'].forEach(letter => { form[`min_${letter.toLowerCase()}`] = props.defaultPredikat[letter]; });
    form.clearErrors();
};
const submit = () => form.put('/nilai/predikat', { preserveScroll: true });
</script>

<template>
    <AppLayout title="Atur Predikat" pageTitle="Atur Predikat">
        <div class="max-w-2xl mx-auto space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <p class="text-sm text-slate-600"><strong>{{ kelas.nama_kelas }}</strong> · {{ tahunAjaran.tahun_ajaran }} ({{ tahunAjaran.semester }})</p>
                <Link :href="backUrl" class="text-xs text-slate-500 hover:text-primary-600">Kembali ke Input Nilai</Link>
            </div>
            <form @submit.prevent="submit" class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <fieldset :disabled="form.processing">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200"><tr><th class="px-5 py-3">Predikat</th><th class="px-5 py-3">Batas minimal</th><th class="px-5 py-3">Rentang nilai</th></tr></thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr v-for="(letter, index) in letters" :key="letter">
                                    <td class="px-5 py-3 font-bold text-slate-800">{{ letter }}</td>
                                    <td class="px-5 py-3">
                                        <input v-if="letter !== 'E'" v-model="form[`min_${letter.toLowerCase()}`]" :aria-label="`Batas minimal predikat ${letter}`" :aria-invalid="!!form.errors[`min_${letter.toLowerCase()}`]" type="number" required min="0.01" max="100" step="0.01" class="w-24 border border-slate-200 rounded-lg px-3 py-2 text-sm" />
                                        <span v-else class="block px-3 text-slate-500">0</span>
                                        <p v-if="form.errors[`min_${letter.toLowerCase()}`]" class="mt-1 text-xs text-rose-600">{{ form.errors[`min_${letter.toLowerCase()}`] }}</p>
                                    </td>
                                    <td class="px-5 py-3 text-xs text-slate-500">{{ range(index) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="border-t border-slate-200 p-5 space-y-4">
                        <p v-if="!valid" role="alert" class="text-xs text-rose-600">Batas harus berurutan A &gt; B &gt; C &gt; D &gt; 0, dengan nilai maksimal 100.</p>
                        <p class="text-xs text-slate-500 leading-relaxed">Berlaku untuk seluruh mata pelajaran pada kelas dan semester ini. Menyimpan pengaturan akan memperbarui predikat nilai yang sudah tersimpan.</p>
                        <div class="flex flex-wrap justify-between gap-3">
                            <button type="button" @click="restoreDefaults" class="border border-slate-200 text-slate-600 rounded-lg px-4 py-2 text-xs">Gunakan Batas Awal</button>
                            <button type="submit" :disabled="!valid || form.processing" class="bg-primary-700 text-white font-semibold rounded-lg px-4 py-2 text-sm disabled:opacity-50">{{ form.processing ? 'Menyimpan...' : 'Simpan Predikat' }}</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </AppLayout>
</template>
