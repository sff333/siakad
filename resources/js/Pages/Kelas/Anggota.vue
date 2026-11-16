<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    kelas: { type: Object, default: null },
    tahunAjaran: { type: Object, default: null },
    tahunAjaranList: { type: Array, default: () => [] },
    kelasList: { type: Array, default: () => [] },
    siswaList: { type: Array, default: () => [] },
});
const students = ref([]);
const searchMembers = ref('');
const searchAvailable = ref('');
const selectedMembers = ref([]);
const selectedAvailable = ref([]);
const dragIds = ref([]);
const dropZone = ref('');
const pointerDrag = ref(null);
const message = ref('');
const localError = ref('');
const form = useForm({ tahun_ajaran_id: props.tahunAjaran?.id, perubahan: [] });
const loadStudents = () => {
    students.value = props.siswaList.map(s => ({ ...s, original_kelas_id: s.kelas_id }));
    selectedMembers.value = [];
    selectedAvailable.value = [];
    localError.value = '';
    message.value = '';
};
loadStudents();
watch(() => [props.kelas?.id, props.tahunAjaran?.tahun_ajaran], loadStudents);
const members = computed(() => students.value.filter(s => s.kelas_id === props.kelas?.id));
const available = computed(() => students.value.filter(s => s.kelas_id === null && s.status === 'aktif'));
const changes = computed(() => students.value.filter(s => s.kelas_id !== s.original_kelas_id).map(s => ({
    siswa_id: s.id, kelas_asal_id: s.original_kelas_id, kelas_tujuan_id: s.kelas_id,
})));
const matches = (s, query) => [s.nama, s.nis, s.nisn].some(value => String(value || '').toLowerCase().includes(query.trim().toLowerCase()));
const visibleMembers = computed(() => members.value.filter(s => matches(s, searchMembers.value)));
const visibleAvailable = computed(() => available.value.filter(s => matches(s, searchAvailable.value)));
const selectedVisible = (side) => {
    const selected = side === 'members' ? selectedMembers.value : selectedAvailable.value;
    const list = side === 'members' ? visibleMembers.value : visibleAvailable.value;
    return selected.filter(id => list.some(s => s.id === id && s.status === 'aktif'));
};
const toggleAll = (side, checked) => {
    const target = side === 'members' ? selectedMembers : selectedAvailable;
    const list = side === 'members' ? visibleMembers.value : visibleAvailable.value;
    target.value = checked ? list.filter(s => s.status === 'aktif').map(s => s.id) : [];
};
const moveStudents = (ids, destination) => {
    if (form.processing || !props.kelas) return;
    const targetId = destination === 'members' ? props.kelas.id : null;
    const eligible = students.value.filter(s => ids.includes(s.id) && s.status === 'aktif'
        && (destination === 'members' ? s.kelas_id === null : s.kelas_id === props.kelas.id));
    if (!eligible.length) return;
    if (destination === 'members' && members.value.length + eligible.length > props.kelas.kapasitas) {
        localError.value = `Kapasitas kelas hanya ${props.kelas.kapasitas} siswa. Keluarkan anggota terlebih dahulu atau pilih lebih sedikit siswa.`;
        return;
    }
    eligible.forEach(s => { s.kelas_id = targetId; });
    selectedMembers.value = selectedMembers.value.filter(id => !eligible.some(s => s.id === id));
    selectedAvailable.value = selectedAvailable.value.filter(id => !eligible.some(s => s.id === id));
    localError.value = '';
    form.clearErrors();
    message.value = `${eligible.length} siswa ${destination === 'members' ? 'dimasukkan ke rombel' : 'dikeluarkan dari rombel'}. Simpan perubahan untuk menerapkan.`;
};
const startDrag = (event, student, side) => {
    if (form.processing || student.status !== 'aktif') { event.preventDefault(); return; }
    const selected = selectedVisible(side);
    dragIds.value = selected.includes(student.id) ? selected : [student.id];
    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.setData('text/plain', student.nama);
};
const endDrag = () => { dragIds.value = []; dropZone.value = ''; };
const drop = (destination) => { moveStudents(dragIds.value, destination); endDrag(); };
const pointerMove = event => {
    if (!pointerDrag.value || event.pointerId !== pointerDrag.value.pointerId) return;
    const state = pointerDrag.value;
    if (Math.hypot(event.clientX - state.startX, event.clientY - state.startY) > 6) state.active = true;
    state.x = event.clientX;
    state.y = event.clientY;
    if (state.active) {
        event.preventDefault();
        dropZone.value = document.elementFromPoint(event.clientX, event.clientY)?.closest('[data-rombel-panel]')?.dataset.rombelPanel || '';
    }
};
const pointerEnd = event => {
    if (!pointerDrag.value || event.pointerId !== pointerDrag.value.pointerId) return;
    if (event.type === 'pointerup' && pointerDrag.value.active) {
        const panel = document.elementFromPoint(event.clientX, event.clientY)?.closest('[data-rombel-panel]')?.dataset.rombelPanel;
        if (panel) moveStudents(dragIds.value, panel);
    }
    pointerDrag.value = null;
    endDrag();
    document.removeEventListener('pointermove', pointerMove);
    document.removeEventListener('pointerup', pointerEnd);
    document.removeEventListener('pointercancel', pointerEnd);
};
const startPointer = (event, student, side) => {
    if (form.processing || student.status !== 'aktif' || event.button !== 0) return;
    event.preventDefault();
    const selected = selectedVisible(side);
    dragIds.value = selected.includes(student.id) ? selected : [student.id];
    pointerDrag.value = { pointerId: event.pointerId, startX: event.clientX, startY: event.clientY, x: event.clientX, y: event.clientY, active: false };
    event.currentTarget.setPointerCapture(event.pointerId);
    document.addEventListener('pointermove', pointerMove, { passive: false });
    document.addEventListener('pointerup', pointerEnd);
    document.addEventListener('pointercancel', pointerEnd);
};
const changeClass = event => {
    if (changes.value.length && !window.confirm('Perubahan belum disimpan. Ganti kelas dan abaikan perubahan?')) {
        event.target.value = props.kelas?.id || '';
        return;
    }
    router.get('/rombel', { kelas_id: event.target.value, tahun_ajaran_id: props.tahunAjaran?.id }, { preserveState: false });
};
const changeYear = event => {
    if (changes.value.length && !window.confirm('Perubahan belum disimpan. Ganti tahun ajaran dan abaikan perubahan?')) {
        event.target.value = props.tahunAjaran?.id || '';
        return;
    }
    router.get('/rombel', { kelas_id: props.kelas?.id, tahun_ajaran_id: event.target.value }, { preserveState: false });
};
const reset = () => { loadStudents(); form.clearErrors(); };
const save = () => {
    form.tahun_ajaran_id = props.tahunAjaran.id;
    form.perubahan = changes.value;
    form.patch(`/kelas/${props.kelas.id}/anggota`, { preserveScroll: true, onSuccess: loadStudents });
};
const beforeUnload = event => {
    if (changes.value.length && !form.processing) { event.preventDefault(); event.returnValue = ''; }
};
onMounted(() => window.addEventListener('beforeunload', beforeUnload));
onBeforeUnmount(() => {
    window.removeEventListener('beforeunload', beforeUnload);
    document.removeEventListener('pointermove', pointerMove);
    document.removeEventListener('pointerup', pointerEnd);
    document.removeEventListener('pointercancel', pointerEnd);
});
const leave = event => {
    if (changes.value.length && !window.confirm('Perubahan belum disimpan. Keluar dan abaikan perubahan?')) event.preventDefault();
};
</script>

