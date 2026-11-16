<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    mapelList: { type: Array, default: () => [] },
    tab: { type: String, default: 'siswa' },
    tahunAjaran: { type: Object, default: null },
    tahunAjaranList: { type: Array, default: () => [] },
    kelas: {
        type: Object,
        required: true,
    },
});

const activeTab = ref(props.tab);
const searchSiswa = ref('');

const siswaLaki = computed(() => {
    return (props.kelas.siswa || []).filter(s => s.jenis_kelamin === 'L').length;
});

const siswaPerempuan = computed(() => {
    return (props.kelas.siswa || []).filter(s => s.jenis_kelamin === 'P').length;
});

const sisaKapasitas = computed(() => {
    const total = props.kelas.siswa?.length || 0;
    const kap = props.kelas.kapasitas || 30;
    return Math.max(0, kap - total);
});

const persentaseKapasitas = computed(() => {
    const total = props.kelas.siswa?.length || 0;
    const kap = props.kelas.kapasitas || 30;
    return Math.round((total / kap) * 100);
});

const filteredSiswa = computed(() => {
    const list = props.kelas.siswa || [];
    if (!searchSiswa.value.trim()) return list;
    const q = searchSiswa.value.toLowerCase();
    return list.filter(s =>
        (s.nama && s.nama.toLowerCase().includes(q)) ||
        (s.nis && String(s.nis).includes(q)) ||
        (s.nisn && String(s.nisn).includes(q))
    );
});

const deleteMapel = mapel => {
    if (confirm(`Hapus mata pelajaran "${mapel.nama_mapel}" dari kelas ini?`)) router.delete(`/kelas/${props.kelas.id}/mapel/${mapel.id}`, {preserveScroll: true});
};
const deleteJadwal = jadwal => {
    if (confirm('Hapus jadwal pelajaran ini?')) router.delete(`/kelas/${props.kelas.id}/jadwal/${jadwal.id}`, {preserveScroll: true});
};
const hariOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
const jadwalByHari = computed(() => {
    const groups = {};
    hariOrder.forEach(h => {
        groups[h] = [];
    });
    (props.kelas.jadwal || []).forEach(j => {
        if (!groups[j.hari]) groups[j.hari] = [];
        groups[j.hari].push(j);
    });
    return groups;
});
</script>

