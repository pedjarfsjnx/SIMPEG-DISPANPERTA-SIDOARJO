@extends('layouts.public')

@section('title', 'Dashboard - SIMPEG Dinas Pangan dan Pertanian Kabupaten Sidoarjo')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-white rounded-lg border border-slate-200 p-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-bold rounded text-[10px] uppercase tracking-wider">Dashboard Real-time</span>
            <h2 class="text-xl font-bold text-slate-900 mt-1">Dashboard Kepegawaian</h2>
            <p class="text-xs text-slate-500">Ringkasan visual statistik dan grafik komposisi data pegawai aktif Dinas Pangan dan Pertanian Kabupaten Sidoarjo.</p>
        </div>
        <a href="{{ route('public.pegawai.index') }}" class="px-3.5 py-2 bg-emerald-800 hover:bg-emerald-900 text-white text-xs font-bold rounded transition shadow-sm whitespace-nowrap">
            Lihat Direktori Pegawai &rarr;
        </a>
    </div>

    <!-- Summary Stats Grid (Clickable Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Card 1: Total Pegawai (Clickable) -->
        <a href="{{ route('public.pegawai.index') }}" class="block bg-white p-5 rounded-lg border border-slate-200 shadow-sm border-l-4 border-l-emerald-800 hover:border-emerald-700 hover:shadow-md transition group">
            <div class="flex justify-between items-start">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider group-hover:text-emerald-800">Total Pegawai Aktif</div>
                <span class="text-[10px] text-emerald-800 font-bold bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">Lihat Semua &rarr;</span>
            </div>
            <div class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($totalPegawai) }}</div>
            <div class="text-xs text-slate-500 mt-1">Klik untuk melihat seluruh 149 personel</div>
        </a>

        <!-- Cards Kategori (Clickable) -->
        @foreach($rekapKategori as $kat)
        <a href="{{ route('public.pegawai.index', ['kategori_id' => $kat->id]) }}" class="block bg-white p-5 rounded-lg border border-slate-200 shadow-sm border-l-4 border-l-amber-500 hover:border-amber-600 hover:shadow-md transition group">
            <div class="flex justify-between items-start">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider group-hover:text-amber-700">{{ $kat->nama }}</div>
                <span class="text-[10px] text-amber-800 font-bold bg-amber-50 px-2 py-0.5 rounded border border-amber-200">Lihat Anggota &rarr;</span>
            </div>
            <div class="text-3xl font-bold text-emerald-800 mt-1">{{ number_format($kat->pegawai_count) }}</div>
            <div class="text-xs text-slate-500 mt-1">Klik untuk melihat anggota {{ $kat->nama }}</div>
        </a>
        @endforeach
    </div>

    <!-- Interactive Visual Charts (Chart.js) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Chart 1: Donut Komposisi Kategori -->
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-2 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Grafik Komposisi Kategori Pegawai</h3>
                <span class="text-[10px] bg-slate-100 px-2 py-0.5 rounded text-slate-600 font-semibold">Persentase</span>
            </div>
            <div class="relative h-64 flex items-center justify-center">
                <canvas id="kategoriChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Bar Distribusi Unit Kerja -->
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-2 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Grafik Distribusi Per Unit Kerja</h3>
                <span class="text-[10px] bg-slate-100 px-2 py-0.5 rounded text-slate-600 font-semibold">Jumlah Personel</span>
            </div>
            <div class="relative h-64">
                <canvas id="unitChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Grid Rekap Detail & Clickable Unit Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Column 1 & 2: Unit Kerja & Status -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Card Unit Kerja (Clickable Links) -->
            <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">
                        Rekapitulasi Unit Kerja & UPTD
                    </h3>
                    <span class="text-[11px] text-slate-500 font-medium">Klik unit kerja untuk melihat daftar anggotanya</span>
                </div>
                <div class="space-y-3">
                    @foreach($rekapUnitKerja as $unit)
                    <a href="{{ route('public.pegawai.index', ['unit_kerja_id' => $unit->id]) }}" class="block p-3 rounded-lg border border-slate-200 bg-slate-50 hover:bg-emerald-50 hover:border-emerald-300 transition group">
                        <div class="flex justify-between items-center text-xs font-medium text-slate-700 mb-1.5">
                            <span class="font-bold text-slate-900 group-hover:text-emerald-900">{{ $unit->nama }} <span class="text-slate-400 font-normal">({{ $unit->tipe }})</span></span>
                            <span class="font-bold text-emerald-900 bg-white px-2.5 py-1 rounded border border-slate-200 text-xs shadow-sm">
                                {{ $unit->pegawai_count }} Personel &rarr;
                            </span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div class="bg-emerald-800 h-2 rounded-full" style="width: {{ $totalPegawai > 0 ? ($unit->pegawai_count / $totalPegawai * 100) : 0 }}%"></div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Card Status Kepegawaian -->
            <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">
                    Status Kepegawaian
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($rekapStatus as $st)
                    <div class="bg-slate-50 border border-slate-200 p-3 rounded text-center">
                        <div class="text-xs text-slate-500 font-medium">{{ $st->nama }}</div>
                        <div class="text-lg font-bold text-slate-900 mt-0.5">{{ $st->pegawai_count }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

                <!-- Column 3: Informational Widgets -->
        <div class="space-y-6">
            
            <!-- Widget Usulan Pensiun Terdekat -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                    <div>
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Jadwal Pensiun Terdekat</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5">Urutan purna tugas paling dekat</p>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 bg-amber-100 text-amber-900 rounded-lg">BUP Otomatis</span>
                </div>
                @if($pensiunMendatang->count() > 0)
                <div class="divide-y divide-slate-100 text-xs">
                    @foreach($pensiunMendatang as $pensiun)
                    <div class="py-3 first:pt-1 last:pb-1">
                        <div class="flex items-start justify-between gap-2">
                            <a href="{{ route('public.pegawai.show', $pensiun->pegawai_id) }}" class="font-bold text-slate-900 hover:text-emerald-800 hover:underline block leading-snug">
                                {{ $pensiun->nama }}
                            </a>
                            <span class="px-2 py-0.5 bg-amber-50 text-amber-900 font-bold rounded text-[10px] border border-amber-200 flex-shrink-0">
                                {{ $pensiun->sisa_waktu }}
                            </span>
                        </div>
                        <div class="text-slate-500 text-[11px] mt-1">{{ $pensiun->jabatan }}</div>
                        <div class="text-amber-800 font-bold text-[11px] mt-1 flex items-center space-x-1">
                            <span>📅 TMT: {{ $pensiun->tmt_pensiun->translatedFormat('d F Y') }}</span>
                            <span class="text-slate-400 font-normal">({{ $pensiun->bup }} Thn)</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-xs text-slate-400 italic py-4 text-center">Belum ada data proyeksi pensiun.</p>
                @endif
            </div>

            <!-- Widget Usulan Kenaikan Pangkat PNS Terdekat -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                    <div>
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Kenaikan Pangkat PNS</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5">Periode KP terdekat (6x setahun BKN)</p>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 bg-emerald-100 text-emerald-900 rounded-lg">PNS</span>
                </div>
                @if($kpMendatang->count() > 0)
                <div class="divide-y divide-slate-100 text-xs">
                    @foreach($kpMendatang as $kp)
                    <div class="py-3 first:pt-1 last:pb-1">
                        <div class="flex items-start justify-between gap-2">
                            <a href="{{ route('public.pegawai.show', $kp->pegawai_id) }}" class="font-bold text-slate-900 hover:text-emerald-800 hover:underline block leading-snug">
                                {{ $kp->nama }}
                            </a>
                            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-900 font-bold rounded text-[10px] border border-emerald-200 flex-shrink-0">
                                {{ $kp->sisa_waktu }}
                            </span>
                        </div>
                        <div class="text-slate-500 text-[11px] mt-1">
                            Golongan: <span class="font-mono font-bold text-slate-800">{{ $kp->golongan ?? '-' }}</span> &bull; {{ $kp->jabatan }}
                        </div>
                        <div class="text-emerald-900 font-bold text-[11px] mt-1">
                            🎯 Periode BKN: {{ $kp->tmt_kp->translatedFormat('d F Y') }}
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-xs text-slate-400 italic py-4 text-center">Belum ada usulan kenaikan pangkat.</p>
                @endif
            </div>

        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Chart 1: Donut Kategori
        const katCtx = document.getElementById('kategoriChart').getContext('2d');
        new Chart(katCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($rekapKategori->pluck('nama')) !!},
                datasets: [{
                    data: {!! json_encode($rekapKategori->pluck('pegawai_count')) !!},
                    backgroundColor: ['#064e3b', '#d97706', '#2563eb', '#7c3aed', '#64748b'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { family: 'Inter', size: 11 } } }
                }
            }
        });

        // Chart 2: Bar Unit Kerja
        const unitCtx = document.getElementById('unitChart').getContext('2d');
        new Chart(unitCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($rekapUnitKerja->pluck('nama')) !!},
                datasets: [{
                    label: 'Jumlah Personel',
                    data: {!! json_encode($rekapUnitKerja->pluck('pegawai_count')) !!},
                    backgroundColor: '#166534',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                    x: { ticks: { font: { family: 'Inter', size: 10 } } }
                }
            }
        });
    });
</script>
@endsection
