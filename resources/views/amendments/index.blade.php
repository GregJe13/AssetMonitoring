@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Amendments</h2>
            <p class="mt-2 text-sm text-gray-500">Daftar amandemen kontrak.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('amendments.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" /></svg>
                Buat Amandemen
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-md bg-green-50 p-4">
            <div class="flex">
                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Search -->
    <div class="mb-6">
        <form method="GET" action="{{ route('amendments.index') }}">
            <div class="relative">
                <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor amandemen, kontrak, atau tenant..." class="block w-full rounded-md border-0 py-2 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
        </form>
    </div>

    <!-- Desktop: Table (hidden on mobile) -->
    <div class="hidden sm:block bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Amandemen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontrak Induk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ke-</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode Baru</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($amendments as $amendment)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $amendment->no_amendment }}</div>
                            <div class="text-xs text-gray-500">{{ $amendment->date_amendment->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $amendment->contract->tenant->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $amendment->contract->no_pks ?? $amendment->contract->no_bak ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-700">
                                #{{ $amendment->amendment_number }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $amendment->new_start_date->format('d M Y') }} — {{ $amendment->new_end_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                {{ $amendment->status === 'active' ? 'bg-green-100 text-green-700' : ($amendment->status === 'expired' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700') }}">
                                {{ ucfirst($amendment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <a href="{{ route('amendments.show', $amendment) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                            Belum ada amandemen.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile: Card List (shown only on mobile) -->
    <div class="sm:hidden space-y-3">
        @forelse($amendments as $amendment)
        <a href="{{ route('amendments.show', $amendment) }}" class="block bg-white shadow-sm ring-1 ring-gray-900/5 rounded-xl p-4 hover:bg-gray-50 transition-colors">
            <div class="flex items-start justify-between gap-3 mb-2">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $amendment->no_amendment }}</p>
                    <p class="text-xs text-gray-500">{{ $amendment->date_amendment->format('d M Y') }}</p>
                </div>
                <span class="inline-flex shrink-0 items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                    {{ $amendment->status === 'active' ? 'bg-green-100 text-green-700' : ($amendment->status === 'expired' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700') }}">
                    {{ ucfirst($amendment->status) }}
                </span>
            </div>
            <div class="space-y-1.5 text-xs text-gray-500">
                <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span class="font-medium text-gray-700">{{ $amendment->contract->tenant->name ?? '-' }}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>{{ $amendment->contract->no_pks ?? $amendment->contract->no_bak ?? '-' }}</span>
                    <span class="inline-flex items-center rounded-full bg-indigo-100 px-1.5 py-0.5 text-xs font-medium text-indigo-700">
                        AMD #{{ $amendment->amendment_number }}
                    </span>
                </div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="font-mono">{{ $amendment->new_start_date->format('d M Y') }} — {{ $amendment->new_end_date->format('d M Y') }}</span>
                </div>
            </div>
        </a>
        @empty
        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-xl px-6 py-12 text-center text-sm text-gray-500">
            Belum ada amandemen.
        </div>
        @endforelse
    </div>
</div>
@endsection
