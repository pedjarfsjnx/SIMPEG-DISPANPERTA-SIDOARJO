<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Daftar Usulan Kenaikan Pangkat - Dinas Pangan dan Pertanian Kabupaten Sidoarjo</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #111827;
            margin: 0;
            padding: 10px 20px;
            background-color: #fff;
        }
        .kop-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .kop-logo {
            height: 65px;
            width: auto;
            object-fit: contain;
        }
        .kop-text {
            text-align: center;
            flex-grow: 1;
            margin: 0 15px;
        }
        .kop-text h2 {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .kop-text h3 {
            margin: 2px 0;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
        }
        .kop-text p {
            margin: 0;
            font-size: 9.5px;
            color: #374151;
            line-height: 1.3;
        }
        .report-title {
            text-align: center;
            margin: 10px 0 15px 0;
        }
        .report-title h4 {
            margin: 0;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
        }
        .report-meta {
            font-size: 10px;
            color: #4b5563;
            margin-top: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th, td {
            border: 1px solid #374151;
            padding: 6px 8px;
            vertical-align: middle;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            text-align: center;
        }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .font-mono { font-family: monospace, Courier, sans-serif; font-size: 10px; }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 9.5px;
            font-weight: bold;
        }
        .badge-primary { background: #dbeafe; color: #1e40af; }
        
        .signature-container {
            margin-top: 25px;
            display: flex;
            justify-content: flex-end;
            page-break-inside: avoid;
        }
        .signature-box {
            width: 250px;
            text-align: center;
            font-size: 11px;
        }
        .signature-space {
            height: 55px;
        }
        
        /* Floating Action Bar */
        .no-print-bar {
            background: #1e293b;
            color: #fff;
            padding: 10px 15px;
            margin: -10px -20px 20px -20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: system-ui, -apple-system, sans-serif;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }
        .btn-print {
            padding: 7px 16px;
            background: #047857;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-print:hover { background: #065f46; }
        .btn-back {
            padding: 6px 12px;
            background: #475569;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 11px;
            text-decoration: none;
        }
        .btn-back:hover { background: #334155; }

        @media print {
            .no-print-bar {
                display: none !important;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Non-printable Top Bar -->
    <div class="no-print-bar">
        <div style="font-size: 12px; font-weight: 600;">
            📄 Pratinjau Cetak Daftar Usulan Kenaikan Pangkat (Total: {{ count($kpList) }} Usulan)
        </div>
        <div style="display: flex; gap: 8px; align-items: center;">
            <button onclick="window.print()" class="btn-print">
                🖨️ Cetak Dokumen / Simpan PDF
            </button>
            <a href="{{ route('admin.kenaikan-pangkat.index', request()->query()) }}" class="btn-back">
                &larr; Kembali
            </a>
        </div>
    </div>

    <!-- Kop Surat Resmi Dinas -->
    <div class="kop-container">
        <img src="{{ asset('logo/logo kabupaten sidoarjo.png') }}" class="kop-logo" alt="Logo Pemkab Sidoarjo">
        <div class="kop-text">
            <h2>PEMERINTAH KABUPATEN SIDOARJO</h2>
            <h3>DINAS PANGAN DAN PERTANIAN</h3>
            <p>Jl. Pahlawan No.KM.2, Jetis, Lemahputro, Kec. Sidoarjo, Kabupaten Sidoarjo, Jawa Timur 61213<br>
            Website: dispanperta.sidoarjokab.go.id &bull; Pos: 61213</p>
        </div>
        <img src="{{ asset('logo/logo dispanperta sidoarjo.png') }}" class="kop-logo" alt="Logo Dispanperta">
    </div>

    <!-- Title -->
    <div class="report-title">
        <h4>DAFTAR USULAN KENAIKAN PANGKAT PEGAWAI NEGERI SIPIL (PNS)</h4>
    </div>

    <!-- Data Table -->
    <table>
        <thead>
            <tr>
                <th width="30">NO</th>
                <th>NAMA PEGAWAI & NIP</th>
                <th>UNIT KERJA & BIDANG</th>
                <th width="70">GOL. LAMA</th>
                <th width="75">GOL. USULAN</th>
                <th width="90">TMT DIUSULKAN</th>
                <th width="85">BATAS PENSIUN</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kpList as $index => $kp)
            @php
                $estPensiun = $kp->pegawai?->estimasi_pensiun['tanggal'];
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <span class="font-bold">{{ $kp->pegawai?->nama ?? '-' }}</span>
                    <div class="font-mono" style="color: #4b5563;">NIP. {{ $kp->pegawai?->nip ?? '-' }}</div>
                </td>
                <td>
                    <div class="font-bold">{{ $kp->pegawai?->unitKerja?->nama ?? '-' }}</div>
                    <div style="font-size: 10px; color: #4b5563;">{{ $kp->pegawai?->bidang?->nama ?? '-' }}</div>
                </td>
                <td class="text-center font-mono">{{ $kp->golongan_lama ?: '-' }}</td>
                <td class="text-center">
                    <span class="badge badge-primary font-mono">{{ $kp->golongan_baru }}</span>
                </td>
                <td class="text-center font-bold">
                    {{ $kp->tmt_diusulkan ? $kp->tmt_diusulkan->translatedFormat('d M Y') : '-' }}
                </td>
                <td class="text-center font-mono" style="color: #4b5563;">
                    {{ $estPensiun ? $estPensiun->translatedFormat('d M Y') : '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 15px; color: #6b7280; font-style: italic;">
                    Tidak ada data usulan kenaikan pangkat yang sesuai.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Signature Section -->
    <div class="signature-container">
        <div class="signature-box">
            <div>Sidoarjo, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            <div style="font-weight: bold; margin-top: 4px;">Kepala Dinas Pangan dan Pertanian<br>Kabupaten Sidoarjo</div>
            <div class="signature-space"></div>
            <div style="font-weight: bold; text-decoration: underline;">Dr. Dra. ENI RUSTIANINGSIH, ST., MT.</div>
            <div>Pembina Utama Muda (IV/c)</div>
            <div class="font-mono">NIP. 19680529 199403 2 006</div>
        </div>
    </div>

</body>
</html>
