@extends('layouts.admin')

@section('title', 'Audit Log Aktivitas - Admin SIMPEG')

@section('content')
<div class="space-y-5 text-xs">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Audit Log Aktivitas Admin</h2>
            <p class="text-slate-500">Catatan riwayat perubahan data, pengarsipan, pemulihan, dan aksi pengelola kepegawaian.</p>
        </div>
    </div>

        <!-- Filter Card -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm">
        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            
            <!-- Search Input with Magnifier Icon -->
            <div class="md:col-span-6 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari aktivitas, nama admin, atau deskripsi log..." 
                       class="w-full text-xs pl-10 pr-3.5 py-2.5 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-slate-50/50 shadow-2xs transition">
            </div>

            <!-- Action Type Dropdown -->
            <div class="md:col-span-3">
                <select name="action" class="w-full text-xs py-2.5 px-3 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-white shadow-2xs transition">
                    <option value="">-- Semua Jenis Aksi --</option>
                    <option value="CREATE" {{ request('action') == 'CREATE' ? 'selected' : '' }}>CREATE (Tambah Data)</option>
                    <option value="UPDATE" {{ request('action') == 'UPDATE' ? 'selected' : '' }}>UPDATE (Perbarui Data)</option>
                    <option value="DELETE" {{ request('action') == 'DELETE' ? 'selected' : '' }}>DELETE (Arsip / Hapus)</option>
                    <option value="RESTORE" {{ request('action') == 'RESTORE' ? 'selected' : '' }}>RESTORE (Pulihkan Data)</option>
                    <option value="IMPORT" {{ request('action') == 'IMPORT' ? 'selected' : '' }}>IMPORT (Sinkronisasi Excel)</option>
                </select>
            </div>

            <!-- Submit & Reset Buttons -->
            <div class="md:col-span-3 flex items-center gap-2">
                <button type="submit" class="flex-1 py-2.5 px-4 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center justify-center space-x-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Filter Log</span>
                </button>
                @if(request()->hasAny(['search', 'action']))
                    <a href="{{ route('admin.activity-logs.index') }}" title="Reset Filter" class="py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl border border-slate-200 transition flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden text-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200 text-slate-700 font-semibold uppercase text-[11px]">
                        <th class="py-3 px-4" width="160">Waktu</th>
                        <th class="py-3 px-4" width="180">Admin Pengelola</th>
                        <th class="py-3 px-4" width="100">Aksi</th>
                        <th class="py-3 px-4">Deskripsi Aktivitas</th>
                        <th class="py-3 px-4" width="120">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">
                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="py-3 px-4 font-semibold text-slate-900">
                            {{ $log->user_name }}
                        </td>
                        <td class="py-3 px-4">
                            @if($log->action === 'CREATE')
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 font-bold rounded text-[10px]">CREATE</span>
                            @elseif($log->action === 'UPDATE')
                                <span class="px-2 py-0.5 bg-sky-100 text-sky-800 font-bold rounded text-[10px]">UPDATE</span>
                            @elseif($log->action === 'DELETE')
                                <span class="px-2 py-0.5 bg-rose-100 text-rose-800 font-bold rounded text-[10px]">DELETE</span>
                            @elseif($log->action === 'RESTORE')
                                <span class="px-2 py-0.5 bg-amber-100 text-amber-800 font-bold rounded text-[10px]">RESTORE</span>
                            @else
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-800 font-bold rounded text-[10px]">{{ $log->action }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-slate-800 font-medium">
                            {{ $log->description }}
                        </td>
                        <td class="py-3 px-4 text-slate-400 font-mono text-[11px]">
                            {{ $log->ip_address }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-slate-500 italic">Belum ada catatan aktivitas admin.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
