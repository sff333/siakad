<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { usePageAccess } from '@/Composables/usePageAccess';
const { canOpen } = usePageAccess();

defineProps({
    pageTitle: {
        type: String,
        default: 'Dashboard',
    },
});

const emit = defineEmits(['toggleMobile']);

const page = usePage();
const user = computed(() => page.props.auth?.user || { name: 'Admin', role: 'admin' });

const userMenuOpen = ref(false);
const dropdownRef = ref(null);

const handleClickOutside = (e) => {
    if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
        userMenuOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});
onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});

const todayFormatted = computed(() => {
    const now = new Date();
    return new Intl.DateTimeFormat('id-ID', {
        weekday: 'short',
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    }).format(now);
});

const handleLogout = () => {
    router.post('/logout');
};
</script>

<template>
    <header class="learning-navbar bg-white/90 backdrop-blur-md border-b border-slate-200/70 sticky top-0 z-30 select-none">
        <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 py-4">
            <!-- Left: Mobile Toggle & Page Title -->
            <div class="flex items-center gap-3">
                <button
                    @click="emit('toggleMobile')"
                    class="lg:hidden text-slate-500 hover:text-slate-800 p-1.5 rounded-lg hover:bg-slate-100 transition cursor-pointer"
                    type="button"
                    aria-label="Menu"
                >
                    <i class="fa-solid fa-bars text-sm"></i>
                </button>

                <div><h1 class="text-sm sm:text-base font-semibold text-slate-800 leading-none">
                    {{ pageTitle }}
                </h1></div>
            </div>

            <!-- Right: Status Indicators & User Profile Dropdown -->
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Date Indicator -->
                <div class="hidden md:flex items-center gap-1.5 px-2.5 py-1 rounded-md text-slate-500 text-xs font-medium">
                    <i class="fa-regular fa-calendar text-[11px]"></i>
                    <span>{{ todayFormatted }}</span>
                </div>

                <Link v-if="canOpen('/pengumuman')" href="/pengumuman" aria-label="Lihat pengumuman" class="w-9 h-9 rounded-xl border border-slate-200 text-slate-500 hover:text-primary-600 flex items-center justify-center"><i class="fa-regular fa-bell"></i></Link>

                <!-- User Profile Dropdown -->
                <div class="relative" ref="dropdownRef">
                    <button
                        @click.stop="userMenuOpen = !userMenuOpen"
                        :aria-expanded="userMenuOpen"
                        aria-label="Menu akun"
                        @keydown.esc="userMenuOpen = false"
                        type="button"
                        class="flex items-center gap-2 p-1 sm:px-2 sm:py-1 rounded-lg hover:bg-slate-100 border border-transparent hover:border-slate-200 transition cursor-pointer"
                    >
                        <div class="w-7 h-7 rounded-full bg-primary-700 text-white font-bold text-xs flex items-center justify-center shrink-0">
                            {{ (user.name || 'A').charAt(0).toUpperCase() }}
                        </div>
                        <span class="hidden sm:inline text-xs font-medium text-slate-700 leading-none">{{ user.name }}</span>
                        <i class="fa-solid fa-chevron-down text-[9px] text-slate-400 transition-transform hidden sm:inline" :class="{ 'rotate-180': userMenuOpen }"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div
                        v-if="userMenuOpen"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-200 py-1 z-50 text-xs"
                    >
                        <div class="px-3 py-2 border-b border-slate-100">
                            <p class="font-medium text-slate-900 truncate">{{ user.name }}</p>
                            <p class="text-[11px] text-slate-500 truncate">{{ user.email }}</p>
                        </div>

                        <div class="py-1">
                            <Link
                                v-if="canOpen('/profil-sekolah')"
                                href="/profil-sekolah"
                                @click="userMenuOpen = false"
                                class="flex items-center gap-2 px-3 py-1.5 text-slate-700 hover:bg-slate-50 transition"
                            >
                                <i class="fa-solid fa-school text-slate-400 w-4"></i> Profil Sekolah
                            </Link>
                            <Link
                                v-if="canOpen('/pengumuman')"
                                href="/pengumuman"
                                @click="userMenuOpen = false"
                                class="flex items-center gap-2 px-3 py-1.5 text-slate-700 hover:bg-slate-50 transition"
                            >
                                <i class="fa-solid fa-bullhorn text-slate-400 w-4"></i> Pengumuman
                            </Link>
                        </div>

                        <div class="border-t border-slate-100 pt-1">
                            <button
                                @click="handleLogout"
                                type="button"
                                class="w-full text-left flex items-center gap-2 px-3 py-1.5 text-rose-700 hover:bg-rose-50 font-medium transition cursor-pointer"
                            >
                                <i class="fa-solid fa-right-from-bracket w-4 text-rose-500"></i> Keluar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>
