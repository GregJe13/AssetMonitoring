@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="md:flex md:items-center md:justify-between mb-8">
    <div class="min-w-0 flex-1">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
            Contract Details
        </h2>
        <div class="mt-1 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap sm:space-x-4">
             <div class="mt-2 flex items-center text-sm text-gray-500">
                <span class="font-bold mr-1">Tenant:</span>
                <a href="{{ route('tenants.show', $contract->tenant) }}" class="text-indigo-600 hover:underline">{{ $contract->tenant->name }}</a>
            </div>
            <div class="mt-2 flex items-center text-sm text-gray-500">
                 <span class="font-bold mr-1">Status:</span>
                 <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset 
                    {{ $contract->is_expired ? 'bg-red-50 text-red-700 ring-red-600/10' : 'bg-green-50 text-green-700 ring-green-600/20' }}">
                    {{ $contract->is_expired ? 'Expired' : ucfirst($contract->status) }}
                </span>
                @if($contract->is_expired)
                    <span class="ml-2 text-red-600 font-medium">({{ $contract->days_expired }} hari sejak berakhir)</span>
                @else
                    <span class="ml-2 text-gray-400">({{ $contract->remaining_days }} hari tersisa)</span>
                @endif
            </div>
        </div>
    </div>
    <div class="mt-4 flex md:ml-4 md:mt-0 gap-3">
        <form action="{{ route('contracts.destroy', $contract) }}" method="POST">
            @csrf @method('DELETE')
            <button type="submit" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Delete</button>
        </form>
        <a href="{{ route('workflow.show', $contract) }}" class="inline-flex items-center gap-1 rounded-md bg-indigo-50 px-3 py-2 text-sm font-semibold text-indigo-600 shadow-sm ring-1 ring-inset ring-indigo-200 hover:bg-indigo-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            Workflow
        </a>
        <a href="{{ route('contracts.edit', $contract) }}" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Edit</a>
        <!-- Print / Export -->
        <a href="{{ route('contracts.print', $contract) }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Print / Export
        </a>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Left: Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Contract Terms -->
        <div class="overflow-hidden bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Contract Terms</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">No. PKS</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">
                            @if($contract->file_pks)
                                <a href="{{ route('contracts.file', [$contract, 'pks']) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 hover:underline inline-flex items-center gap-1">
                                    {{ $contract->no_pks ?? '-' }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                            @else
                                {{ $contract->no_pks ?? '-' }}
                            @endif
                        </dd>
                         <dt class="text-xs text-gray-400 mt-1">Date: {{ $contract->date_pks ? $contract->date_pks->format('d M Y') : '-' }}</dt>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">No. BAK</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">
                            @if($contract->file_bak)
                                <a href="{{ route('contracts.file', [$contract, 'bak']) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 hover:underline inline-flex items-center gap-1">
                                    {{ $contract->no_bak ?? '-' }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                            @else
                                {{ $contract->no_bak ?? '-' }}
                            @endif
                        </dd>
                        <dt class="text-xs text-gray-400 mt-1">Date: {{ $contract->date_bak ? $contract->date_bak->format('d M Y') : '-' }}</dt>
                    </div>
                    
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $contract->start_date->format('d F Y') }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">End Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $contract->end_date->format('d F Y') }}</dd>
                    </div>

                    <div class="sm:col-span-2 border-t border-gray-100 pt-4">
                         <div class="flex justify-between">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Total Rental Value</dt>
                                <dd class="mt-1 text-xl font-bold text-gray-900">Rp {{ number_format($contract->total_rental_value) }}</dd>
                            </div>
                            @if($contract->security_deposit > 0)
                            <div class="text-right">
                                <dt class="text-sm font-medium text-gray-500">Security Deposit</dt>
                                <dd class="mt-1 text-base font-semibold text-gray-900">Rp {{ number_format($contract->security_deposit) }}</dd>
                            </div>
                            @endif
                        </div>
                    </div>
                     <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Payment Terms</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($contract->is_upfront)
                                Full Payment Upfront
                            @else
                                Every {{ $contract->payment_interval_value }} {{ Str::plural($contract->payment_interval_unit, $contract->payment_interval_value) }}
                            @endif
                        </dd>
                    </div>
                    @if(!$contract->is_upfront && $contract->payment_start_date)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Payment Start Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $contract->payment_start_date->format('d F Y') }}
                            @if($contract->payment_start_date->ne($contract->start_date))
                                <span class="text-xs text-gray-500 ml-2">(berbeda dengan tanggal mulai kontrak)</span>
                            @endif
                        </dd>
                    </div>
                    @endif
                     <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Perwakilan Pihak Pertama</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $contract->pihak_pertama }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Perwakilan Pihak Kedua</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $contract->pihak_kedua }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Payment Schedule -->
        <div class="overflow-hidden bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Payment Schedule</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6">Period</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Due Date</th>
                        <th scope="col" class="px-3 py-3.5 text-right text-xs font-medium uppercase tracking-wide text-gray-500">Amount</th>
                        <th scope="col" class="px-3 py-3.5 text-center text-xs font-medium uppercase tracking-wide text-gray-500">Status</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($contract->payments as $payment)
                    <tr class="{{ $payment->payment_status == 'overdue' ? 'bg-red-50' : '' }}">
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">#{{ $payment->period_number }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm {{ $payment->payment_status == 'overdue' ? 'text-red-700 font-bold' : 'text-gray-500' }}">
                            {{ $payment->due_date->format('d M Y') }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-right">
                            Rp {{ number_format($payment->amount_due) }}
                        </td>
                         <td class="whitespace-nowrap px-3 py-4 text-sm text-center">
                            @if($payment->payment_status == 'paid')
                                <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Paid</span>
                                @if($payment->paid_at)
                                    <div class="text-xs text-gray-500 mt-1">Dibayar: {{ $payment->paid_at->format('d/m/Y') }}</div>
                                @endif
                            @elseif($payment->payment_status == 'overdue')
                                <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">Overdue</span>
                            @else
                                <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">Pending</span>
                            @endif
                        </td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            @if($payment->payment_status != 'paid')
                                <form action="{{ route('payments.update', $payment) }}" method="POST">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="action" value="mark_as_paid">
                                    <button type="submit" class="text-indigo-600 hover:text-indigo-900">Mark Paid</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right: Rented Assets -->
    <div>
        <div class="overflow-hidden bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Rented Assets</h3>
                @php
                    $rentedAssets = $contract->assets->filter(fn($a) => $a->pivot->rented_area_sqm > 0);
                @endphp
                <p class="mt-1 text-sm text-gray-500">{{ $rentedAssets->count() }} units attached.</p>
            </div>
            <ul role="list" class="divide-y divide-gray-100">
                @forelse($rentedAssets as $asset)
                @php
                    $isAssetActive = now()->between($contract->start_date, $contract->end_date);
                @endphp
                <li class="flex gap-x-4 py-5 px-6">
                    <div class="flex-auto">
                        <div class="flex items-baseline justify-between gap-x-4">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{ $asset->name }}</p>
                            <p class="flex-none text-xs text-gray-600">{{ $asset->id_gedung }}</p>
                        </div>
                        <div class="mt-2 flex items-center gap-4 text-sm flex-wrap">
                            <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                                Rented: {{ number_format($asset->pivot->rented_area_sqm, 0) }} m²
                            </span>
                            <span class="text-gray-500 text-xs">of {{ number_format($asset->area_sqm, 0) }} m² total</span>
                            @if($isAssetActive)
                                <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-500 ring-1 ring-inset ring-gray-400/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                </li>
                @empty
                <li class="p-6 text-center text-sm text-gray-500">No assets attached.</li>
                @endforelse
            </ul>
        </div>

        <!-- Amendments -->
        <div class="overflow-hidden bg-white shadow sm:rounded-lg mt-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold leading-6 text-gray-900">Amandemen</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ $contract->amendments->count() }} amandemen terkait.</p>
                </div>
                <a href="{{ route('amendments.create', ['tenant_id' => $contract->tenant_id, 'contract_id' => $contract->id]) }}" class="inline-flex items-center gap-1 rounded-md bg-indigo-50 px-2.5 py-1.5 text-xs font-semibold text-indigo-600 ring-1 ring-inset ring-indigo-200 hover:bg-indigo-100 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Buat Amandemen
                </a>
            </div>
            <ul role="list" class="divide-y divide-gray-100">
                @forelse($contract->amendments->sortByDesc('amendment_number') as $amendment)
                <li class="px-6 py-4 hover:bg-gray-50 transition-colors">
                    <a href="{{ route('amendments.show', $amendment) }}" class="block">
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold">{{ $amendment->amendment_number }}</span>
                                <span class="text-sm font-medium text-gray-900">{{ $amendment->no_amendment ?? 'AMD #' . $amendment->amendment_number }}</span>
                            </div>
                            <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium ring-1 ring-inset
                                {{ $amendment->is_expired ? 'bg-red-50 text-red-700 ring-red-600/10' : 'bg-green-50 text-green-700 ring-green-600/20' }}">
                                {{ $amendment->is_expired ? 'Expired' : ucfirst($amendment->status) }}
                            </span>
                        </div>
                        <div class="ml-8 space-y-1">
                            <p class="text-xs text-gray-500">
                                {{ $amendment->new_start_date->format('d M Y') }} — {{ $amendment->new_end_date->format('d M Y') }}
                            </p>
                            <p class="text-xs font-medium text-gray-700">Rp {{ number_format($amendment->total_rental_value) }}</p>
                            @if($amendment->assets->count() > 0)
                            <div class="flex flex-wrap gap-1 mt-1">
                                @foreach($amendment->assets as $asset)
                                <span class="inline-flex items-center rounded-md bg-blue-50 px-1.5 py-0.5 text-xs text-blue-700 ring-1 ring-inset ring-blue-600/10">
                                    {{ $asset->name }} ({{ number_format($asset->pivot->rented_area_sqm, 0) }} m²)
                                </span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </a>
                </li>
                @empty
                <li class="p-6 text-center text-sm text-gray-500">Belum ada amandemen untuk kontrak ini.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
