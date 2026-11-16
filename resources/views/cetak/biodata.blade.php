@extends('cetak.layout', ['title' => $title])

@section('styles')
    body { font: 10pt Arial, sans-serif; color: #20252b; }
    main { max-width: 186mm; }
    .letterhead { text-align: center; border-bottom: 3px double #20252b; padding-bottom: 3mm; margin-bottom: 3mm; }
    .letterhead .school-name { font-size: 12pt; font-weight: bold; margin: 1mm 0; }
    .document-title { text-align: center; font-size: 14pt; letter-spacing: 1.5px; text-transform: uppercase; margin: 0; }
    .letterhead .document-subtitle { text-align: center; font-size: 9pt; margin: 1mm 0 0; color: #555; }
    .person-summary { display: flex; align-items: center; gap: 5mm; margin-bottom: 3mm; break-inside: avoid; }
    .person-name { font-size: 16pt; font-weight: bold; margin: 0 0 1mm; }
    .person-caption { font-size: 9pt; color: #555; margin: 0 0 1mm; }
    .person-photo { width: 25mm; height: 33mm; border: 1px solid #a0a0a0; flex: none; display: flex; align-items: center; justify-content: center; color: #777; font-size: 9pt; }
    .person-photo img { width: 100%; height: 100%; object-fit: cover; }
    .record-section { margin-bottom: 2mm; }
    .record-section h2 { font-size: 10pt; text-transform: uppercase; letter-spacing: .5px; border-bottom: 1px solid #777; padding: 1mm 0; margin: 0 0 .5mm; break-after: avoid; }
    .record-fields { margin: 0; }
    .record-row { display: grid; grid-template-columns: 53mm 4mm minmax(0, 1fr); line-height: 1.2; padding: .3mm 0; break-inside: avoid; }
    .record-row dt { color: #444; }
    .record-row dd { margin: 0; white-space: pre-line; }
    .document-footer { margin-top: 3mm; border-top: 1px solid #bbb; padding-top: 1.5mm; font-size: 8pt; color: #666; }
@endsection

@section('header')
    @php($profil = \App\Models\ProfilSekolah::first())
    <header class="letterhead">
        <h1 class="document-title">{{ $title }}</h1>
        <p class="school-name">{{ $profil?->nama_sekolah ?: 'Nama sekolah belum diisi' }}</p>
        <p class="document-subtitle">{{ $subtitle ?? 'Identitas dan data peserta didik' }}</p>
    </header>
@endsection

@section('content')
    <article data-biodata @if (!isset($subtitle)) data-student-biodata @endif>
        <div class="person-summary">
            <div class="person-photo">
                @if ($foto)
                    <img src="{{ asset('storage/'.$foto) }}" alt="Foto {{ $nama }}">
                @else
                    <span>Pas foto<br>3 × 4</span>
                @endif
            </div>
            <div>
                <p class="person-caption">NAMA LENGKAP</p>
                <p class="person-name">{{ filled($nama) ? $nama : 'Nama belum diisi' }}</p>
                <p class="person-caption">{{ $caption ?? ($sections['Data Sekolah']['Kelas'] ?: 'Kelas belum diisi') }}</p>
            </div>
        </div>
        @foreach ($sections as $section => $fields)
            <section class="record-section">
                <h2>{{ chr(65 + $loop->index) }}. {{ $section }}</h2>
                <dl class="record-fields">
                    @foreach ($fields as $label => $value)
                        <div class="record-row">
                            <dt>{{ $label }}</dt><span aria-hidden="true">:</span>
                            <dd>{{ filled($value) ? $value : '–' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </section>
        @endforeach
        <footer class="document-footer">{{ $title }} · {{ $profil?->nama_sekolah ?: 'Administrasi sekolah' }}</footer>
    </article>
@endsection
