<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan #{{ $report->id }} - Helpdesk PT. Lemigas</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <style>
        :root{
            --bg:#ffffff; --muted:#6b7280; --border:#d1d5db; --card:#ffffff; --accent:#0f172a;
            --primary:#1f2937; --muted-2:#94a3b8; --table-border:#e5e7eb;
        }
        *{box-sizing:border-box}
        html,body{height:100%;margin:0;font-family:'Figtree',system-ui,-apple-system,'Segoe UI',Roboto,'Helvetica Neue',Arial,'Noto Sans';color:var(--primary);background:var(--bg)}
        .paper{max-width:900px;margin:20px auto;padding:20px}
        .topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;padding-bottom:20px;border-bottom:2px solid var(--table-border)}
        .brand{display:flex;align-items:center;gap:12px}
        .brand img{height:48px;width:auto;border-radius:6px}
        .brand h2{margin:0;font-size:20px;font-weight:700;color:var(--primary)}
        .brand p{margin:0;font-size:13px;color:var(--muted);margin-top:2px}
        .print-meta{font-size:13px;color:var(--muted)}
        .grid{display:grid;grid-template-columns:1fr 320px;gap:24px}
        .card{background:var(--card);border:1px solid var(--table-border);box-shadow:0 1px 3px rgba(0,0,0,0.05);padding:20px}
        .section{margin-bottom:20px}
        .label{font-size:13px;font-weight:500;color:var(--primary);display:block;margin-bottom:6px;border-bottom:1px solid var(--table-border);padding-bottom:4px}
        .value{font-size:14px;color:var(--primary);margin-bottom:8px;line-height:1.5}
        .muted{color:var(--muted)}
        .two-cols{display:flex;gap:20px;padding:12px;background:#f9fafb;border:1px solid var(--table-border)}
        .thumb{overflow:hidden;border:1px solid var(--table-border);background:#fff}
        .thumb img{display:block;width:100%;height:180px;object-fit:cover}
        .info-row{display:flex;justify-content:space-between;gap:20px;align-items:start;padding:12px;background:#f9fafb;border:1px solid var(--table-border)}
        .status-badge{display:inline-block;padding:6px 12px;background:#f3f4f6;border:1px solid var(--table-border);font-size:13px}
        .title-lg{font-size:18px;margin:0 0 12px 0;padding-bottom:8px;border-bottom:2px solid var(--table-border);color:var(--primary);font-weight:600}
        .hr{height:1px;background:var(--table-border);margin:16px 0}
        .rating {display:flex;align-items:center;gap:8px}
        .stars svg{width:18px;height:18px}
        .print-actions{margin-top:12px}
        .print-btn{display:inline-block;padding:8px 12px;border-radius:6px;background:#0ea5a0;color:#fff;text-decoration:none;font-size:13px}
        .hint{font-size:12px;color:var(--muted);margin-left:12px}
        @media print{ .print-btn,.hint{display:none} .paper{margin:0;padding:8px} }
    </style>
</head>
<body onload="window.print()">
    <div class="paper">
        <div class="topbar">
            <div class="brand">
                <img src="{{ asset('images/lemigas.png') }}" alt="Lemigas">
                <div>
                    <h2>Helpdesk - PT. Lemigas</h2>
                    <p class="muted">Laporan Pekerjaan — ID #{{ $report->id }}</p>
                </div>
            </div>
            <div style="text-align:right">
                <div class="print-meta">Dicetak: {{ now()->format('d M Y H:i') }}</div>
                <div class="print-meta muted-2">{{ auth()->user()->name ?? '' }}</div>
            </div>
        </div>

        <div class="grid">
            <div>
                <div class="card section">
                    <h3 class="title-lg">Informasi Laporan</h3>
                    <div class="hr"></div>
                    <div class="info-row">
                        <div>
                            <div class="label">Kategori</div>
                            <div class="value">{{ ucfirst($report->kategori) }}</div>
                        </div>
                        <div>
                            <div class="label">Tanggal Pengajuan</div>
                            <div class="value">{{ \Carbon\Carbon::parse($report->tanggal_pengajuan)->format('d M Y') }}</div>
                        </div>
                    </div>
                    <div style="margin-top:10px">
                        <div class="label">Deskripsi</div>
                        <div class="value">{!! nl2br(e($report->deskripsi_pengajuan)) !!}</div>
                    </div>
                </div>

                @if($report->resolution)
                <div class="card section">
                    <h3 class="title-lg">Ringkasan Pekerjaan</h3>
                    <div class="hr"></div>
                    <div class="two-cols">
                        <div style="flex:1">
                            @if($report->resolution->barang)
                                <div class="label">Barang</div>
                                <div class="value">{{ $report->resolution->barang }} (Qty: {{ $report->resolution->qty }})</div>
                            @endif
                            <div class="label">Mulai</div>
                            <div class="value">{{ $report->start_time ? $report->start_time->format('d M Y H:i') : '-' }}</div>
                            <div class="label">Selesai</div>
                            <div class="value">{{ $report->end_time ? $report->end_time->format('d M Y H:i') : '-' }}</div>
                            @if($report->duration_minutes !== null)
                                <div class="label">Durasi</div>
                                <div class="value">{{ floor(abs($report->duration_minutes)/60) }} jam {{ abs($report->duration_minutes)%60 }} menit</div>
                            @endif
                            <div style="margin-top:10px">
                                <div class="label">Deskripsi Pekerjaan</div>
                                <div class="value">{!! nl2br(e($report->resolution->deskripsi_pekerjaan)) !!}</div>
                            </div>
                        </div>
                        <div style="width:34%">
                            <div class="label">Pelapor</div>
                            <div class="value">{{ $report->nama_pelapor }}<br><span class="muted">{{ $report->reporter->email ?? '-' }}</span></div>
                            <div style="height:10px"></div>
                            <div class="label">Teknisi</div>
                            <div class="value">{{ $report->technician->name ?? '-' }}<br><span class="muted">{{ $report->technician->email ?? '' }}</span></div>
                            <div style="height:10px"></div>
                            <div class="label">Status</div>
                            <div class="status-badge">{{ str_replace('_',' ', $report->status) }}</div>
                        </div>
                    </div>
                </div>

                <div class="card section">
                    <h3 class="title-lg">Dokumentasi</h3>
                    <div class="hr"></div>
                    <div style="display:flex;gap:12px">
                        <div style="flex:1">
                            <div class="label">Foto Sebelum</div>
                            @php
                                $beforePath = $report->resolution?->foto_before;
                                $beforeUrl = null;
                                if ($beforePath) {
                                    $beforeUrl = (\Illuminate\Support\Facades\Storage::disk('public')->exists($beforePath))
                                        ? \Illuminate\Support\Facades\Storage::disk('public')->url($beforePath)
                                        : asset('storage/' . ltrim($beforePath, '/'));
                                }
                            @endphp
                            @if($beforeUrl)
                                <div class="thumb"><a href="{{ $beforeUrl }}" target="_blank"><img src="{{ $beforeUrl }}" alt="Foto Sebelum"></a></div>
                            @else
                                <div class="thumb" style="height:180px;display:flex;align-items:center;justify-content:center;color:var(--muted);">Tidak ada foto</div>
                            @endif
                        </div>
                        <div style="flex:1">
                            <div class="label">Foto Sesudah</div>
                            @php
                                $afterPath = $report->resolution?->foto_after;
                                $afterUrl = null;
                                if ($afterPath) {
                                    $afterUrl = (\Illuminate\Support\Facades\Storage::disk('public')->exists($afterPath))
                                        ? \Illuminate\Support\Facades\Storage::disk('public')->url($afterPath)
                                        : asset('storage/' . ltrim($afterPath, '/'));
                                }
                            @endphp
                            @if($afterUrl)
                                <div class="thumb"><a href="{{ $afterUrl }}" target="_blank"><img src="{{ $afterUrl }}" alt="Foto Sesudah"></a></div>
                            @else
                                <div class="thumb" style="height:180px;display:flex;align-items:center;justify-content:center;color:var(--muted);">Tidak ada foto</div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                @if($report->status == 'rated')
                <div class="card section">
                    <h3 class="title-lg">Penilaian</h3>
                    <div class="hr"></div>
                    <div class="rating">
                        @php $rating = intval($report->rating ?? 0); @endphp
                        <div class="stars">
                            @for($i=1;$i<=5;$i++)
                                @if($i <= $rating)
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#f59e0b"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.176 0l-3.386 2.46c-.784.57-1.838-.197-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.045 9.393c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69L9.05 2.927z"/></svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#e2e8f0"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.176 0l-3.386 2.46c-.784.57-1.838-.197-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.045 9.393c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69L9.05 2.927z"/></svg>
                                @endif
                            @endfor
                        </div>
                        <div class="value">{{ $report->rating ?? '-' }} / 5</div>
                    </div>
                    @if($report->rating_feedback)
                        <div style="margin-top:10px">
                            <div class="label">Feedback</div>
                            <div class="value">{!! nl2br(e($report->rating_feedback)) !!}</div>
                        </div>
                    @endif
                </div>
                @endif

                <div class="print-actions">
                    <a href="#" class="print-btn" onclick="window.print();return false">Cetak / Simpan PDF</a>
                    <span class="hint">Tip: pilih "Save as PDF" di dialog printer untuk menyimpan.</span>
                </div>

            </div>

            <div>
                <div class="card">
                    <div class="label">Ringkasan Cepat</div>
                    <div style="display:flex;flex-direction:column;gap:10px;margin-top:8px">
                        <div><strong>Kategori:</strong> {{ ucfirst($report->kategori) }}</div>
                        <div><strong>Pelapor:</strong> {{ $report->nama_pelapor }}</div>
                        <div><strong>Teknisi:</strong> {{ $report->technician->name ?? '-' }}</div>
                        <div><strong>Status:</strong> <span class="status-badge">{{ str_replace('_',' ', $report->status) }}</span></div>
                        @if($report->resolution && $report->resolution->barang)
                        <div><strong>Barang:</strong> {{ $report->resolution->barang }} ({{ $report->resolution->qty }})</div>
                        @endif
                    </div>
                </div>

                <div class="card" style="margin-top:12px">
                    <div class="label">Informasi Cetak</div>
                    <div class="muted" style="margin-top:8px;font-size:13px">
                        <p style="margin:0 0 8px">Laporan ini dihasilkan oleh sistem Helpdesk. Informasi disusun secara otomatis berdasarkan data yang tersimpan.</p>
                        <div style="padding:8px;border:1px solid var(--table-border);background:#f9fafb;font-size:12px;text-align:center">
                            Dokumen ini dicetak pada {{ now()->format('d M Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // no-op JavaScript for print view; images open in new tab when clicked (anchors)
    </script>
</body>
</html>

