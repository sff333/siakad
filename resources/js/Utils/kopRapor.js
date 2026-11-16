export const judulKopRapor = (kabupaten = '') => {
    const namaKabupaten = (kabupaten || '').trim().replace(/^(?:kabupaten|kab\.?)\s+/i, '').replace(/\s+/g, ' ').toUpperCase();
    return ['DINAS PENDIDIKAN KABUPATEN', namaKabupaten].filter(Boolean).join(' ');
};
