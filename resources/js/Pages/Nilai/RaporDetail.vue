<script setup>
import { ref, computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import KopSuratModal from '@/Components/KopSuratModal.vue';
import { judulKopRapor } from '@/Utils/kopRapor';

const props = defineProps({
    siswa: {
        type: Object,
        required: true,
    },
    ekstrakurikuler: { type: Array, default: () => [] },
    nilai: {
        type: Array,
        default: () => [],
    },
    tahunAjaran: {
        type: Object,
        default: () => null,
    },
    rekapAbsensi: {
        type: Object,
        default: () => ({ hadir: 0, sakit: 0, izin: 0, alpa: 0 }),
    },
});

const page = usePage();
const profil = computed(() => page.props.profil_sekolah || {});
const showKopModal = ref(false);

const formattedAddress = computed(() => {
    const parts = [
        profil.value.alamat,
        profil.value.kelurahan ? `Kel. ${profil.value.kelurahan}` : null,
        profil.value.kecamatan ? `Kec. ${profil.value.kecamatan}` : null,
        profil.value.kabupaten,
        profil.value.provinsi,
        profil.value.kode_pos ? `Kode Pos ${profil.value.kode_pos}` : null,
    ].filter(Boolean);
    return parts.join(', ');
});

const faseSiswa = computed(() => {
    const t = Number(props.siswa?.kelas?.tingkat || 1);
    if (t <= 2) return 'A';
    if (t <= 4) return 'B';
    return 'C';
});

const scoredNilai = computed(() => props.nilai.filter(n => n.nilai_akhir !== null));
const totalNilai = computed(() => scoredNilai.value.reduce((acc, curr) => acc + Number(curr.nilai_akhir), 0));

const rataRataNilai = computed(() => {
    if (!scoredNilai.value.length) return '-';
    return (totalNilai.value / scoredNilai.value.length).toFixed(1);
});

const catatanWaliKelas = computed(() => {
    if (!scoredNilai.value.length) return 'Belum ada nilai untuk menyusun catatan hasil belajar.';
    const avg = parseFloat(rataRataNilai.value);
    if (avg >= 85) {
        return `Ananda ${props.siswa.nama} menunjukkan pencapaian kompetensi yang sangat baik di seluruh mata pelajaran. Pertahankan ketekunan, kedisiplinan, dan budi pekerti luhur dalam belajar.`;
    } else if (avg >= 70) {
        return `Ananda ${props.siswa.nama} menunjukkan kemajuan belajar yang baik dan mampu menyelesaikan tugas-tugas dengan tertib. Terus tingkatkan keaktifan belajar pada semester berikutnya.`;
    } else {
        return `Ananda ${props.siswa.nama} perlu meningkatkan disiplin dan ketekunan belajar, khususnya pada materi yang belum tuntas. Diharapkan bimbingan lebih lanjut dari orang tua di rumah.`;
    }
});

const tanggalRaporFormatted = computed(() => {
    const kota = profil.value.kabupaten || 'Tempat';
    const d = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    return `${kota}, ${d}`;
});

const print = () => {
    window.print();
};
</script>

<template>
    <AppLayout title="Cetak Rapor" pageTitle="Cetak Rapor">
        <div class="max-w-4xl mx-auto space-y-4">
            <!-- Action Bar (Hidden in print) -->
            <div class="flex flex-wrap gap-3 items-center justify-between print:hidden">
                <Link href="/rapor" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-900 transition">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Kembali</span>
                </Link>

                <div class="flex flex-wrap items-center gap-2">
                    <Link :href="`/nilai?kelas_id=${siswa.kelas_id}&tahun_ajaran_id=${tahunAjaran?.id || ''}`" class="px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-700 text-xs font-medium">Input Nilai</Link>
                    <button
                        type="button"
                        @click="showKopModal = true"
                        class="px-3 py-1.5 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-medium transition flex items-center gap-1.5 cursor-pointer"
                    >
                        <i class="fa-solid fa-heading text-slate-400 text-[11px]"></i>
                        <span>Kop Surat</span>
                    </button>

                    <button
                        type="button"
                        @click="print"
                        class="px-3 py-1.5 rounded-lg bg-primary-700 hover:bg-primary-800 text-white text-xs font-semibold shadow-2xs transition cursor-pointer flex items-center gap-1.5"
                    >
                        <i class="fa-solid fa-print text-[11px]"></i>
                        <span>Cetak Rapor</span>
                    </button>
                </div>
            </div>

            <!-- Authentic Indonesian Academic Report Card Sheet -->
            <div class="bg-white p-8 sm:p-12 border border-slate-200 shadow-sm print:border-none print:shadow-none print:p-0 print:m-0 text-slate-900 leading-normal">
                <!-- Kop Surat Resmi -->
                <div class="pb-2 mb-4">
                    <div class="flex items-center justify-between gap-4 text-center">
                        <!-- Logo Kiri -->
                        <div class="w-20 h-20 flex items-center justify-center shrink-0">
                            <img
                                src="/storage/sekolah/logo.png"
                                alt="Logo Instansi"
                                class="max-h-20 max-w-20 object-contain"
                            />
                        </div>

                        <!-- Teks Badan Kop Surat -->
                        <div class="flex-1 px-2">
                            <h4 class="text-xs sm:text-sm font-bold uppercase tracking-wider leading-tight">
                                {{ judulKopRapor(profil.kabupaten) }}
                            </h4>
                            <h2 class="text-base sm:text-xl font-black uppercase tracking-wide mt-0.5">
                                {{ profil.nama_sekolah || 'SEKOLAH DASAR' }}
                            </h2>
                            <p class="text-[11px] sm:text-xs text-slate-600 mt-1 leading-snug">
                                {{ formattedAddress || 'Alamat Lengkap Satuan Pendidikan' }}
                            </p>
                            <p class="text-[11px] text-slate-500 font-mono mt-0.5">
                                <span v-if="profil.telepon">Telp: {{ profil.telepon }}</span>
                                <span v-if="profil.email"> &bull; Pos-el: {{ profil.email }}</span>
                                <span v-if="profil.website"> &bull; Website: {{ profil.website }}</span>
                            </p>
                            <p class="text-[11px] text-slate-700 font-mono font-semibold mt-0.5">
                                NPSN: {{ profil.npsn || '-' }} &bull; NSS: {{ profil.nss || '-' }} &bull; AKREDITASI: {{ profil.akreditasi || 'A' }}
                            </p>
                        </div>

                        <!-- Logo Kanan -->
                        <div class="w-20 h-20 flex items-center justify-center shrink-0">
                            <img
                                src="/storage/sekolah/logo-sekolah.png"
                                alt="Logo Sekolah"
                                class="max-h-20 max-w-20 object-contain"
                            />
                        </div>
                    </div>

                    <!-- Garis Pembatas Kop Surat -->
                    <div v-if="profil.tipe_garis_kop !== 'tunggal'" class="mt-3">
                        <div class="border-b-[3px] border-black"></div>
                        <div class="border-b-[1px] border-black mt-[2px]"></div>
                    </div>
                    <div v-else class="mt-3 border-b-2 border-black"></div>
                </div>

                <!-- Judul Dokumen Rapor Resmi -->
                <div class="text-center my-4">
                    <h3 class="text-sm sm:text-base font-bold uppercase tracking-wide">
                        LAPORAN HASIL BELAJAR (RAPOR)
                    </h3>
                    <h4 class="text-xs sm:text-sm font-semibold uppercase">
                        SEKOLAH DASAR (SD)
                    </h4>
                </div>

                <!-- Identitas Peserta Didik (Format Standar 2 Kolom) -->
                <div class="mb-5 text-xs">
                    <table class="w-full border-collapse">
                        <tbody>
                            <tr>
                                <td class="w-32 py-0.5">Nama Peserta Didik</td>
                                <td class="w-3 py-0.5">:</td>
                                <td class="font-bold py-0.5">{{ siswa.nama }}</td>
                                <td class="w-28 py-0.5">Kelas</td>
                                <td class="w-3 py-0.5">:</td>
                                <td class="font-medium py-0.5">{{ siswa.kelas?.nama_kelas || '-' }}</td>
                            </tr>
                            <tr>
                                <td class="py-0.5">NISN / NIS</td>
                                <td class="py-0.5">:</td>
                                <td class="font-mono py-0.5">{{ siswa.nisn || '-' }} / {{ siswa.nis }}</td>
                                <td class="py-0.5">Fase</td>
                                <td class="py-0.5">:</td>
                                <td class="font-medium py-0.5">{{ faseSiswa }}</td>
                            </tr>
                            <tr>
                                <td class="py-0.5">Nama Sekolah</td>
                                <td class="py-0.5">:</td>
                                <td class="py-0.5">{{ profil.nama_sekolah || '-' }}</td>
                                <td class="py-0.5">Semester</td>
                                <td class="py-0.5">:</td>
                                <td class="py-0.5">
                                    {{ tahunAjaran?.semester == 1 ? '1 (Ganjil)' : (tahunAjaran?.semester == 2 ? '2 (Genap)' : (tahunAjaran?.semester || '-')) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="py-0.5">Alamat Sekolah</td>
                                <td class="py-0.5">:</td>
                                <td class="py-0.5">{{ formattedAddress || '-' }}</td>
                                <td class="py-0.5">Tahun Ajaran</td>
                                <td class="py-0.5">:</td>
                                <td class="py-0.5">{{ tahunAjaran?.tahun_ajaran || '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- A. Capaian Kompetensi / Muatan Pelajaran -->
                <div class="mb-5">
                    <h4 class="text-xs font-bold uppercase tracking-wider mb-1.5">
                        A. Capaian Hasil Belajar
                    </h4>
                    <table class="w-full text-left text-xs border border-black border-collapse">
                        <thead>
                            <tr class="bg-slate-100 font-bold uppercase text-[11px] text-center">
                                <th class="border border-black py-2 px-2 w-10">No</th>
                                <th class="border border-black py-2 px-3 text-left">Muatan Pelajaran</th>
                                <th class="border border-black py-2 px-2 w-14">KKM</th>
                                <th class="border border-black py-2 px-2 w-16">Nilai Akhir</th>
                                <th class="border border-black py-2 px-2 w-16">Predikat</th>
                                <th class="border border-black py-2 px-3 text-left">Capaian Kompetensi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(n, idx) in nilai" :key="n.id">
                                <td class="border border-black py-1.5 px-2 text-center font-mono">{{ idx + 1 }}</td>
                                <td class="border border-black py-1.5 px-3 font-medium">{{ n.mata_pelajaran?.nama_mapel }}</td>
                                <td class="border border-black py-1.5 px-2 text-center font-mono">{{ n.mata_pelajaran?.kkm || 70 }}</td>
                                <td class="border border-black py-1.5 px-2 text-center font-bold font-mono">{{ n.nilai_akhir ?? '-' }}</td>
                                <td class="border border-black py-1.5 px-2 text-center font-bold font-mono">{{ n.predikat || '-' }}</td>
                                <td class="border border-black py-1.5 px-3 text-[11px] leading-snug">
                                    <span class="whitespace-pre-line">{{ n.capaian_kompetensi || 'Belum diisi.' }}</span>
                                </td>
                            </tr>
                            <tr v-if="nilai.length === 0">
                                <td colspan="6" class="border border-black py-6 text-center text-slate-400 italic">
                                    Belum ada data nilai tercatat pada periode ini.
                                </td>
                            </tr>
                            <tr v-if="nilai.length > 0" class="font-bold bg-slate-50">
                                <td colspan="3" class="border border-black py-1.5 px-3 text-right">Jumlah Nilai</td>
                                <td class="border border-black py-1.5 px-2 text-center font-mono">{{ scoredNilai.length ? totalNilai : '-' }}</td>
                                <td colspan="2" class="border border-black py-1.5 px-3 text-slate-400"></td>
                            </tr>
                            <tr v-if="nilai.length > 0" class="font-bold bg-slate-50">
                                <td colspan="3" class="border border-black py-1.5 px-3 text-right">Rata-rata</td>
                                <td class="border border-black py-1.5 px-2 text-center font-mono">{{ rataRataNilai }}</td>
                                <td colspan="2" class="border border-black py-1.5 px-3 text-slate-400"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- B. Ekstrakurikuler -->
                <div class="mb-5">
                    <h4 class="text-xs font-bold uppercase tracking-wider mb-1.5">
                        B. Kegiatan Ekstrakurikuler
                    </h4>
                    <table class="w-full text-left text-xs border border-black border-collapse">
                        <thead>
                            <tr class="bg-slate-100 font-bold uppercase text-[11px] text-center">
                                <th class="border border-black py-1.5 px-2 w-10">No</th>
                                <th class="border border-black py-1.5 px-3 text-left w-64">Kegiatan Ekstrakurikuler</th>
                                <th class="border border-black py-1.5 px-2 w-20">Predikat</th>
                                <th class="border border-black py-1.5 px-3 text-left">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, idx) in ekstrakurikuler" :key="item.id">
                                <td class="border border-black py-1.5 px-2 text-center font-mono">{{ idx + 1 }}</td>
                                <td class="border border-black py-1.5 px-3 font-medium">{{ item.kegiatan }}</td>
                                <td class="border border-black py-1.5 px-2 text-center font-medium">{{ item.predikat }}</td>
                                <td class="border border-black py-1.5 px-3 text-[11px] whitespace-pre-line">{{ item.keterangan || '-' }}</td>
                            </tr>
                            <tr v-if="!ekstrakurikuler.length"><td colspan="4" class="border border-black py-4 text-center text-slate-500 italic">Belum ada kegiatan ekstrakurikuler yang diisi.</td></tr>
                        </tbody>
                    </table>
                </div>

                <!-- C. Ketidakhadiran (Tabel Standar Rapor) -->
                <div class="mb-5">
                    <h4 class="text-xs font-bold uppercase tracking-wider mb-1.5">
                        C. Ketidakhadiran
                    </h4>
                    <table class="w-64 border border-black border-collapse text-xs">
                        <tbody>
                            <tr>
                                <td class="border border-black px-3 py-1">Sakit</td>
                                <td class="border border-black px-3 py-1 w-24 text-center font-mono">{{ rekapAbsensi.sakit }} hari</td>
                            </tr>
                            <tr>
                                <td class="border border-black px-3 py-1">Izin</td>
                                <td class="border border-black px-3 py-1 w-24 text-center font-mono">{{ rekapAbsensi.izin }} hari</td>
                            </tr>
                            <tr>
                                <td class="border border-black px-3 py-1">Tanpa Keterangan</td>
                                <td class="border border-black px-3 py-1 w-24 text-center font-mono">{{ rekapAbsensi.alpa }} hari</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- D. Catatan Wali Kelas -->
                <div class="mb-6">
                    <h4 class="text-xs font-bold uppercase tracking-wider mb-1.5">
                        D. Catatan Wali Kelas
                    </h4>
                    <div class="border border-black p-3 text-xs leading-relaxed min-h-[50px]">
                        {{ catatanWaliKelas }}
                    </div>
                </div>

                <!-- Pengesahan & Tanda Tangan Standar Rapor Indonesia -->
                <div class="text-xs mt-6 pt-2">
                    <div class="flex justify-between items-start text-center">
                        <div class="w-56">
                            <p>Mengetahui,</p>
                            <p class="font-bold mt-0.5">Orang Tua / Wali Peserta Didik</p>
                            <div class="h-20"></div>
                            <p class="border-b border-black w-44 mx-auto"></p>
                        </div>

                        <div class="w-56">
                            <p>{{ tanggalRaporFormatted }}</p>
                            <p class="font-bold mt-0.5">Wali Kelas,</p>
                            <div class="h-20"></div>
                            <p class="font-bold border-b border-black w-44 mx-auto pb-0.5">{{ siswa.kelas?.guru?.nama || '........................................' }}</p>
                            <p class="text-[11px] font-mono mt-0.5">NIP. {{ siswa.kelas?.guru?.nip || '-' }}</p>
                        </div>
                    </div>

                    <div class="text-center mt-6">
                        <div class="w-64 mx-auto">
                            <p>Mengetahui,</p>
                            <p class="font-bold mt-0.5">Kepala Satuan Pendidikan</p>
                            <div class="h-20"></div>
                            <p class="font-bold border-b border-black w-52 mx-auto pb-0.5">{{ profil.kepala_sekolah || '........................................' }}</p>
                            <p class="text-[11px] font-mono mt-0.5">NIP. {{ profil.nip_kepala_sekolah || '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Kop Surat -->
        <KopSuratModal :show="showKopModal" @close="showKopModal = false" />
    </AppLayout>
</template>
