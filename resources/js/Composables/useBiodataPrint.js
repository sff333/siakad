import { onBeforeUnmount, ref } from 'vue';

export function useBiodataPrint(resource) {
    const printingId = ref(null);
    const printError = ref('');
    let printFrame = null;
    const removePrintFrame = () => {
        printFrame?.remove();
        printFrame = null;
    };
    const handlePrint = (id, options = {}) => {
        if (printingId.value !== null) return;
        removePrintFrame();
        printError.value = '';
        printingId.value = id;
        const frame = document.createElement('iframe');
        printFrame = frame;
        frame.title = options.title || `Cetak biodata ${resource}`;
        frame.style.cssText = 'position:fixed;left:-10000px;top:0;width:794px;height:1123px;border:0;';
        frame.onload = async () => {
            try {
                if (!frame.contentDocument?.querySelector(options.selector || '[data-biodata]')) {
                    throw new Error('Print document unavailable');
                }
                await frame.contentDocument.fonts.ready;
                frame.contentWindow.focus();
                frame.contentWindow.print();
            } catch {
                printError.value = 'Dokumen tidak dapat dicetak. Muat ulang halaman dan coba lagi.';
            } finally {
                printingId.value = null;
            }
        };
        frame.onerror = () => {
            printingId.value = null;
            printError.value = 'Gagal memuat dokumen untuk dicetak. Silakan coba lagi.';
        };
        frame.src = options.url || `/${resource}/${id}/cetak`;
        document.body.appendChild(frame);
    };
    onBeforeUnmount(removePrintFrame);
    return { printingId, printError, handlePrint };
}
