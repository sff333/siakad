@extends('cetak.layout', ['title' => 'Rekap Data Siswa', 'orientation' => 'landscape'])

@section('styles')
    th, td { padding: 1.5mm 2mm; font-size: 9pt; line-height: 1.25; }
@endsection

@section('content')
    <table data-rekap-siswa>
        <thead>
            <tr>
                <th scope="col">No.</th>
                <th scope="col">Nama Siswa</th>
                <th scope="col">NIS</th>
                <th scope="col">NISN</th>
                <th scope="col">Kelas</th>
                <th scope="col">L/P</th>
                <th scope="col">Nama Wali / Orang Tua</th>
                <th scope="col">Telepon</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($siswa as $s)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $s->nama ?: '–' }}</td>
                    <td>{{ $s->nis ?: '–' }}</td>
                    <td>{{ $s->nisn ?: '–' }}</td>
                    <td>{{ $s->kelas?->nama_kelas ?: '–' }}</td>
                    <td>{{ $s->jenis_kelamin ?: '–' }}</td>
                    <td>{{ $s->nama_wali ?: $s->nama_ayah ?: $s->nama_ibu ?: '–' }}</td>
                    <td>{{ $s->telepon_wali ?: $s->telepon_ortu ?: '–' }}</td>
                    <td>{{ match ($s->status) { 'pindah' => 'Mutasi Keluar', 'keluar' => 'Keluar / DO', default => ucfirst($s->status ?? '–') } }}</td>
                </tr>
            @empty
                <tr><td colspan="9">Tidak ada data siswa.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
