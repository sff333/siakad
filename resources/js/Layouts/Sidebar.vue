<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { usePageAccess } from '@/Composables/usePageAccess';
const { canOpen } = usePageAccess();

const props = defineProps({
    sidebarOpen: {
        type: Boolean,
        default: true,
    },
    mobileOpen: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['update:sidebarOpen', 'update:mobileOpen']);

const page = usePage();
const currentUrl = computed(() => page.url.split('?')[0]);

const groups = [
    { title: 'Ruang belajar', items: [
        { href: '/dashboard', label: 'Dashboard', icon: 'fa-gauge-high' },
        { href: '/siswa', label: 'Data Siswa', icon: 'fa-user-graduate' },
        { href: '/guru', label: 'Data Guru', icon: 'fa-chalkboard-user' },
        { href: '/kelas', label: 'Kelas', icon: 'fa-school' },
    ] },
    { title: 'Akademik', items: [
        { href: '/absensi', label: 'Presensi', icon: 'fa-clipboard-check' },
        { href: '/absensi/rekap', label: 'Rekap Presensi', icon: 'fa-chart-simple' },
        { href: '/nilai', label: 'Nilai Siswa', icon: 'fa-award' },
        { href: '/rapor', label: 'Rapor', icon: 'fa-file-lines' },
    ] },
    { title: 'Informasi & pengaturan', items: [
        { href: '/pengumuman', label: 'Pengumuman', icon: 'fa-bullhorn' },
        { href: '/tahun-ajaran', label: 'Tahun Ajaran', icon: 'fa-calendar-check' },
        { href: '/profil-sekolah', label: 'Profil Sekolah', icon: 'fa-landmark' },
    ] },
    { title: 'Administrator', adminOnly: true, items: [
        { href: '/pengguna', label: 'Manajemen Pengguna', icon: 'fa-users-gear' },
        { href: '/log-aktivitas', label: 'Log Aktivitas', icon: 'fa-clock-rotate-left' },
    ] },
];
const visibleGroups = computed(() => groups
    .filter(group => !group.adminOnly || page.props.auth?.can_manage_users)
    .map(group => ({ ...group, items: group.items.filter(item => canOpen(item.href)) }))
    .filter(group => group.items.length));
const isUrl = (...urls) => {
    return urls.some((url) => {
        if (url === '/kelas' && currentUrl.value === '/rombel') return true;
        if (url === '/dashboard' || url === '/absensi') {
            return currentUrl.value === url;
        }
        return currentUrl.value.startsWith(url);
    });
};

const profil = computed(() => page.props.profil_sekolah || {});
const user = computed(() => page.props.auth?.user || { name: 'Admin', role: 'admin' });
</script>

<template>
    <div>
        <div v-if="mobileOpen" class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-40 lg:hidden" @click="emit('update:mobileOpen', false)"></div>
        <aside class="learning-sidebar fixed inset-y-0 left-0 z-50 bg-white border-r border-slate-200/70 flex flex-col transition-all duration-200" :class="[sidebarOpen ? 'w-64' : 'w-20', mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0']">
            <div class="flex items-center gap-3 px-5 h-24 shrink-0">
                <div class="w-10 h-10 rounded-2xl bg-primary-600 text-white flex items-center justify-center shrink-0 shadow-lg shadow-primary-200"><i class="fa-solid fa-graduation-cap text-lg"></i></div>
                <div v-if="sidebarOpen" class="min-w-0">
                    <p class="text-sm font-bold leading-snug text-slate-900">Sistem Informasi<br />Akademik</p>
                    <p class="text-[10px] text-slate-400 truncate mt-0.5">{{ profil.nama_sekolah || 'Sistem Informasi Akademik' }}</p>
                </div>
            </div>
            <nav aria-label="Navigasi utama" class="flex-1 overflow-y-auto px-3 pb-4 space-y-6">
                <div v-for="group in visibleGroups" :key="group.title">
                    <p v-if="sidebarOpen" class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ group.title }}</p>
                    <div class="space-y-1">
                        <Link v-for="item in group.items" :key="item.href" :href="item.href" :title="item.label" :aria-current="isUrl(item.href) ? 'page' : undefined" @click="emit('update:mobileOpen', false)" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium transition-colors" :class="isUrl(item.href) ? 'bg-primary-600 text-white shadow-md shadow-primary-100' : 'text-slate-500 hover:bg-primary-50 hover:text-primary-700'">
                            <i class="fa-solid w-5 text-center text-sm" :class="item.icon"></i>
                            <span v-if="sidebarOpen">{{ item.label }}</span>
                            <span v-if="sidebarOpen && isUrl(item.href)" class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                        </Link>
                    </div>
                </div>
            </nav>
            <div class="p-3 border-t border-slate-100">
                <div class="flex items-center gap-2 px-1">
                    <div class="w-9 h-9 rounded-xl bg-slate-100 text-primary-600 flex items-center justify-center text-sm font-bold shrink-0">{{ (user.name || 'A').charAt(0).toUpperCase() }}</div>
                    <div v-if="sidebarOpen" class="flex-1 min-w-0"><p class="text-xs font-semibold truncate">{{ user.name }}</p><p class="text-[10px] text-slate-400">{{ user.role_label }}</p></div>
                    <button @click="emit('update:sidebarOpen', !sidebarOpen)" :aria-label="sidebarOpen ? 'Tutup sidebar' : 'Buka sidebar'" class="hidden lg:flex p-2 text-slate-400 hover:text-primary-600 cursor-pointer"><i class="fa-solid text-xs" :class="sidebarOpen ? 'fa-chevron-left' : 'fa-chevron-right'"></i></button>
                    <button @click="emit('update:mobileOpen', false)" aria-label="Tutup menu" class="lg:hidden p-2 text-slate-400"><i class="fa-solid fa-xmark"></i></button>
                </div>
            </div>
        </aside>
    </div>
</template>
