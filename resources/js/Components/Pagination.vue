<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { compactPaginationLinks } from '@/Utils/pagination';

const props = defineProps({
    links: {
        type: Array,
        required: true,
    },
});
const visibleLinks = computed(() => compactPaginationLinks(props.links));
</script>

<template>
    <div v-if="links && links.length > 3" class="flex flex-col sm:flex-row items-center justify-between gap-3 px-4 py-3 bg-white border-t border-slate-200 text-xs text-slate-600">
        <div class="text-slate-500 font-medium">
            Navigasi Halaman Data
        </div>
        <div class="inline-flex items-center -space-x-px rounded-lg shadow-2xs isolate">
            <template v-for="(link, index) in visibleLinks" :key="index">
                <div
                    v-if="!link.url"
                    :aria-label="index === 0 ? 'Halaman sebelumnya' : index === visibleLinks.length - 1 ? 'Halaman berikutnya' : undefined"
                    aria-disabled="true"
                    class="relative inline-flex items-center px-3 py-1.5 text-xs font-medium text-slate-400 bg-slate-50 border border-slate-200 select-none first:rounded-l-lg last:rounded-r-lg"
                >
                    <i v-if="index === 0" class="fa-solid fa-chevron-left text-[10px]" aria-hidden="true"></i>
                    <i v-else-if="index === visibleLinks.length - 1" class="fa-solid fa-chevron-right text-[10px]" aria-hidden="true"></i>
                    <span v-else>{{ link.label }}</span>
                </div>
                <Link
                    v-else
                    :href="link.url"
                    :aria-label="index === 0 ? 'Halaman sebelumnya' : index === visibleLinks.length - 1 ? 'Halaman berikutnya' : `Halaman ${link.label}`"
                    :title="index === 0 ? 'Halaman sebelumnya' : index === visibleLinks.length - 1 ? 'Halaman berikutnya' : undefined"
                    :aria-current="link.active ? 'page' : undefined"
                    preserve-scroll
                    class="relative inline-flex items-center px-3 py-1.5 text-xs font-semibold border transition-colors first:rounded-l-lg last:rounded-r-lg focus:z-10"
                    :class="link.active 
                        ? 'z-10 bg-primary-700 border-primary-700 text-white shadow-2xs' 
                        : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i v-if="index === 0" class="fa-solid fa-chevron-left text-[10px]" aria-hidden="true"></i>
                    <i v-else-if="index === visibleLinks.length - 1" class="fa-solid fa-chevron-right text-[10px]" aria-hidden="true"></i>
                    <span v-else>{{ link.label }}</span>
                </Link>
            </template>
        </div>
    </div>
</template>