<template>
    <AppLayout title="Detail Kelas" pageTitle="Detail Kelas">
        <div class="space-y-4">
            <select :value="tahunAjaran?.id || ''" @change="router.get(`/kelas/${kelas.id}`, {tahun_ajaran_id: $event.target.value, tab: activeTab})" aria-label="Tahun ajaran detail kelas" class="bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm max-w-full"><option value="">Pilih tahun ajaran</option><option v-for="t in tahunAjaranList" :key="t.id" :value="t.id">{{ t.tahun_ajaran }} ({{ t.semester }})</option></select>
            <!-- Navigation & Actions Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <Link href="/kelas" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-800 transition">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Kembali ke Daftar Kelas</span>
                </Link>

                <div class="flex items-center gap-2 flex-wrap">
                    <Link
                        :href="`/absensi?kelas_id=${kelas.id}&tahun_ajaran_id=${tahunAjaran?.id || ''}`"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-medium transition"
                    >
                        <i class="fa-solid fa-clipboard-check text-slate-400 text-[11px]"></i>
                        <span>Presensi</span>
                    </Link>

                    <Link
                        :href="`/nilai?kelas_id=${kelas.id}&tahun_ajaran_id=${tahunAjaran?.id || ''}`"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-medium transition"
                    >
                        <i class="fa-solid fa-award text-slate-400 text-[11px]"></i>
                        <span>Input Nilai</span>
                    </Link>

                    <Link
                        :href="`/rapor?kelas_id=${kelas.id}&tahun_ajaran_id=${tahunAjaran?.id || ''}`"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-medium transition"
                    >
                        <i class="fa-solid fa-file-invoice text-slate-400 text-[11px]"></i>
                        <span>Buku Rapor</span>
                    </Link>

                    <Link
                        :href="`/kelas/${kelas.id}/edit`"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary-700 hover:bg-primary-800 text-white text-xs font-medium transition shadow-2xs"
                    >
                        <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                        <span>Edit Kelas</span>
                    </Link>
                </div>
            </div>

            <!-- Class Hero Card -->
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-2xs">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-xl bg-primary-700 text-white flex items-center justify-center font-bold text-lg shrink-0">
                            {{ kelas.tingkat }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-base sm:text-lg font-bold text-slate-900">{{ kelas.nama_kelas }}</h2>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                    Tingkat {{ kelas.tingkat }}
                                </span>
                                <span
                                    class="px-2 py-0.5 rounded text-[10px] font-semibold"
                                    :class="(kelas.siswa?.length || 0) >= (kelas.kapasitas || 30) ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-emerald-50 text-emerald-800 border border-emerald-200'"
                                >
                                    {{ (kelas.siswa?.length || 0) >= (kelas.kapasitas || 30) ? 'Penuh' : 'Tersedia' }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Tahun Ajaran {{ kelas.tahun_ajaran?.tahun_ajaran || '-' }} &bull; Semester {{ kelas.tahun_ajaran?.semester || '-' }}
                            </p>
                        </div>
                    </div>

                    <!-- Wali Kelas Profile Badge -->
                    <div class="flex items-center gap-3 p-2.5 rounded-lg bg-slate-50 border border-slate-200 shrink-0">
                        <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-700 font-bold text-xs flex items-center justify-center shrink-0">
                            {{ (kelas.guru?.nama || 'W').charAt(0).toUpperCase() }}
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-semibold text-slate-400 block">Wali Kelas</span>
                            <p class="text-xs font-semibold text-slate-800 leading-none">
                                {{ kelas.guru?.nama || 'Belum Ditugaskan' }}
                            </p>
                            <p class="text-[11px] text-slate-500 font-mono mt-0.5">
                                {{ kelas.guru?.nip ? `NIP. ${kelas.guru.nip}` : (kelas.guru?.telepon || '-') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Capacity Progress Bar -->
                <div class="mt-4 pt-3 border-t border-slate-100 space-y-1.5">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500">Kapasitas Ruang Belajar</span>
                        <span class="font-mono font-medium text-slate-700">
                            {{ kelas.siswa?.length || 0 }} / {{ kelas.kapasitas || 30 }} Siswa ({{ persentaseKapasitas }}%)
                        </span>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div
                            class="h-full rounded-full transition-all"
                            :class="persentaseKapasitas >= 100 ? 'bg-amber-600' : 'bg-primary-700'"
                            :style="{ width: `${Math.min(100, persentaseKapasitas)}%` }"
                        ></div>
                    </div>
                </div>
            </div>

            <!-- 4 Quick Stats -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="bg-white rounded-xl p-3.5 border border-slate-200 shadow-2xs">
                    <span class="text-[11px] text-slate-500 block font-medium">Total Siswa</span>
                    <span class="text-xl font-bold text-slate-900 font-mono">{{ kelas.siswa?.length || 0 }}</span>
                    <span class="text-[10px] text-slate-400 block mt-0.5">Peserta Didik</span>
                </div>

                <div class="bg-white rounded-xl p-3.5 border border-slate-200 shadow-2xs">
                    <span class="text-[11px] text-slate-500 block font-medium">Laki-laki / Perempuan</span>
                    <span class="text-xl font-bold text-slate-900 font-mono">{{ siswaLaki }} L &bull; {{ siswaPerempuan }} P</span>
                    <span class="text-[10px] text-slate-400 block mt-0.5">Komposisi Gender</span>
                </div>

                <div class="bg-white rounded-xl p-3.5 border border-slate-200 shadow-2xs">
                    <span class="text-[11px] text-slate-500 block font-medium">Kursi Tersedia</span>
                    <span class="text-xl font-bold text-slate-900 font-mono">{{ sisaKapasitas }}</span>
                    <span class="text-[10px] text-slate-400 block mt-0.5">Sisa Kuota Kelas</span>
                </div>

                <div class="bg-white rounded-xl p-3.5 border border-slate-200 shadow-2xs">
                    <span class="text-[11px] text-slate-500 block font-medium">Jadwal Pelajaran</span>
                    <span class="text-xl font-bold text-slate-900 font-mono">{{ kelas.jadwal?.length || 0 }}</span>
                    <span class="text-[10px] text-slate-400 block mt-0.5">Sesi Mingguan</span>
                </div>
            </div>

            <!-- Tab Navigation & Content Container -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
                <!-- Tab Headers -->
                <div class="px-4 border-b border-slate-200 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-1 -mb-px">
                        <button
                            type="button"
                            @click="activeTab = 'siswa'"
                            class="px-3.5 py-3 text-xs font-semibold border-b-2 transition cursor-pointer flex items-center gap-2"
                            :class="activeTab === 'siswa'
                                ? 'border-primary-700 text-primary-700'
                                : 'border-transparent text-slate-500 hover:text-slate-800'"
                        >
                            <i class="fa-solid fa-users text-xs"></i>
                            <span>Daftar Siswa ({{ kelas.siswa?.length || 0 }})</span>
                        </button>

                        <button type="button" @click="activeTab = 'mapel'" class="px-3.5 py-3 text-xs font-semibold border-b-2 transition flex items-center gap-2" :class="activeTab === 'mapel' ? 'border-primary-700 text-primary-700' : 'border-transparent text-slate-500 hover:text-slate-800'">
                            <i aria-hidden="true" class="fa-solid fa-book-open"></i><span>Mata Pelajaran ({{ mapelList.length }})</span>
                        </button>

                        <button
                            type="button"
                            @click="activeTab = 'jadwal'"
                            class="px-3.5 py-3 text-xs font-semibold border-b-2 transition cursor-pointer flex items-center gap-2"
                            :class="activeTab === 'jadwal'
                                ? 'border-primary-700 text-primary-700'
                                : 'border-transparent text-slate-500 hover:text-slate-800'"
                        >
                            <i class="fa-solid fa-calendar-days text-xs"></i>
                            <span>Jadwal Pelajaran ({{ kelas.jadwal?.length || 0 }})</span>
                        </button>
                    </div>

                    <!-- Right Tab Actions -->
                    <div v-if="activeTab === 'siswa'" class="flex items-center gap-2 py-2">
                        <input
                            v-model="searchSiswa"
                            type="text"
                            placeholder="Cari siswa..."
                            class="hidden sm:inline-block px-2.5 py-1 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:border-primary-600 outline-none w-36"
                        />
                        <Link
                            :href="`/rombel?kelas_id=${kelas.id}&tahun_ajaran_id=${tahunAjaran?.id || ''}`"
                            class="inline-flex items-center gap-1 bg-primary-700 hover:bg-primary-800 text-white font-medium text-xs px-2.5 py-1 rounded-md transition"
                        >
                            <i class="fa-solid fa-plus text-[10px]"></i>
                            <span>Atur Anggota Rombel</span>
                        </Link>
                    </div>

                    <div v-else-if="activeTab === 'mapel' && tahunAjaran" class="py-2">
                        <Link :href="`/kelas/${kelas.id}/mapel/create?tahun_ajaran_id=${tahunAjaran.id}`" class="inline-flex items-center gap-1 bg-primary-700 text-white font-medium text-xs px-3 py-2 rounded-lg">+ Tambah Mata Pelajaran</Link>
                    </div>
                    <div v-else-if="tahunAjaran" class="py-2">
                        <Link
                            :href="`/kelas/${kelas.id}/jadwal/create?tahun_ajaran_id=${tahunAjaran?.id || ''}`"
                            class="inline-flex items-center gap-1 bg-primary-700 hover:bg-primary-800 text-white font-medium text-xs px-2.5 py-1 rounded-md transition"
                        >
                            <i class="fa-solid fa-plus text-[10px]"></i>
                            <span>Tambah Jadwal</span>
                        </Link>
                    </div>
                </div>

                <!-- Tab 1: Daftar Siswa -->
                <div v-if="activeTab === 'siswa'">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead class="bg-slate-50 text-slate-600 text-[11px] font-semibold border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-2.5 w-10 text-center">No</th>
                                    <th class="px-4 py-2.5">Siswa</th>
                                    <th class="px-3 py-2.5 text-center">L/P</th>
                                    <th class="px-4 py-2.5">Orang Tua / Wali</th>
                                    <th class="px-3 py-2.5">Status</th>
                                    <th class="px-4 py-2.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr v-for="(s, index) in filteredSiswa" :key="s.id" class="hover:bg-slate-50/60 transition-colors">
                                    <td class="px-4 py-2.5 text-center font-mono text-slate-400">{{ index + 1 }}</td>
                                    <td class="px-4 py-2.5">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs shrink-0 border border-slate-200 overflow-hidden">
                                                <img v-if="s.foto" :src="`/storage/${s.foto}`" class="w-full h-full object-cover" />
                                                <span v-else>{{ (s.nama || 'S').charAt(0).toUpperCase() }}</span>
                                            </div>
                                            <div>
                                                <Link :href="`/siswa/${s.id}`" class="font-semibold text-slate-900 hover:text-primary-700">
                                                    {{ s.nama }}
                                                </Link>
                                                <p class="text-[11px] text-slate-400 font-mono">NIS: {{ s.nis }} &bull; NISN: {{ s.nisn || '-' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2.5 text-center">
                                        <span class="px-1.5 py-0.5 rounded text-[11px]" :class="s.jenis_kelamin === 'L' ? 'bg-blue-50 text-blue-700' : 'bg-rose-50 text-rose-700'">
                                            {{ s.jenis_kelamin }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2.5 text-slate-600">
                                        <div>
                                            <span>{{ s.nama_wali || s.nama_ayah || '-' }}</span>
                                            <span v-if="s.telepon" class="text-[11px] text-slate-400 block font-mono">{{ s.telepon }}</span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-800 capitalize">
                                            {{ s.status || 'Aktif' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2.5 text-right">
                                        <div class="inline-flex items-center gap-1">
                                            <Link
                                                :href="`/siswa/${s.id}`"
                                                class="px-2 py-1 rounded text-slate-600 hover:text-slate-900 hover:bg-slate-100 text-xs font-medium transition"
                                                title="Buku Induk Siswa"
                                            >
                                                Profil
                                            </Link>
                                            <Link
                                                :href="`/rapor/${s.id}?tahun_ajaran_id=${tahunAjaran?.id || ''}`"
                                                class="px-2 py-1 rounded text-slate-600 hover:text-primary-700 hover:bg-primary-50 text-xs font-medium transition"
                                                title="Cetak Rapor"
                                            >
                                                Rapor
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!filteredSiswa || filteredSiswa.length === 0">
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-400 text-xs">
                                        {{ searchSiswa ? 'Tidak ada siswa yang sesuai pencarian.' : 'Belum ada siswa terdaftar di kelas ini.' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div v-else-if="activeTab === 'mapel'" class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 border-b border-slate-200"><tr><th class="px-4">Kode</th><th class="px-4">Mata Pelajaran</th><th class="px-4">KKM</th><th class="px-4">Guru Pengampu</th><th class="px-4">Status</th><th class="px-4 text-right">Aksi</th></tr></thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="m in mapelList" :key="m.id"><td class="px-4 font-mono">{{ m.kode_mapel }}</td><td class="px-4 font-medium">{{ m.nama_mapel }}</td><td class="px-4">{{ m.kkm }}</td><td class="px-4">{{ m.guru?.nama || '-' }}</td><td class="px-4">{{ m.status_aktif ? 'Aktif' : 'Nonaktif' }}</td><td class="px-4"><div class="flex gap-3 justify-end"><Link :href="`/kelas/${kelas.id}/mapel/${m.id}/edit`" class="text-primary-600">Edit</Link><button type="button" @click="deleteMapel(m)" class="text-rose-600">Hapus</button></div></td></tr>
                            <tr v-if="!mapelList.length"><td colspan="6" class="px-4 py-10 text-center text-slate-400">Belum ada mata pelajaran untuk kelas dan periode ini.</td></tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tab 2: Jadwal Pelajaran Mingguan -->
                <div v-else-if="activeTab === 'jadwal'" class="p-4">
                    <div v-if="kelas.jadwal && kelas.jadwal.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        <div
                            v-for="hari in hariOrder"
                            :key="hari"
                            class="rounded-lg border border-slate-200 bg-slate-50/50 overflow-hidden"
                        >
                            <div class="px-3 py-2 bg-slate-100 border-b border-slate-200 flex items-center justify-between">
                                <span class="font-bold text-xs text-slate-800">{{ hari }}</span>
                                <span class="text-[10px] text-slate-500 font-mono">
                                    {{ (jadwalByHari[hari] || []).length }} Mapel
                                </span>
                            </div>

                            <div class="p-2.5 space-y-2">
                                <div
                                    v-for="j in jadwalByHari[hari]"
                                    :key="j.id"
                                    class="p-2.5 rounded-md bg-white border border-slate-200 shadow-2xs space-y-1"
                                >
                                    <div class="flex items-center justify-between">
                                        <span class="font-semibold text-xs text-slate-900">
                                            {{ j.mata_pelajaran?.nama_mapel }}
                                        </span>
                                        <span class="text-[10px] font-mono text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">
                                            {{ j.jam_mulai }} - {{ j.jam_selesai }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between text-[11px] text-slate-500">
                                        <span>{{ j.guru?.nama || 'Guru Mapel' }}</span>
                                        <span class="font-mono text-[10px] text-slate-400">KKM: {{ j.mata_pelajaran?.kkm ?? '-' }}</span>
                                    </div>
                                    <div class="flex items-center justify-end gap-3 pt-2 text-xs"><Link :href="`/kelas/${kelas.id}/jadwal/${j.id}/edit`" class="text-primary-600">Edit</Link><button type="button" @click="deleteJadwal(j)" class="text-rose-600">Hapus</button></div>
                                </div>

                                <div v-if="!jadwalByHari[hari] || jadwalByHari[hari].length === 0" class="py-4 text-center text-[11px] text-slate-400">
                                    Tidak ada jadwal
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else class="text-center py-10 text-slate-400 text-xs space-y-2">
                        <i class="fa-solid fa-calendar-xmark text-2xl text-slate-300 block"></i>
                        <p>Belum ada jadwal pelajaran untuk kelas ini.</p>
                        <Link
                            :href="`/kelas/${kelas.id}/jadwal/create?tahun_ajaran_id=${tahunAjaran?.id || ''}`"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary-700 hover:bg-primary-800 text-white text-xs font-medium transition"
                        >
                            <i class="fa-solid fa-plus text-[10px]"></i>
                            <span>Buat Jadwal Pertama</span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
