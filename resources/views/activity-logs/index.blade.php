@extends('layouts.app')

@section('title', 'Activity Log - INTI Asset Monitoring')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <p class="text-sm font-medium text-indigo-600">Monitoring</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">Activity Log</h1>
        <p class="mt-1 text-sm text-gray-500">Pantau aktivitas create, edit, dan delete yang dilakukan oleh worker untuk penilaian KPI.</p>
    </div>

    <!-- Filters -->
    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 p-5">
        <form action="{{ route('activity-logs.index') }}" method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5 items-end">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    class="block w-full rounded-md border-0 py-2 pl-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                    placeholder="Cari deskripsi...">
            </div>
            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">User</label>
                <select name="user_id" id="user_id"
                    class="block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                    <option value="">Semua User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="action" class="block text-sm font-medium text-gray-700 mb-1">Aksi</label>
                <select name="action" id="action"
                    class="block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                    <option value="">Semua Aksi</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                            {{ ucfirst($action) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                    class="block w-full rounded-md border-0 py-2 pl-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                    <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                    </svg>
                    Filter
                </button>
                @if(request()->hasAny(['search', 'user_id', 'action', 'date_from']))
                <a href="{{ route('activity-logs.index') }}"
                    class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Log Table -->
    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Waktu</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">User</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Aksi</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Target</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Deskripsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm text-gray-500">
                            <div>
                                <p class="font-medium text-gray-900">{{ $log->created_at->format('d M Y') }}</p>
                                <p class="text-xs text-gray-400">{{ $log->created_at->format('H:i:s') }}</p>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4">
                            <div class="flex items-center gap-2">
                                <div class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-indigo-100 text-indigo-600 text-xs font-bold">
                                    {{ strtoupper(substr($log->user->name ?? '?', 0, 2)) }}
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $log->user->name ?? 'Deleted User' }}</span>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4">
                            @php
                                $actionBadge = match($log->action) {
                                    'created' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                    'updated' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                    'deleted' => 'bg-red-50 text-red-700 ring-red-600/20',
                                    'login' => 'bg-violet-50 text-violet-700 ring-violet-600/20',
                                    'logout' => 'bg-gray-100 text-gray-600 ring-gray-500/20',
                                    default => 'bg-gray-100 text-gray-600 ring-gray-500/20',
                                };
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $actionBadge }}">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4">
                            <span class="text-sm text-gray-900">{{ $log->model_name }}</span>
                            @if($log->model_id)
                                <span class="text-xs text-gray-400 ml-1">#{{ $log->model_id }}</span>
                            @endif
                        </td>
                        <td class="px-3 py-4 text-sm text-gray-500 whitespace-normal">
                            {{ $log->description }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Belum ada log aktivitas</p>
                                    <p class="mt-1 text-xs text-gray-500">Log akan muncul saat user melakukan aksi di sistem.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($logs->hasPages())
        <div class="border-t border-gray-200 px-6 py-4">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
