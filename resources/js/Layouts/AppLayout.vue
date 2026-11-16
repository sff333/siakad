<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import Sidebar from './Sidebar.vue';
import Navbar from './Navbar.vue';

const props = defineProps({
    title: {
        type: String,
        default: 'SIAKAD',
    },
    pageTitle: {
        type: String,
        default: 'Dashboard',
    },
});

const sidebarOpen = ref(true);
const mobileOpen = ref(false);

const page = usePage();
const flash = computed(() => page.props.flash || {});
const errors = computed(() => page.props.errors || {});
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const actionError = ref('');
const showActionError = (event) => { actionError.value = event.detail; };
onMounted(() => window.addEventListener('academic-action-error', showActionError));
onUnmounted(() => window.removeEventListener('academic-action-error', showActionError));
watch(() => flash.value.id, () => { actionError.value = ''; });

const dismissFlash = ref({
    success: false,
    error: false,
});
watch(() => flash.value.id, () => {
    dismissFlash.value = { success: false, error: false };
});
</script>

<template>
    <div class="learning-app min-h-screen text-slate-800 flex font-sans antialiased">
        <Head :title="title" />

        <!-- Sidebar Navigation -->
        <Sidebar
            v-model:sidebarOpen="sidebarOpen"
            v-model:mobileOpen="mobileOpen"
        />

        <!-- Main Content Area -->
        <div
            class="app-workspace min-w-0 flex-1 flex flex-col min-h-screen transition-all duration-200"
            :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-20'"
        >
            <!-- Top Navbar -->
            <Navbar
                :pageTitle="pageTitle"
                @toggleMobile="sidebarOpen = true; mobileOpen = !mobileOpen"
            />

            <!-- Page Main Body -->
            <main class="learning-content flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
                <!-- Flash Success Notification -->
                <Teleport to="body">
                <div class="fixed right-4 top-20 z-[100] w-[calc(100%-2rem)] max-w-md print:hidden" aria-live="polite" aria-atomic="true">
                <div v-if="actionError" role="alert" class="mb-4 flex justify-between gap-3 rounded-lg border border-rose-200 bg-rose-50 p-3 text-xs text-rose-800">
                    <span>{{ actionError }}</span>
                    <button type="button" aria-label="Tutup notifikasi kesalahan" @click="actionError = ''">×</button>
                </div>
                <div
                    v-if="flash.success && !dismissFlash.success"
                    role="status"
                    class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-3.5 py-2.5 rounded-lg flex items-center justify-between text-xs font-medium"
                >
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                        <span>{{ flash.success }}</span>
                    </div>
                    <button
                        @click="dismissFlash.success = true"
                        aria-label="Tutup notifikasi berhasil"
                        type="button"
                        class="text-emerald-600 hover:text-emerald-900 p-0.5"
                    >
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </button>
                </div>

                <!-- Flash Error Notification -->
                <div
                    v-if="flash.error && !dismissFlash.error"
                    role="alert"
                    class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 px-3.5 py-2.5 rounded-lg flex items-center justify-between text-xs font-medium"
                >
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                        <span>{{ flash.error }}</span>
                    </div>
                    <button
                        @click="dismissFlash.error = true"
                        aria-label="Tutup notifikasi kesalahan"
                        type="button"
                        class="text-rose-600 hover:text-rose-900 p-0.5"
                    >
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </button>
                </div>
                </div>
                </Teleport>

                <!-- Validation Errors Notification -->
                <div
                    v-if="hasErrors"
                    role="alert"
                    class="mb-4 bg-rose-50 border border-rose-200 text-rose-900 px-3.5 py-2.5 rounded-lg text-xs"
                >
                    <div class="flex items-center gap-2 font-semibold mb-1">
                        <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                        <span>Periksa isian formulir:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-rose-800 pl-1 text-[11px]">
                        <li v-for="(msg, key) in errors" :key="key">{{ msg }}</li>
                    </ul>
                </div>

                <!-- Main Page Slot -->
                <slot />
            </main>

            <!-- Clean Footer -->
            <footer class="border-t border-slate-200/80 py-2.5 px-6 text-slate-400 text-[11px] flex items-center justify-between">
                <span>&copy; {{ new Date().getFullYear() }} {{ page.props.profil_sekolah?.nama_sekolah || 'SIAKAD' }}</span>
                <span>Sistem Informasi Akademik</span>
            </footer>
        </div>
    </div>
</template>