<template>
    <AppLayout title="Anggota Rombel" pageTitle="Anggota Rombel">
        <div v-if="pointerDrag?.active" aria-hidden="true" class="fixed z-[60] pointer-events-none bg-primary-600 text-white rounded-lg px-3 py-2 text-xs shadow-lg" :style="{left: `${pointerDrag.x + 12}px`, top: `${pointerDrag.y + 12}px`}">{{ dragIds.length }} siswa</div>
        <div class="space-y-4">
            <div class="bg-white border border-slate-200 rounded-xl p-4 flex flex-wrap items-end justify-between gap-4">
                <div class="min-w-48">
                    <label for="tahun-rombel" class="block text-xs font-semibold mb-2">Tahun ajaran</label>
                    <select id="tahun-rombel" :value="tahunAjaran?.id || ''" @change="changeYear" :disabled="form.processing" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm">
                        <option value="">Pilih tahun ajaran</option>
                        <option v-for="t in tahunAjaranList" :key="t.id" :value="t.id">{{ t.tahun_ajaran }}</option>
                    </select>
                </div>
                <div class="flex-1 min-w-56 max-w-md">
                    <label for="rombel-tujuan" class="block text-xs font-semibold mb-2">Kelas / rombel tujuan</label>
                    <select id="rombel-tujuan" :value="kelas?.id || ''" @change="changeClass" :disabled="form.processing" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm">
                        <option value="">Pilih kelas</option>
                        <option v-for="k in kelasList" :key="k.id" :value="k.id">{{ k.nama_kelas }}</option>
                    </select>
                </div>
                <Link :href="kelas ? `/kelas/${kelas.id}?tahun_ajaran_id=${tahunAjaran?.id || ''}` : '/kelas'" @click="leave" class="text-xs text-slate-500 hover:text-primary-600 py-2">{{ kelas ? 'Kembali ke detail kelas' : 'Daftar kelas' }}</Link>
            </div>
            <template v-if="kelas && tahunAjaran">
                <p class="text-xs text-slate-500">Atur rombel tahun ajaran {{ tahunAjaran.tahun_ajaran }}. Seret siswa antarpanel atau gunakan tombol pindah, lalu simpan. Anggota berlaku untuk semester ganjil dan genap.</p>
                <div v-if="localError" role="alert" class="rounded-lg bg-amber-50 border border-amber-200 p-3 text-sm text-amber-800">{{ localError }}</div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <section data-rombel-panel="members" aria-label="Anggota rombel" @dragover.prevent="dropZone = 'members'" @dragleave.self="dropZone = ''" @drop.prevent="drop('members')" class="bg-white border-2 rounded-xl overflow-hidden flex flex-col transition-colors" :class="dropZone === 'members' && dragIds.length ? 'border-primary-400 bg-primary-50/30' : 'border-slate-200'">
                        <div class="p-4 border-b border-slate-100 space-y-3">
                            <div class="flex items-center justify-between gap-2"><h2 class="text-sm font-semibold">Anggota {{ kelas.nama_kelas }}</h2><span class="text-xs text-slate-500 tabular-nums">{{ members.length }} / {{ kelas.kapasitas }} siswa</span></div>
                            <input v-model="searchMembers" aria-label="Cari anggota rombel" placeholder="Cari nama, NIS, atau NISN..." class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs" />
                            <button type="button" @click="moveStudents(selectedVisible('members'), 'available')" :disabled="!selectedVisible('members').length || form.processing" class="text-xs border border-rose-200 text-rose-600 rounded-lg px-3 py-2 hover:bg-rose-50 disabled:opacity-40 disabled:cursor-not-allowed">Keluarkan terpilih ({{ selectedVisible('members').length }}) →</button>
                        </div>
                        <div class="overflow-auto h-[28rem]">
                            <table class="w-full text-left text-xs whitespace-nowrap">
                                <thead class="bg-slate-50 sticky top-0 z-10"><tr>
                                    <th class="px-3 w-10"><input type="checkbox" aria-label="Pilih semua anggota yang terlihat" :disabled="form.processing" :checked="visibleMembers.some(s => s.status === 'aktif') && visibleMembers.filter(s => s.status === 'aktif').every(s => selectedMembers.includes(s.id))" @change="toggleAll('members', $event.target.checked)" /></th>
                                    <th class="px-3">Peserta didik</th><th class="px-3">NIS / NISN</th><th class="px-3">JK</th><th class="px-3 text-right">Aksi</th>
                                </tr></thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr v-for="s in visibleMembers" :key="s.id" :draggable="s.status === 'aktif' && !form.processing" @dragstart="startDrag($event, s, 'members')" @dragend="endDrag" :class="selectedMembers.includes(s.id) ? 'bg-primary-50' : ''" class="cursor-grab active:cursor-grabbing">
                                        <td class="px-3"><div class="flex items-center gap-1"><button type="button" draggable="false" @pointerdown.stop="startPointer($event, s, 'members')" :disabled="s.status !== 'aktif' || form.processing" :aria-label="`Seret anggota ${s.nama}`" title="Seret untuk memindahkan siswa" class="touch-none select-none cursor-grab active:cursor-grabbing p-1 text-slate-400 disabled:opacity-30"><i class="fa-solid fa-grip-vertical"></i></button><input v-model="selectedMembers" type="checkbox" :value="s.id" :disabled="s.status !== 'aktif' || form.processing" :aria-label="`Pilih anggota ${s.nama}`" /></div></td>
                                        <td class="px-3"><p class="font-medium text-slate-800">{{ s.nama }}</p><p v-if="s.status !== 'aktif'" class="text-[11px] text-slate-400">{{ s.status }} · tidak dapat dipindahkan</p><p v-else-if="s.original_kelas_id !== s.kelas_id" class="text-[11px] text-primary-600">Belum disimpan</p></td>
                                        <td class="px-3 text-slate-500"><p>{{ s.nis || '-' }}</p><p class="text-[11px] text-slate-400">{{ s.nisn || '-' }}</p></td><td class="px-3">{{ s.jenis_kelamin }}</td>
                                        <td class="px-3 text-right"><button type="button" @click="moveStudents([s.id], 'available')" :disabled="s.status !== 'aktif' || form.processing" :aria-label="`Keluarkan ${s.nama} dari rombel`" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg disabled:opacity-30">→</button></td>
                                    </tr>
                                    <tr v-if="!visibleMembers.length"><td colspan="5" class="px-4 py-12 text-center text-slate-400 whitespace-normal">{{ searchMembers ? 'Tidak ada siswa yang sesuai pencarian.' : 'Seret siswa ke sini untuk menambahkan anggota rombel.' }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="px-4 py-3 border-t border-slate-100 text-[11px] text-slate-400">Menampilkan {{ visibleMembers.length }} dari {{ members.length }} anggota</div>
                    </section>
                    <section data-rombel-panel="available" aria-label="Siswa tersedia" @dragover.prevent="dropZone = 'available'" @dragleave.self="dropZone = ''" @drop.prevent="drop('available')" class="bg-white border-2 rounded-xl overflow-hidden flex flex-col transition-colors" :class="dropZone === 'available' && dragIds.length ? 'border-primary-400 bg-primary-50/30' : 'border-slate-200'">
                        <div class="p-4 border-b border-slate-100 space-y-3">
                            <div class="flex items-center justify-between gap-2"><h2 class="text-sm font-semibold">Siswa tersedia</h2><span class="text-xs text-slate-500">{{ available.length }} siswa</span></div>
                            <input v-model="searchAvailable" aria-label="Cari siswa tersedia" placeholder="Cari nama, NIS, atau NISN..." class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs" />
                            <div class="flex flex-wrap gap-2 justify-between">
                                <button type="button" @click="moveStudents(selectedVisible('available'), 'members')" :disabled="!selectedVisible('available').length || form.processing" class="text-xs border border-primary-200 text-primary-600 rounded-lg px-3 py-2 hover:bg-primary-50 disabled:opacity-40 disabled:cursor-not-allowed">← Masukkan terpilih ({{ selectedVisible('available').length }})</button>
                            </div>
                        </div>
                        <div class="overflow-auto h-[28rem]">
                            <table class="w-full text-left text-xs whitespace-nowrap">
                                <thead class="bg-slate-50 sticky top-0 z-10"><tr>
                                    <th class="px-3 w-10"><input type="checkbox" aria-label="Pilih semua siswa tersedia yang terlihat" :disabled="form.processing" :checked="visibleAvailable.length > 0 && visibleAvailable.every(s => selectedAvailable.includes(s.id))" @change="toggleAll('available', $event.target.checked)" /></th>
                                    <th class="px-3">Peserta didik</th><th class="px-3">Kelas sebelumnya</th><th class="px-3">JK</th><th class="px-3 text-right">Aksi</th>
                                </tr></thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr v-for="s in visibleAvailable" :key="s.id" :draggable="!form.processing" @dragstart="startDrag($event, s, 'available')" @dragend="endDrag" :class="selectedAvailable.includes(s.id) ? 'bg-primary-50' : ''" class="cursor-grab active:cursor-grabbing">
                                        <td class="px-3"><div class="flex items-center gap-1"><button type="button" draggable="false" @pointerdown.stop="startPointer($event, s, 'available')" :disabled="form.processing" :aria-label="`Seret siswa ${s.nama}`" title="Seret untuk memindahkan siswa" class="touch-none select-none cursor-grab active:cursor-grabbing p-1 text-slate-400 disabled:opacity-30"><i class="fa-solid fa-grip-vertical"></i></button><input v-model="selectedAvailable" type="checkbox" :value="s.id" :disabled="form.processing" :aria-label="`Pilih siswa ${s.nama}`" /></div></td>
                                        <td class="px-3"><p class="font-medium text-slate-800">{{ s.nama }}</p><p class="text-[11px] text-slate-400">NIS {{ s.nis || '-' }} · NISN {{ s.nisn || '-' }}</p></td>
                                        <td class="px-3 text-slate-500"><p>{{ s.kelas_sebelumnya || 'Siswa baru / belum ada riwayat' }}</p><p v-if="s.tahun_sebelumnya" class="text-[11px] text-slate-400">{{ s.tahun_sebelumnya }}</p></td><td class="px-3">{{ s.jenis_kelamin }}</td>
                                        <td class="px-3 text-right"><button type="button" @click="moveStudents([s.id], 'members')" :disabled="form.processing" :aria-label="`Masukkan ${s.nama} ke rombel`" class="p-2 text-primary-600 hover:bg-primary-50 rounded-lg disabled:opacity-30">←</button></td>
                                    </tr>
                                    <tr v-if="!visibleAvailable.length"><td colspan="5" class="px-4 py-12 text-center text-slate-400 whitespace-normal">{{ searchAvailable ? 'Tidak ada siswa yang sesuai pencarian.' : 'Tidak ada siswa aktif yang belum memiliki rombel pada tahun ajaran ini.' }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="px-4 py-3 border-t border-slate-100 text-[11px] text-slate-400">Menampilkan {{ visibleAvailable.length }} dari {{ available.length }} siswa tersedia</div>
                    </section>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-4 flex flex-wrap items-center justify-between gap-3">
                    <div aria-live="polite"><p class="text-sm font-medium">{{ changes.length }} perubahan belum disimpan</p><p class="text-xs text-slate-400 mt-1">{{ message || 'Siswa tersedia belum memiliki rombel pada tahun ajaran yang dipilih. Siswa yang dikeluarkan akan kembali ke panel tersedia.' }}</p></div>
                    <div class="flex gap-2"><button type="button" @click="reset" :disabled="!changes.length || form.processing" class="px-4 py-2 text-sm rounded-lg border border-slate-200 text-slate-600 disabled:opacity-40">Batalkan perubahan</button><button type="button" @click="save" :disabled="!changes.length || form.processing" class="px-4 py-2 text-sm rounded-lg bg-primary-700 text-white font-semibold disabled:opacity-40">{{ form.processing ? 'Menyimpan...' : 'Simpan Rombel' }}</button></div>
                </div>
            </template>
            <p v-else class="bg-white rounded-xl border border-slate-200 p-10 text-center text-sm text-slate-400">Pilih tahun ajaran dan kelas tujuan untuk mengatur anggota rombel.</p>
        </div>
    </AppLayout>
</template>
