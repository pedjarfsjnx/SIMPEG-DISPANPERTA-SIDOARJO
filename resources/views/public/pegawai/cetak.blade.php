<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Data Pegawai - Dinas Pangan dan Pertanian Kabupaten Sidoarjo</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; color: #111; }
        .kop-container { display: flex; align-items: center; justify-between: space-between; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-logo { height: 60px; width: auto; }
        .kop-text { text-align: center; flex-grow: 1; margin: 0 15px; }
        .kop-text h2 { margin: 0; font-size: 15px; font-weight: bold; text-transform: uppercase; }
        .kop-text h3 { margin: 2px 0; font-size: 13px; text-transform: uppercase; }
        .kop-text p { margin: 0; font-size: 10px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        .text-center { text-align: center; }
        .no-print { margin-bottom: 20px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" style="padding: 8px 16px; background: #166534; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            🖨️ Cetak Dokumen (Print / Save PDF)
        </button>
    </div>

    <!-- Kop Resmi Dinas dengan Logo Asli -->
    <div class="kop-container">
        <img src="{{ asset('logo/logo kabupaten sidoarjo.png') }}" class="kop-logo" alt="Kabupaten Sidoarjo">
        <div class="kop-text">
            <h2>PEMERINTAH KABUPATEN SIDOARJO</h2>
            <h3>DINAS PANGAN DAN PERTANIAN</h3>
            <p>Jl. Pahlawan No.KM.2, Jetis, Lemahputro, Kec. Sidoarjo, Kabupaten Sidoarjo, Jawa Timur 61213</p>
        </div>
        <img src="{{ asset('logo/logo dispanperta sidoarjo.png') }}" class="kop-logo" alt="Dispanperta Sidoarjo">
    </div>

    <h3 style="text-align: center; margin-bottom: 15px;">LAPORAN DAFTAR PEGAWAI INTERNAL</h3>

    <table>
        <thead>
            <tr>
                <th width="30">NO</th>
                <th>NAMA PEGAWAI</th>
                <th>NIP / NIK</th>
                <th>KATEGORI</th>
                <th>UNIT KERJA</th>
                <th>JABATAN</th>
                <th>GOL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pegawaiList as $index => $peg)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $peg->nama }}</strong></td>
                <td>{{ $peg->nip ?: ($peg->nik ?: '-') }}</td>
                <td>{{ $peg->kategori?->nama }}</td>
                <td>{{ $peg->unitKerja?->nama }}</td>
                <td>{{ $peg->formasiJabatan?->nama_jabatan ?: '-' }}</td>
                <td class="text-center">{{ $peg->golongan ?: '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; float: right; text-align: center; width: 250px;">
        <p>Sidoarjo, {{ date('d F Y') }}</p>
        <p>Kepala Dinas Pangan dan Pertanian<br>Kabupaten Sidoarjo</p>
        <br><br><br>
        <p><strong>Dr. ENI RUSTIANINGSIH, ST., MT</strong><br>Pembina Utama Muda<br>NIP. 196712101997032004</p>
    </div>

</body>
</html>
