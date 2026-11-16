import { usePage } from '@inertiajs/vue3';

export function usePageAccess() {
    const page = usePage();
    const canOpen = (url) => {
        if (page.props.auth?.can_manage_users) return true;
        const path = url.split('?')[0];
        if (path === '/dashboard') return true;
        const key = path === '/absensi/rekap' ? 'rekap-presensi'
            : ['/rombel', '/mapel', '/jadwal'].some(prefix => path === prefix || path.startsWith(prefix + '/')) ? 'kelas'
            : path.split('/')[1];
        return page.props.auth?.allowed_pages?.includes(key) ?? false;
    };
    return { canOpen };
}
