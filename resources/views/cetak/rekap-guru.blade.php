@extends('cetak.layout', ['title' => 'Rekap Data Guru', 'orientation' => 'landscape'])

@section('content')
    <table data-rekap-guru>
        <thead>
            <tr>
                <th scope="col">No.</th>
                <th scope="col">Nama</th>
                <th scope="col">NIP</th>
                <th scope="col">Jenis kelamin</th>
                <th scope="col">Jabatan</th>
                <th scope="col">Pendidikan terakhir</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($guru as $g)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $g->nama ?: '–' }}</td>
                    <td>{{ $g->nip ?: '–' }}</td>
                    <td>{{ match ($g->jenis_kelamin) { 'L' => 'Laki-laki', 'P' => 'Perempuan', default => '–' } }}</td>
                    <td>{{ $g->jabatan ?: '–' }}</td>
                    <td>{{ $g->pendidikan_terakhir ?: '–' }}</td>
                    <td>{{ $g->status_aktif ? 'Aktif' : 'Nonaktif' }}</td>
                </tr>
            @empty
                <tr><td colspan="7">Tidak ada data guru.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
