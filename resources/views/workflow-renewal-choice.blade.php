@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Workflow Perpanjangan Selesai</h2>
        <p class="mt-2 text-sm text-gray-500">
            Kontrak <strong>{{ $contract->no_pks ?? $contract->no_bak ?? '#'.$contract->id }}</strong>
            — Tenant: <strong>{{ $contract->tenant->name }}</strong>
        </p>
        <p class="mt-1 text-sm text-gray-400">Silakan pilih langkah selanjutnya untuk perpanjangan kontrak ini.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <!-- Option 1: New Contract -->
        <a href="{{ route('contracts.create', ['tenant_id' => $contract->tenant_id]) }}"
           class="group block bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl p-8 hover:ring-2 hover:ring-indigo-500 hover:shadow-md transition-all">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-indigo-100 group-hover:bg-indigo-200 transition-colors mb-4">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-700">Buat Kontrak Baru</h3>
                <p class="mt-2 text-sm text-gray-500">Buat kontrak baru untuk tenant ini dengan ketentuan yang baru sepenuhnya.</p>
            </div>
        </a>

        <!-- Option 2: Create Amendment -->
        <a href="{{ route('amendments.create', ['tenant_id' => $contract->tenant_id, 'contract_id' => $contract->id]) }}"
           class="group block bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl p-8 hover:ring-2 hover:ring-amber-500 hover:shadow-md transition-all">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-amber-100 group-hover:bg-amber-200 transition-colors mb-4">
                    <svg class="w-7 h-7 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-amber-700">Buat Amandemen</h3>
                <p class="mt-2 text-sm text-gray-500">Buat amandemen dari kontrak existing untuk memperpanjang atau mengubah ketentuan.</p>
            </div>
        </a>
    </div>

    <div class="mt-8 text-center">
        <a href="{{ route('workflow.show', $contract) }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali ke Workflow</a>
    </div>
</div>
@endsection
