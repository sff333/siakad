<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <style>
        @page { size: A4 {{ $orientation ?? 'portrait' }}; margin: 12mm; }
        body { font: 10pt Arial, sans-serif; color: #111; margin: 0; overflow-wrap: anywhere; }
        main { max-width: 260mm; margin: 1rem auto; padding: 0 1rem; }
        header { text-align: center; margin-bottom: 1rem; }
        header p { margin: 0.25rem 0; }
        h1 { font-size: 16pt; margin: 0 0 0.25rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #777; padding: 0.4rem 0.55rem; text-align: left; vertical-align: top; }
        tr { break-inside: avoid; }
        thead { display: table-header-group; }
        .controls { text-align: right; margin-bottom: 1rem; }
        .controls button { padding: 0.5rem 1rem; cursor: pointer; }
        @media print {
            main { max-width: none; margin: 0; padding: 0; }
            .controls { display: none; }
        }
        @yield('styles')
    </style>
</head>
<body>
    <main>
        <div class="controls"><button type="button" onclick="window.print()">Cetak</button></div>
        @section('header')
        <header>
            <h1>{{ $title }}</h1>
            @if ($sekolah = \App\Models\ProfilSekolah::first()?->nama_sekolah)
                <p>{{ $sekolah }}</p>
            @endif
        </header>
        @show
        @yield('content')
    </main>
    <script>window.addEventListener('load', () => { if (window.self === window.top) window.print(); });</script>
</body>
</html>
