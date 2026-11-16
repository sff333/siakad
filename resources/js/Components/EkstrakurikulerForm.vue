<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    siswaList: { type: Array, required: true },
    records: { type: [Object, Array], default: () => ({}) },
    kelasId: { type: [String, Number], required: true },
    tahunAjaranId: { type: [String, Number], required: true },
});
const selectedStudent = ref('');
const form = useForm({ kelas_id: props.kelasId, tahun_ajaran_id: props.tahunAjaranId, siswa_id: '', kegiatan: [] });
const loadStudent = () => {
    const data = {
        kelas_id: props.kelasId,
        tahun_ajaran_id: props.tahunAjaranId,
        siswa_id: selectedStudent.value,
        kegiatan: (props.records[selectedStudent.value] || []).map(item => ({
            kegiatan: item.kegiatan, predikat: item.predikat, keterangan: item.keterangan || '',
        })),
    };
    form.defaults(data);
    form.reset();
    form.clearErrors();
};
const changeStudent = (event) => {
    if (form.isDirty && !window.confirm('Isian belum disimpan. Pindah siswa dan abaikan perubahan?')) {
        event.target.value = selectedStudent.value;
        return;
    }
    selectedStudent.value = event.target.value;
    loadStudent();
};
watch(() => [props.kelasId, props.tahunAjaranId], () => {
    selectedStudent.value = '';
    loadStudent();
});
const addActivity = () => form.kegiatan.push({ kegiatan: '', predikat: '', keterangan: '' });
const submit = () => form.post('/nilai/ekstrakurikuler', {
    preserveScroll: true,
    onSuccess: loadStudent,
});
</script>

<template>
    <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-5">
        <p class="text-sm text-slate-500">Isi kegiatan ekstrakurikuler siswa untuk bagian B rapor pada semester yang dipilih.</p>
        <div class="max-w-md">
            <label for="siswa-ekstrakurikuler" class="block text-sm font-semibold mb-2">Siswa</label>
            <select id="siswa-ekstrakurikuler" :value="selectedStudent" @change="changeStudent" :disabled="form.processing" class="w-full border border-slate-200 rounded-lg p-3 bg-slate-50 text-sm">
                <option value="">Pilih siswa</option>
                <option v-for="s in siswaList" :key="s.id" :value="s.id">{{ s.nama }} ({{ s.nis }})</option>
            </select>
        </div>
        <form v-if="selectedStudent" @submit.prevent="submit" class="space-y-4">
            <fieldset :disabled="form.processing" class="space-y-4">
                <div v-for="(item, index) in form.kegiatan" :key="index" class="rounded-xl border border-slate-200 p-4 space-y-4">
                    <div class="flex justify-between items-center">
                        <p class="text-sm font-semibold">Kegiatan {{ index + 1 }}</p>
                        <button type="button" @click="form.kegiatan.splice(index, 1)" :aria-label="`Hapus kegiatan ${index + 1}`" class="text-xs text-rose-600 hover:underline">Hapus</button>
                    </div>
                    <div class="grid sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label :for="`kegiatan-${index}`" class="block text-xs font-medium mb-2">Nama kegiatan</label>
                            <input :id="`kegiatan-${index}`" v-model="item.kegiatan" required maxlength="255" placeholder="Contoh: Pramuka" class="w-full p-3 rounded-lg border border-slate-200 text-sm" />
                            <p v-if="form.errors[`kegiatan.${index}.kegiatan`]" class="text-xs text-rose-600 mt-1">{{ form.errors[`kegiatan.${index}.kegiatan`] }}</p>
                        </div>
                        <div>
                            <label :for="`predikat-${index}`" class="block text-xs font-medium mb-2">Predikat</label>
                            <select :id="`predikat-${index}`" v-model="item.predikat" required class="w-full p-3 rounded-lg border border-slate-200 text-sm">
                                <option value="">Pilih predikat</option>
                                <option>Sangat Baik</option><option>Baik</option><option>Cukup</option><option>Perlu Bimbingan</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label :for="`keterangan-${index}`" class="block text-xs font-medium mb-2">Keterangan</label>
                        <textarea :id="`keterangan-${index}`" v-model="item.keterangan" rows="3" maxlength="3000" placeholder="Tuliskan partisipasi dan pencapaian siswa..." class="w-full p-3 rounded-lg border border-slate-200 text-sm resize-y"></textarea>
                    </div>
                </div>
                <p v-if="!form.kegiatan.length" class="text-sm text-slate-400 py-4">Belum ada kegiatan. Tambahkan kegiatan untuk siswa ini.</p>
                <div class="flex flex-wrap justify-between gap-3">
                    <button type="button" @click="addActivity" :disabled="form.kegiatan.length >= 20" class="px-4 py-2.5 border border-slate-200 text-primary-700 text-sm font-medium rounded-lg disabled:opacity-50">+ Tambah kegiatan</button>
                    <button type="submit" class="bg-primary-700 text-white rounded-lg px-4 py-2.5 text-sm font-semibold disabled:opacity-50">{{ form.processing ? 'Menyimpan...' : 'Simpan Ekstrakurikuler' }}</button>
                </div>
                <p v-if="!form.kegiatan.length && (records[selectedStudent] || []).length" class="text-xs text-amber-700">Simpan untuk menghapus seluruh kegiatan siswa ini pada semester yang dipilih.</p>
            </fieldset>
        </form>
    </div>
</template>
