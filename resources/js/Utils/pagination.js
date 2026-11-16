export const compactPaginationLinks = (links = []) => {
    const pages = links.slice(1, -1).filter(link => /^\d+$/.test(String(link.label)));
    if (!pages.length) return links;

    const lastPage = Math.max(...pages.map(link => Number(link.label)));
    const currentPage = Number(pages.find(link => link.active)?.label || 1);
    const start = Math.max(1, Math.min(currentPage - 1, lastPage - 2));
    const end = Math.min(lastPage, start + 2);
    const visible = pages.filter(link => {
        const page = Number(link.label);
        return lastPage <= 5 || page === 1 || page === lastPage || (page >= start && page <= end);
    });
    const result = [links[0]];
    let previousPage = 0;
    for (const link of visible) {
        const page = Number(link.label);
        if (previousPage && page - previousPage > 1) {
            result.push({ label: '…', url: null, active: false });
        }
        result.push(link);
        previousPage = page;
    }
    result.push(links[links.length - 1]);
    return result;
};
