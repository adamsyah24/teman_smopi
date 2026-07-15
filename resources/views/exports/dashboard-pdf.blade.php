<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Analisis Dashboard TEMAN SMOPI</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Arial, sans-serif;
            color: #334155;
            font-size: 10px;
            line-height: 1.5;
        }
        @page {
            margin: 1.5cm;
        }

        /* ======= HEADER ======= */
        .header {
            border-bottom: 3px solid #1e3a8a;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header-title {
            font-size: 17px;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-sub {
            font-size: 11px;
            color: #475569;
            font-weight: bold;
            margin-top: 2px;
        }
        .header-meta {
            font-size: 9px;
            color: #64748b;
            margin-top: 8px;
        }

        /* ======= SECTION TITLE ======= */
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #ffffff;
            background-color: #1e3a8a;
            padding: 6px 10px;
            margin-top: 20px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* ======= TABLE ======= */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 9px;
        }
        .data-table th {
            background-color: #3b82f6;
            color: #ffffff;
            border: 1px solid #2563eb;
            padding: 5px 7px;
            text-align: left;
            font-weight: bold;
        }
        .data-table td {
            border: 1px solid #cbd5e1;
            padding: 4px 7px;
        }
        .data-table tr:nth-child(even) td {
            background-color: #f1f5f9;
        }
        .data-table td.center {
            text-align: center;
        }
        .data-table td.right {
            text-align: right;
        }
        .data-table td.italic {
            font-style: italic;
            color: #64748b;
        }

        /* ======= ANALYSIS BOX ======= */
        .analysis-box {
            background-color: #eff6ff;
            border-left: 4px solid #2563eb;
            padding: 10px 14px;
            margin-bottom: 22px;
        }
        .analysis-label {
            font-weight: bold;
            color: #1e40af;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 4px;
        }
        .analysis-text {
            color: #1e293b;
            font-size: 10px;
        }

        /* ======= PAGE BREAK ======= */
        .page-break {
            page-break-after: always;
        }

        /* ======= FOOTER ======= */
        .footer {
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            margin-top: 25px;
        }
    </style>
