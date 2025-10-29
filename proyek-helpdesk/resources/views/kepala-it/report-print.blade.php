<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan #{{ $report->id }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <style>
        :root { --gray:#111827; --muted:#6b7280; --border:#e5e7eb; --primary:#4f46e5; }
        * { box-sizing: border-box; }
        body { font-family: 'Figtree', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji','Segoe UI Emoji'; color:#111827; margin:0; background:#fff; }
        .container { max-width: 800px; margin: 24px auto; padding: 0 16px; }
        .header { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
        .title { font-size:22px; font-weight:700; margin:0; }
        .meta { color:#6b7280; font-size:12px; }
        .card { border:1px solid var(--border); border-radius:12px; padding:16px; margin-bottom:12px; }
        .section-title { font-size:14px; font-weight:600; margin:0 0 8px 0; color:#374151; }
        .row { display:flex; gap:12px; }
        .col { flex:1; }
        .badge { display:inline-block; border-radius:999px; padding:2px 8px; font-size:12px; border:1px solid var(--border); }
        .muted { color:#6b7280; }
        .mt-8 { margin-top: 16px; }
        .print-hint { display:inline-block; margin-top:16px; font-size:12px; color:#6b7280; }
        @media print { .print-btn, .print-hint { display:none; } .container { margin:0; } }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <div class="header">
            <h1 class="title">Laporan Pekerjaan #{{ $report->id }}</h1>
            <div class="meta">Dicetak: {{ now()->format('d M Y H:i') }}</div>
        </div>

        <div class="card">
            <div class="row">
                <div class="col">
                    <div class="section-title">Informasi Laporan</div>
                    <div><span class="muted">Kategori:</span> {{ ucfirst($report->kategori) }}</div>
                    <div><span class="muted">Tanggal Pengajuan:</span> {{ \Carbon\Carbon::parse($report->tanggal_pengajuan)->format('d M Y') }}</div>
                    <div class="mt-8"><span class="muted">Deskripsi:</span><br>{{ $report->deskripsi_pengajuan }}</div>
                </div>
                <div class="col">
                    <div class="section-title">Pelapor & Teknisi</div>
                    <div><span class="muted">Pelapor:</span> {{ $report->nama_pelapor }} ({{ $report->reporter->email ?? '-' }})</div>
                    <div><span class="muted">Teknisi:</span> {{ $report->technician->name ?? '-' }} {{ $report->technician ? '(' . $report->technician->email . ')' : '' }}</div>
                    <div class="mt-8"><span class="muted">Status:</span> <span class="badge">{{ str_replace('_',' ', $report->status) }}</span></div>
                </div>
            </div>
        </div>

        @if($report->resolution)
        <div class="card">
            <div class="section-title">Ringkasan Pekerjaan</div>
            @if($report->resolution->barang)
            <div><span class="muted">Barang:</span> {{ $report->resolution->barang }} (Qty: {{ $report->resolution->qty }})</div>
            @endif
            @if($report->start_time)
            <div><span class="muted">Mulai:</span> {{ optional($report->start_time)->format('d M Y H:i') }}</div>
            @endif
            @if($report->end_time)
            <div><span class="muted">Selesai:</span> {{ optional($report->end_time)->format('d M Y H:i') }}</div>
            @endif
            @if($report->duration_minutes !== null)
            <div><span class="muted">Durasi:</span> {{ floor(abs($report->duration_minutes)/60) }} jam {{ abs($report->duration_minutes)%60 }} menit</div>
            @endif
            <div class="mt-8"><span class="muted">Deskripsi Pekerjaan:</span><br>{{ $report->resolution->deskripsi_pekerjaan }}</div>
        </div>
        @endif

        @if($report->status == 'rated')
        <div class="card">
            <div class="section-title">Penilaian</div>
            <div><span class="muted">Rating:</span> {{ $report->rating ?? '-' }} / 5</div>
            @if($report->rating_feedback)
            <div class="mt-8"><span class="muted">Feedback:</span><br>{{ $report->rating_feedback }}</div>
            @endif
        </div>
        @endif

        <a class="print-btn" href="#" onclick="window.print(); return false;">Cetak</a>
        <span class="print-hint">Tip: Pilih "Save as PDF" di dialog printer untuk menyimpan PDF.</span>
    </div>
</body>
</html>

