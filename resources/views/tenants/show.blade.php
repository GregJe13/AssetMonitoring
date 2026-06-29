@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="md:flex md:items-center md:justify-between mb-8">
    <div class="min-w-0 flex-1">
        <div class="flex items-center gap-3 mb-1">
            <a href="{{ route('tenants.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-indigo-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Tenants
            </a>
        </div>
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">{{ $tenant->name }}</h2>
        <div class="mt-1 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap sm:space-x-6">
            <div class="mt-2 flex items-center text-sm text-gray-500">
                <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 2c-1.716 0-3.408.106-5.07.31C3.806 2.45 3 3.414 3 4.517V17.25a.75.75 0 001.075.676L10 15.082l5.925 2.844A.75.75 0 0017 17.25V4.517c0-1.103-.806-2.068-1.93-2.207A41.403 41.403 0 0010 2z" clip-rule="evenodd" /></svg>
                ID: {{ $tenant->id_tenant ?? 'N/A' }}
            </div>
            <div class="mt-2 flex items-center text-sm text-gray-500">
                <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 2a6 6 0 00-6 6c0 1.887-.454 3.665-1.257 5.234a.75.75 0 00.515 1.076 32.91 32.91 0 003.256.508 8.688 8.688 0 013.486 3.168 1.5 1.5 0 012.874 0 8.688 8.688 0 013.486-3.168 32.909 32.909 0 003.256-.508.75.75 0 00.515-1.076A11.448 11.448 0 0016 8a6 6 0 00-6-6z" clip-rule="evenodd" /></svg>
                Since {{ $partnershipSummary['first_contract_date'] ? \Carbon\Carbon::parse($partnershipSummary['first_contract_date'])->format('M Y') : 'N/A' }}
            </div>
        </div>
    </div>
    <div class="mt-4 flex md:ml-4 md:mt-0 gap-3">
        <form action="{{ route('tenants.destroy', $tenant) }}" method="POST">
            @csrf @method('DELETE')
            <button type="submit" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Delete</button>
        </form>
        <a href="{{ route('tenants.edit', $tenant) }}" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Edit</a>
        <a href="{{ route('contracts.create', ['tenant_id' => $tenant->id]) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Create Contract</a>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Left Column: Tenant Info & Stats -->
    <div class="space-y-6">
        <!-- Contact Info -->
        <div class="overflow-hidden bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Contact Information</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">PIC Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $tenant->pic ?? '-' }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $tenant->phone ?? '-' }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">PIC Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $tenant->pic_phone ?? '-' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $tenant->email ?? '-' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">NPWP</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $tenant->npwp ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Partnership Stats -->
        <div class="overflow-hidden bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-gray-50">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Partnership Overview</h3>
                <p class="mt-1 text-sm text-gray-500">Summary of cooperation history.</p>
            </div>
            <div class="px-4 py-5 sm:p-6 space-y-4">
                <div>
                    <div class="text-sm text-gray-500">Total Duration</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $partnershipSummary['total_partnership_years'] }} Years</div>
                    <div class="text-xs text-gray-400">({{ $partnershipSummary['total_partnership_days'] }} Days)</div>
                </div>
                <div class="grid grid-cols-2 gap-4 border-t border-gray-100 pt-4">
                     <div>
                        <div class="text-sm text-gray-500">Total Contracts</div>
                        <div class="text-lg font-semibold text-gray-900">{{ $partnershipSummary['total_contracts'] }}</div>
                     </div>
                     <div>
                        <div class="text-sm text-gray-500">Active</div>
                        <div class="text-lg font-semibold text-green-600">{{ $partnershipSummary['active_contracts'] }}</div>
                     </div>
                     <div>
                        <div class="text-sm text-gray-500">Completed</div>
                        <div class="text-lg font-semibold text-gray-900">{{ $partnershipSummary['completed_contracts'] }}</div>
                     </div>
                </div>
                <div class="border-t border-gray-100 pt-4">
                     <div class="text-sm text-gray-500">Total Contribution</div>
                     <div class="text-lg font-bold text-emerald-600">Rp {{ number_format($partnershipSummary['total_rental_value']) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Contract History Log -->
    <div class="lg:col-span-2">
        <div class="overflow-hidden bg-white shadow sm:rounded-lg">
             <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Contract History Log</h3>
                <p class="mt-1 text-sm text-gray-500">Complete timeline of contracts.</p>
            </div>
            <ul role="list" class="divide-y divide-gray-100">
                @forelse($contractHistory as $contract)
                @php
                    $paidCount = $contract->payments->where('payment_status', 'paid')->count();
                    $pendingCount = $contract->payments->where('payment_status', 'pending')->count();
                    $overdueCount = $contract->payments->where('payment_status', 'overdue')->count();
                    $totalPayments = $contract->payments->count();
                    $amendmentCount = $contract->amendments->count();

                    // Calculate duration
                    $startDate = $contract->start_date;
                    $endDate = $contract->end_date;
                    $diffMonths = $startDate->diffInMonths($endDate);
                    $years = intdiv($diffMonths, 12);
                    $months = $diffMonths % 12;
                    $durationText = '';
                    if ($years > 0) $durationText .= $years . ' yr' . ($years > 1 ? 's' : '');
                    if ($months > 0) $durationText .= ($years > 0 ? ' ' : '') . $months . ' mo' . ($months > 1 ? 's' : '');
                    if (!$durationText) $durationText = $startDate->diffInDays($endDate) . ' days';
                @endphp
                <li class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between gap-x-6 py-1">
                        <!-- Left: Status & Main Info -->
                        <div class="min-w-0 flex-1">
                            <div class="flex items-start gap-x-3 mb-1">
                                <p class="text-sm font-semibold leading-6 text-gray-900">
                                    {{ $contract->no_pks ?? $contract->no_bak ?? 'No Reference' }}
                                </p>
                                <p class="rounded-md whitespace-nowrap mt-0.5 px-1.5 py-0.5 text-xs font-medium ring-1 ring-inset 
                                    {{ $contract->status === 'active' ? 'text-green-700 bg-green-50 ring-green-600/20' : '' }}
                                    {{ $contract->status === 'expired' ? 'text-gray-600 bg-gray-100 ring-gray-500/10' : '' }}
                                    {{ $contract->status === 'draft' ? 'text-yellow-800 bg-yellow-50 ring-yellow-600/20' : '' }}">
                                    {{ ucfirst($contract->status) }}
                                </p>
                            </div>

                            <!-- Date Range & Duration -->
                            <div class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500 font-mono">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                {{ $contract->start_date->format('d M Y') }} - {{ $contract->end_date->format('d M Y') }}
                                <span class="text-gray-400">·</span>
                                <span class="text-gray-400 font-sans">{{ $durationText }}</span>
                            </div>

                            <!-- Assets -->
                            <div class="mt-2 text-sm text-gray-600">
                                <span class="font-medium text-gray-500">Assets:</span>
                                {{ $contract->assets->pluck('name')->implode(', ') ?: '-' }}
                            </div>

                            <!-- Payment & Amendment Summary -->
                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                @if($totalPayments > 0)
                                    <div class="inline-flex items-center gap-1.5 rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-200">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Payments:
                                        @if($paidCount > 0)
                                            <span class="text-green-600 font-semibold">{{ $paidCount }} paid</span>
                                        @endif
                                        @if($pendingCount > 0)
                                            <span class="text-yellow-600 font-semibold">{{ $pendingCount }} pending</span>
                                        @endif
                                        @if($overdueCount > 0)
                                            <span class="text-red-600 font-semibold">{{ $overdueCount }} overdue</span>
                                        @endif
                                    </div>
                                @endif
                                @if($amendmentCount > 0)
                                    <div class="inline-flex items-center gap-1 rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-600/10">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        {{ $amendmentCount }} {{ Str::plural('Amendment', $amendmentCount) }}
                                    </div>
                                @endif
                            </div>
                        </div>

                         <!-- Right: Value & Actions -->
                        <div class="flex flex-col items-end gap-2 flex-shrink-0">
                             <div class="text-sm font-bold text-gray-900">
                                Rp {{ number_format($contract->total_rental_value) }}
                             </div>
                             <a href="{{ route('contracts.show', $contract) }}" class="rounded bg-white px-2 py-1 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">Details</a>
                        </div>
                    </div>
                </li>
                @empty
                <li class="p-6 text-center text-sm text-gray-500">No history found.</li>
                @endforelse
            </ul>
        </div>

        {{-- Invoice History --}}
        <div class="overflow-hidden bg-white shadow sm:rounded-lg mt-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold leading-6 text-gray-900">Invoice History</h3>
                    <p class="mt-1 text-sm text-gray-500">Riwayat pencatatan penerimaan pembayaran.</p>
                </div>
                @if($invoiceHistory->count() > 0)
                    <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-600/20">
                        {{ $invoiceHistory->count() }} {{ Str::plural('invoice', $invoiceHistory->count()) }}
                    </span>
                @endif
            </div>
            <ul role="list" class="divide-y divide-gray-100">
                @forelse($invoiceHistory as $invoice)
                <li class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between gap-x-6 py-1">
                        {{-- Left: Invoice Info --}}
                        <div class="min-w-0 flex-1">
                            <div class="flex items-start gap-x-3 mb-1">
                                <p class="text-sm font-semibold leading-6 text-gray-900">
                                    {{ $invoice->invoice_number }}
                                </p>
                            </div>

                            {{-- Description --}}
                            <p class="text-sm text-gray-600 mt-0.5">{{ $invoice->description }}</p>

                            {{-- Payment Date --}}
                            <div class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500 font-mono">
                                <svg class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Dibayar: {{ $invoice->payment_date->format('d M Y') }}
                                @if($invoice->invoice_date)
                                    <span class="text-gray-400">·</span>
                                    <span class="text-gray-400 font-sans">Invoice: {{ $invoice->invoice_date->format('d M Y') }}</span>
                                @endif
                            </div>

                            {{-- Assets --}}
                            @if($invoice->assets->count() > 0)
                            <div class="mt-2 flex flex-wrap gap-1.5">
                                @foreach($invoice->assets as $asset)
                                    <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-0.5 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                        {{ $asset->name }}
                                    </span>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        {{-- Right: Amount & Action --}}
                        <div class="flex flex-col items-end gap-2 flex-shrink-0">
                            <div class="text-sm font-bold text-gray-900">
                                Rp {{ number_format($invoice->amount) }}
                            </div>
                            <a href="{{ route('invoices.show', $invoice) }}" class="rounded bg-white px-2 py-1 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">Details</a>
                        </div>
                    </div>
                </li>
                @empty
                <li class="p-6 text-center text-sm text-gray-500">Belum ada invoice untuk tenant ini.</li>
                @endforelse
            </ul>
            @if($invoiceHistory->count() > 0)
            <div class="border-t border-gray-200 px-4 py-3 sm:px-6 bg-gray-50">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Total Penerimaan</span>
                    <span class="font-bold text-emerald-600">Rp {{ number_format($invoiceHistory->sum('amount')) }}</span>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
