import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

const appName = 'Sistem Informasi Akademik';

function reportActionError(message) {
    window.dispatchEvent(new CustomEvent('academic-action-error', { detail: message }));
    return false;
}

createInertiaApp({
    defaults: {
        visitOptions: (_url, options) => {
            if (!['post', 'put', 'patch', 'delete'].includes(options.method)) return {};

            return {
                onHttpException: options.onHttpException ?? ((response) => reportActionError(
                    response.status === 419
                        ? 'Sesi telah kedaluwarsa. Muat ulang halaman sebelum mencoba lagi.'
                        : 'Server tidak dapat menyelesaikan aksi. Periksa data sebelum mencoba lagi.',
                )),
                onNetworkError: options.onNetworkError ?? (() => reportActionError(
                    'Koneksi terputus. Periksa apakah perubahan tersimpan sebelum mencoba lagi.',
                )),
            };
        },
    },
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
    progress: {
        color: '#dc2626',
        showSpinner: true,
    },
});
