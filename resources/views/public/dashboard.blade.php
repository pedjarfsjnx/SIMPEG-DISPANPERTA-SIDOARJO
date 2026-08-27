@extends('layouts.public')

@section('title', 'Dashboard Kepegawaian - SIMPEG Dinas Pangan dan Pertanian Kabupaten Sidoarjo')

@section('content')
<div class="space-y-5">

    <!-- 1. Header & Statistical Overview Section -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm space-y-5">
        
        <!-- Standard Page Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-xl font-bold text-slate-900">
                    Statistik & Komposisi Aparatur
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Data kepegawaian resmi Dinas Pangan dan Pertanian Kabupaten Sidoarjo yang diperbarui secara langsung.
                </p>
            </div>

            <a href="{{ route('public.pegawai.index') }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-semibold text-xs rounded-xl shadow-xs transition whitespace-nowrap">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span>Lihat Direktori Pegawai &rarr;</span>
            </a>
        </div>

        <!-- Integrated Key Metrics Hub -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">
            
            <!-- Main Figure: Total Pegawai -->
            <div class="lg:col-span-4 bg-slate-50/80 rounded-xl p-4 border border-slate-200/80 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <span>Total Pegawai Aktif</span>
                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 rounded text-[10px] font-semibold">Aktif</span>
                    </div>
                    <div class="flex items-baseline gap-2 mt-2">
                        <span class="text-3xl font-bold text-slate-900">
                            {{ number_format($totalPegawai) }}
                        </span>
                        <span class="text-xs font-medium text-slate-500">Personel</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                        Tersebar di Kantor Induk dan 5 Unit Pelaksana Teknis Daerah (UPTD).
                    </p>
                </div>

                <div class="pt-3 mt-3 border-t border-slate-200 flex items-center justify-between text-xs">
                    <a href="{{ route('public.pegawai.index') }}" class="font-semibold text-emerald-800 hover:text-emerald-900">
                        Buka Semua Pegawai &rarr;
                    </a>
                    <span class="text-[11px] text-slate-400">Database Resmi</span>
                </div>
            </div>

            <!-- 5 Categories Structured Grid -->
            <div class="lg:col-span-8 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Rincian Kategori Kepegawaian
                    </span>
                    <span class="text-[11px] text-slate-500">Klik baris untuk memfilter</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                    @foreach($rekapKategori as $kat)
                    @php
                        $percent = $totalPegawai > 0 ? round(($kat->pegawai_count / $totalPegawai) * 100, 1) : 0;
                        $theme = match(strtoupper($kat->nama)) {
                            'PNS' => ['badge' => 'bg-emerald-50 text-emerald-800 border-emerald-200', 'bar' => 'bg-emerald-700'],
                            'PPPK' => ['badge' => 'bg-blue-50 text-blue-800 border-blue-200', 'bar' => 'bg-blue-600'],
                            'PPPK PARUH WAKTU' => ['badge' => 'bg-amber-50 text-amber-800 border-amber-200', 'bar' => 'bg-amber-500'],
                            'SWAKELOLA' => ['badge' => 'bg-purple-50 text-purple-800 border-purple-200', 'bar' => 'bg-purple-600'],
                            'OUTSOURCING' => ['badge' => 'bg-slate-100 text-slate-700 border-slate-200', 'bar' => 'bg-slate-500'],
                            default => ['badge' => 'bg-slate-100 text-slate-700 border-slate-200', 'bar' => 'bg-slate-500']
                        };
                    @endphp
                    <a href="{{ route('public.pegawai.index', ['kategori_id' => $kat->id]) }}" 
                       class="group block p-3 rounded-xl border border-slate-200/80 bg-slate-50/50 hover:bg-emerald-50/60 hover:border-emerald-300 transition duration-150">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded border {{ $theme['badge'] }}">
                                {{ $kat->nama }}
                            </span>
                            <span class="text-[11px] font-medium text-slate-400">{{ $percent }}%</span>
                        </div>
                        <div class="flex items-baseline justify-between mt-1">
                            <span class="text-xl font-bold text-slate-900 group-hover:text-emerald-800 transition">
                                {{ number_format($kat->pegawai_count) }}
                            </span>
                            <span class="text-[11px] text-slate-500 group-hover:text-emerald-800 font-medium">Lihat &rarr;</span>
                        </div>
                        <div class="w-full bg-slate-200/80 rounded-full h-1.5 mt-2 overflow-hidden">
                            <div class="{{ $theme['bar'] }} h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

    <!-- 2. Interactive Charts Deck (Visual Analytics) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        
        <!-- Chart 1: Donut Komposisi Kategori -->
        <div class="lg:col-span-5 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Komposisi Aparatur</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Proporsi persentase antar kategori</p>
                </div>
                <span class="text-[10px] bg-slate-100 text-slate-700 font-semibold px-2 py-0.5 rounded">
                    Persentase
                </span>
            </div>
            <div class="relative h-64 flex items-center justify-center my-2">
                <canvas id="kategoriChart"></canvas>
            </div>
            <div class="text-[11px] text-slate-500 text-center bg-slate-50 py-2 px-3 rounded-lg border border-slate-100">
                Arahkan kursor pada grafik untuk melihat rincian jumlah dan rasio.
            </div>
        </div>

        <!-- Chart 2: Bar Distribusi Unit Kerja -->
        <div class="lg:col-span-7 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Persebaran Unit Kerja & UPTD</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Alokasi personel di Dinas Induk dan UPTD wilayah</p>
                </div>
                <span class="text-[10px] bg-slate-100 text-slate-700 font-semibold px-2 py-0.5 rounded">
                    Jumlah Personel
                </span>
            </div>
            <div class="relative h-64 my-2">
                <canvas id="unitChart"></canvas>
            </div>
            <div class="text-[11px] text-slate-500 text-center bg-slate-50 py-2 px-3 rounded-lg border border-slate-100">
                Grafik menampilkan total aparatur pada masing-masing unit operasional.
            </div>
        </div>

    </div>


    <!-- 3. Rincian Unit Kerja, Status & Pengingat Administrasi -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        
        <!-- Left Section: Unit Kerja Table (7 Cols) -->
        <div class="lg:col-span-7 space-y-5">
            
            <!-- Unit Kerja List -->
            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                    <div>
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                            Rincian Distribusi Unit Kerja & UPTD
                        </h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Pilih unit kerja untuk melihat daftar personel lengkap</p>
                    </div>
                    <span class="text-[11px] font-bold text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
                        {{ $rekapUnitKerja->count() }} Unit Terdata
                    </span>
                </div>

                <div class="space-y-2">
                    @foreach($rekapUnitKerja as $unit)
                    @php
                        $pctUnit = $totalPegawai > 0 ? round(($unit->pegawai_count / $totalPegawai) * 100, 1) : 0;
                    @endphp
                    <a href="{{ route('public.pegawai.index', ['unit_kerja_id' => $unit->id]) }}" 
                       class="group block p-3 rounded-xl border border-slate-200/70 bg-slate-50/60 hover:bg-emerald-50/70 hover:border-emerald-300 transition duration-150">
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full {{ $unit->tipe === 'dinas' ? 'bg-emerald-700' : 'bg-amber-500' }}"></span>
                                <span class="text-xs font-bold text-slate-800 group-hover:text-emerald-900 transition">
                                    {{ $unit->nama }}
                                </span>
                                <span class="text-[10px] px-1.5 py-0.2 bg-white border border-slate-200 text-slate-500 rounded uppercase font-medium">
                                    {{ $unit->tipe }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-900 group-hover:text-emerald-900">
                                    {{ $unit->pegawai_count }} Personel
                                </span>
                                <span class="text-[11px] text-slate-400 font-mono">({{ $pctUnit }}%)</span>
                            </div>
                        </div>
                        <div class="w-full bg-slate-200/80 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-emerald-700 h-1.5 rounded-full" style="width: {{ $pctUnit }}%"></div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Status Kepegawaian Mini Grid -->
            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                        Status Kepegawaian
                    </h3>
                    <span class="text-[11px] text-slate-400">Kondisi status saat ini</span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach($rekapStatus as $st)
                    <div class="bg-slate-50/80 border border-slate-200/80 p-3 rounded-xl text-center">
                        <div class="text-[11px] font-semibold text-slate-500 uppercase">{{ $st->nama }}</div>
                        <div class="text-xl font-black text-slate-900 mt-0.5">{{ number_format($st->pegawai_count) }}</div>
                        <div class="text-[10px] text-slate-400">Personel</div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Right Section: Upcoming Alerts / Timeline Widgets (5 Cols) -->
        <div class="lg:col-span-5 space-y-5">
            
            <!-- Widget Usulan Pensiun Terdekat -->
            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                    <div>
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Jadwal Pensiun Terdekat</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Proyeksi purna tugas berdasarkan BUP</p>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 bg-amber-50 text-amber-900 border border-amber-200 rounded-md">
                        BUP Otomatis
                    </span>
                </div>

                @if($pensiunMendatang->count() > 0)
                <div class="divide-y divide-slate-100 text-xs">
                    @foreach($pensiunMendatang as $pensiun)
                    <div class="py-2.5 first:pt-0 last:pb-0 group">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <a href="{{ route('public.pegawai.show', $pensiun->pegawai_id) }}" 
                                   class="font-bold text-slate-900 hover:text-emerald-800 transition block leading-snug">
                                    {{ $pensiun->nama }}
                                </a>
                                <div class="text-slate-500 text-[11px] mt-0.5">{{ $pensiun->jabatan }}</div>
                            </div>
                            <span class="px-2 py-0.5 bg-amber-50 text-amber-900 font-bold rounded text-[10px] border border-amber-200 flex-shrink-0">
                                {{ $pensiun->sisa_waktu }}
                            </span>
                        </div>
                        <div class="text-amber-800 font-bold text-[11px] mt-1 flex items-center space-x-1.5">
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            <span>TMT: {{ $pensiun->tmt_pensiun->translatedFormat('d F Y') }}</span>
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
            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                    <div>
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Kenaikan Pangkat PNS</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Siklus periodik 6x setahun BKN</p>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 bg-emerald-50 text-emerald-900 border border-emerald-200 rounded-md">
                        PNS
                    </span>
                </div>

                @if($kpMendatang->count() > 0)
                <div class="divide-y divide-slate-100 text-xs">
                    @foreach($kpMendatang as $kp)
                    <div class="py-2.5 first:pt-0 last:pb-0 group">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <a href="{{ route('public.pegawai.show', $kp->pegawai_id) }}" 
                                   class="font-bold text-slate-900 hover:text-emerald-800 transition block leading-snug">
                                    {{ $kp->nama }}
                                </a>
                                <div class="text-slate-500 text-[11px] mt-0.5">
                                    Gol: <span class="font-mono font-bold text-slate-800">{{ $kp->golongan ?? '-' }}</span> &bull; {{ $kp->jabatan }}
                                </div>
                            </div>
                            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-900 font-bold rounded text-[10px] border border-emerald-200 flex-shrink-0">
                                {{ $kp->sisa_waktu }}
                            </span>
                        </div>
                        <div class="text-emerald-800 font-bold text-[11px] mt-1 flex items-center space-x-1.5">
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                            <span>Periode BKN: {{ $kp->tmt_kp->translatedFormat('d F Y') }}</span>
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
                    backgroundColor: ['#047857', '#3b82f6', '#f59e0b', '#8b5cf6', '#64748b'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'bottom', 
                        labels: { 
                            font: { family: 'Inter', size: 11 },
                            padding: 10,
                            usePointStyle: true,
                            boxWidth: 8
                        } 
                    }
                },
                cutout: '65%'
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
                    backgroundColor: '#15803d',
                    hoverBackgroundColor: '#166534',
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        ticks: { precision: 0, font: { family: 'Inter', size: 10 } },
                        grid: { color: '#f1f5f9' }
                    },
                    x: { 
                        ticks: { font: { family: 'Inter', size: 10 } },
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection

