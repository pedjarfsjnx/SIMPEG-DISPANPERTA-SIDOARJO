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

    <!-- Filter -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aktivitas atau nama admin..." class="text-xs rounded border-slate-300">
            
            <select name="action" class="text-xs rounded border-slate-300">
                <option value="">Semua Aksi</option>
                <option value="CREATE" {{ request('action') == 'CREATE' ? 'selected' : '' }}>CREATE (Tambah)</option>
                <option value="UPDATE" {{ request('action') == 'UPDATE' ? 'selected' : '' }}>UPDATE (Edit)</option>
                <option value="DELETE" {{ request('action') == 'DELETE' ? 'selected' : '' }}>DELETE (Arsip)</option>
                <option value="RESTORE" {{ request('action') == 'RESTORE' ? 'selected' : '' }}>RESTORE (Pulih)</option>
                <option value="IMPORT" {{ request('action') == 'IMPORT' ? 'selected' : '' }}>IMPORT (Excel)</option>
            </select>

            <div class="flex gap-2">
                <button type="submit" class="w-full bg-emerald-800 text-white font-semibold text-xs py-2 rounded">Filter Log</button>
                <a href="{{ route('admin.activity-logs.index') }}" class="px-3 bg-slate-200 text-slate-700 font-semibold text-xs py-2 rounded">Reset</a>
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
