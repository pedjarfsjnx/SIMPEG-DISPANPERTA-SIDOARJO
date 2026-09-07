<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Rekapitulasi Proyeksi Pensiun (BUP) - Dinas Pangan dan Pertanian Kabupaten Sidoarjo</title>
    <style>
        @page {
            size: A4 landscape;
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
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .font-mono { font-family: monospace, Courier, sans-serif; font-size: 10px; }
        
        .signature-container {
            margin-top: 25px;
            display: flex;
            justify-content: flex-end;
            page-break-inside: avoid;
        }
        .signature-box {
            width: 260px;
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
            transition: background 0.2s;
        }
        .btn-print:hover { background: #065f46; }
        .btn-word {
            padding: 7px 16px;
            background: #0284c7;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
        }
        .btn-word:hover { background: #0369a1; }
        .btn-back {
            padding: 7px 12px;
            background: #475569;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        .btn-back:hover { background: #334155; }

        /* Editable in-place signature styling */
        .editable-field {
            border-bottom: 1px dashed #cbd5e1;
            padding: 1px 3px;
            border-radius: 3px;
            display: inline-block;
            transition: all 0.2s;
            cursor: text;
        }
        .editable-field:hover, .editable-field:focus {
            border-bottom-color: #047857;
            background-color: #f0fdf4;
            outline: 1px solid #059669;
        }

        .edit-hint {
            font-size: 10.5px;
            color: #047857;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            padding: 4px 10px;
            border-radius: 6px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        @media print {
            .no-print-bar, .edit-hint {
                display: none !important;
            }
            .editable-field {
                border-bottom: none !important;
                background: transparent !important;
                outline: none !important;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>

    @php
        $kadis = \App\Models\Pegawai::whereHas('formasiJabatan', function($q) {
            $q->where('nama_jabatan', 'like', '%KEPALA DINAS%');
        })->first();

        $kadisNama = $kadis?->nama ?? 'Dr. ENI RUSTIANINGSIH, ST., MT';
        $kadisNip = $kadis?->nip ? 'NIP. ' . $kadis->nip : 'NIP. 196712101997032004';
    @endphp

    <!-- Non-printable Top Bar -->
    <div class="no-print-bar">
        <div>
            <div style="font-size: 12px; font-weight: 600;">
                📄 Pratinjau Cetak Laporan Rekapitulasi Pensiun Pegawai (Total: {{ count($rekapList) }} Personel)
            </div>
            <div class="edit-hint">
                💡 <span><strong>Tips:</strong> Nama penandatangan otomatis diambil dari data Kepala Dinas aktif di database. Anda juga dapat mengklik langsung teksnya jika ingin mengganti pejabat penandatangan sebelum mencetak/mengunduh.</span>
            </div>
        </div>
        <div style="display: flex; gap: 8px; align-items: center;">
            <button onclick="window.print()" class="btn-print">
                🖨️ Cetak / Simpan PDF
            </button>
            <button onclick="downloadAsWord('Rekapitulasi_Proyeksi_Pensiun_Pegawai')" class="btn-word">
                📄 Unduh Word (.doc)
            </button>
            <a href="{{ route('admin.pensiun.index', request()->query()) }}" class="btn-back">
                &larr; Kembali
            </a>
        </div>
    </div>

    <div id="printable-area">
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
            <h4>REKAPITULASI PROYEKSI BATAS USIA PENSIUN (BUP) PEGAWAI</h4>
            <div class="report-meta">
                @if(request('tahun'))
                    Tahun Proyeksi: <strong>{{ request('tahun') }}</strong>
                @else
                    Periode: <strong>Semua Periode Mendatang</strong>
                @endif
                @if(request('bulan'))
                    &bull; Bulan: <strong>{{ \Carbon\Carbon::createFromDate(null, (int)request('bulan'), 1)->translatedFormat('F') }}</strong>
                @endif
                &bull; Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB
            </div>
        </div>

        <!-- Data Table -->
        <table>
            <thead>
                <tr>
                    <th width="30">NO</th>
                    <th>NAMA PEGAWAI & NIP</th>
                    <th>JABATAN & PENEMPATAN</th>
                    <th width="85">TGL LAHIR</th>
                    <th width="45">BUP</th>
                    <th width="95">TMT PENSIUN</th>
                    <th width="120">KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekapList as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <span class="font-bold">{{ $item->nama }}</span>
                        <div class="font-mono" style="color: #4b5563;">NIP. {{ $item->nip ?: '-' }}</div>
                    </td>
                    <td>
                        <div class="font-bold">{{ $item->jabatan }}</div>
                        <div style="font-size: 10px; color: #4b5563;">{{ $item->unit_kerja }} {{ $item->bidang !== '-' ? ' - ' . $item->bidang : '' }}</div>
                    </td>
                    <td class="text-center">{{ $item->tanggal_lahir ? $item->tanggal_lahir->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">{{ $item->bup }} Thn</td>
                    <td class="text-center font-bold">
                        {{ $item->tmt_pensiun ? $item->tmt_pensiun->translatedFormat('d M Y') : '-' }}
                    </td>
                    <td class="text-center" style="font-size: 10px;">
                        {{ $item->keterangan_khusus }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 15px; color: #6b7280; font-style: italic;">
                        Tidak ada data pegawai yang sesuai dengan kriteria filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Signature Section -->
        <div class="signature-container">
            <div class="signature-box">
                <div><span class="editable-field" contenteditable="true">Sidoarjo</span>, <span class="editable-field" contenteditable="true">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span></div>
                <div style="font-weight: bold; margin-top: 4px;" class="editable-field" contenteditable="true">Kepala Dinas Pangan dan Pertanian<br>Kabupaten Sidoarjo</div>
                <div class="signature-space"></div>
                <div style="font-weight: bold; text-decoration: underline;" class="editable-field" contenteditable="true">{{ $kadisNama }}</div>
                <div class="font-mono editable-field" contenteditable="true">{{ $kadisNip }}</div>
            </div>
        </div>
    </div>

    <script>
        function downloadAsWord(filename) {
            var printArea = document.getElementById('printable-area').cloneNode(true);
            
            var editables = printArea.querySelectorAll('[contenteditable]');
            editables.forEach(function(el) {
                el.removeAttribute('contenteditable');
                el.classList.remove('editable-field');
            });

            var header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' " +
                "xmlns:w='urn:schemas-microsoft-com:office:word' " +
                "xmlns='http://www.w3.org/TR/REC-html40'>" +
                "<head><meta charset='utf-8'><title>" + filename + "</title>" +
                "<style>" +
                "@page { size: A4 landscape; margin: 1.5cm 1.5cm 1.5cm 1.5cm; } " +
                "body { font-family: Arial, sans-serif; font-size: 10pt; color: #111827; } " +
                "table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 9.5pt; } " +
                "th, td { border: 1px solid #374151; padding: 5px 6px; } " +
                "th { background-color: #f3f4f6; font-weight: bold; text-align: center; } " +
                ".text-center { text-align: center; } " +
                ".font-bold { font-weight: bold; } " +
                ".font-mono { font-family: 'Courier New', monospace; } " +
                ".kop-container { border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 15px; text-align: center; } " +
                ".kop-logo { display: none; } " +
                ".kop-text h2 { font-size: 13pt; margin: 0; } " +
                ".kop-text h3 { font-size: 12pt; margin: 2px 0; } " +
                ".kop-text p { font-size: 8.5pt; color: #374151; margin: 0; } " +
                ".report-title { text-align: center; margin: 15px 0; } " +
                ".report-title h4 { font-size: 11pt; text-decoration: underline; margin: 0; font-weight: bold; } " +
                ".signature-container { margin-top: 30px; } " +
                ".signature-box { float: right; width: 260px; text-align: center; } " +
                ".signature-space { height: 55px; } " +
                "</style></head><body>";
            var footer = "</body></html>";
            var html = header + printArea.innerHTML + footer;

            var blob = new Blob(['\ufeff' + html], {
                type: 'application/msword;charset=utf-8'
            });

            var link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = filename + '_' + new Date().toISOString().slice(0, 10) + '.doc';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(link.href);
        }
    </script>
</body>
</html>