</head>
<body>

    <!-- ===== HEADER ===== -->
    <div class="header">
        <div class="header-title">Laporan Analisis Dashboard</div>
        <div class="header-sub">TEMAN SMOPI &mdash; Sistem Manajemen Laporan</div>
        <div class="header-meta">
            Dicetak Oleh: {{ auth()->user()->name ?? 'Administrator' }}
            &nbsp;&nbsp;|&nbsp;&nbsp;
            Tanggal Cetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB
        </div>
    </div>

    <!-- ===== 1. DURASI PENGERJAAN LAPORAN ===== -->
    <div class="section-title">1. Durasi Pengerjaan Laporan</div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width:5%">No</th>
                <th style="width:25%">Nomor Tiket</th>
                <th style="width:20%">Tanggal Masuk</th>
                <th style="width:20%">Tanggal Selesai</th>
                <th style="width:15%">Durasi (Hari)</th>
            </tr>
        </thead>
        <tbody>
            @forelse(array_slice($data['durasi_pengerjaan']['items'], 0, 15) as $idx => $item)
                <tr>
                    <td class="center">{{ $idx + 1 }}</td>
                    <td>{{ $item['tiket'] }}</td>
                    <td>{{ $item['tanggal_masuk'] }}</td>
                    <td>{{ $item['tanggal_selesai'] }}</td>
                    <td class="center">{{ $item['durasi'] }} hari</td>
                </tr>
            @empty
                <tr><td colspan="5" class="center italic">Tidak ada data laporan selesai.</td></tr>
            @endforelse
            @if(count($data['durasi_pengerjaan']['items']) > 15)
                <tr>
                    <td colspan="5" class="center italic">
                        Menampilkan 15 dari {{ count($data['durasi_pengerjaan']['items']) }} laporan. Unduh format Excel untuk data lengkap.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="analysis-box">
        <div class="analysis-label">&#128202; Analisis Durasi Pengerjaan</div>
        <div class="analysis-text">{!! preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $data['durasi_pengerjaan']['analysis']) !!}</div>
    </div>

    <div class="page-break"></div>

    <!-- ===== 2. MEDIAN DURASI PER BULAN ===== -->
    <div class="section-title">2. Median Durasi Pengerjaan per Bulan (Hari)</div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Median Durasi (Hari)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['median_durasi']['labels'] as $idx => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td class="center">{{ $data['median_durasi']['data'][$idx] }} hari</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="analysis-box">
        <div class="analysis-label">&#128202; Analisis Median Durasi</div>
        <div class="analysis-text">{!! preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $data['median_durasi']['analysis']) !!}</div>
    </div>

    <!-- ===== 3. PERSENTASE KATEGORI PER BULAN ===== -->
    <div class="section-title">3. Persentase Laporan per Kategori per Bulan (%)</div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width:40%">Kategori &amp; Role</th>
                @foreach($data['persentase_kategori']['labels'] as $label)
                    <th class="center">{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse(array_slice($data['persentase_kategori']['datasets'], 0, 10) as $dataset)
                <tr>
                    <td><strong>{{ $dataset['label'] }}</strong></td>
                    @foreach($dataset['data'] as $val)
                        <td class="center">{{ $val }}%</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($data['persentase_kategori']['labels']) + 1 }}" class="center italic">
                        Tidak ada data persentase kategori.
                    </td>
                </tr>
            @endforelse
            @if(count($data['persentase_kategori']['datasets']) > 10)
                <tr>
                    <td colspan="{{ count($data['persentase_kategori']['labels']) + 1 }}" class="center italic">
                        Menampilkan 10 dari {{ count($data['persentase_kategori']['datasets']) }} dataset. Unduh format Excel untuk data lengkap.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="analysis-box">
        <div class="analysis-label">&#128202; Analisis Persentase Kategori</div>
        <div class="analysis-text">{!! preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $data['persentase_kategori']['analysis']) !!}</div>
    </div>

    <div class="page-break"></div>

    <!-- ===== 4. RATA-RATA DURASI PER BULAN ===== -->
    <div class="section-title">4. Rata-rata Durasi Pengerjaan per Bulan (Hari)</div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Rata-rata Durasi (Hari)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['rata_rata_durasi']['labels'] as $idx => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td class="center">{{ $data['rata_rata_durasi']['data'][$idx] }} hari</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="analysis-box">
        <div class="analysis-label">&#128202; Analisis Rata-rata Durasi</div>
        <div class="analysis-text">{!! preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $data['rata_rata_durasi']['analysis']) !!}</div>
    </div>

    <!-- ===== 5. STATISTIK BULANAN LAPORAN ===== -->
    <div class="section-title">5. Statistik Bulanan Laporan (Tahun {{ now()->year }})</div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Jumlah Laporan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['statistik_bulanan']['labels'] as $idx => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td class="center">{{ $data['statistik_bulanan']['data'][$idx] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="analysis-box">
        <div class="analysis-label">&#128202; Analisis Statistik Bulanan</div>
        <div class="analysis-text">{!! preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $data['statistik_bulanan']['analysis']) !!}</div>
    </div>

    <div class="page-break"></div>

    <!-- ===== 6. STATISTIK GABUNGAN BERDASARKAN STATUS ===== -->
    <div class="section-title">6. Statistik Bulanan Laporan Berdasarkan Status</div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width:28%">Status Laporan</th>
                @foreach($data['statistik_gabungan']['labels'] as $label)
                    <th class="center">{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data['statistik_gabungan']['datasets'] as $dataset)
                <tr>
                    <td><strong>{{ $dataset['label'] }}</strong></td>
                    @foreach($dataset['data'] as $val)
                        <td class="center">{{ $val }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="analysis-box">
        <div class="analysis-label">&#128202; Analisis Distribusi Status</div>
        <div class="analysis-text">{!! preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $data['statistik_gabungan']['analysis']) !!}</div>
    </div>

    <!-- ===== 7. JUMLAH LAPORAN PER KATEGORI ===== -->
    <div class="section-title">7. Jumlah Laporan per Kategori (1 Tahun Terakhir)</div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width:8%">No</th>
                <th>Kategori Laporan</th>
                <th style="width:20%">Jumlah Laporan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data['statistik_kategori']['labels'] as $idx => $label)
                <tr>
                    <td class="center">{{ $idx + 1 }}</td>
                    <td>{{ $label }}</td>
                    <td class="center">{{ $data['statistik_kategori']['data'][$idx] }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="center italic">Tidak ada data kategori.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="analysis-box">
        <div class="analysis-label">&#128202; Analisis Kategori Laporan</div>
        <div class="analysis-text">{!! preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $data['statistik_kategori']['analysis']) !!}</div>
    </div>

    <!-- ===== FOOTER ===== -->
    <div class="footer">
        TEMAN SMOPI &copy; {{ date('Y') }} &mdash; Laporan ini dibuat secara otomatis oleh sistem.
    </div>

</body>
</html>
