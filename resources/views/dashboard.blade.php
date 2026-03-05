@extends('layouts.app')

@yield('title', 'Dashboard - INTI Asset Monitoring')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500">Overview of asset performance and alerts.</p>
        </div>
        <div class="flex items-center gap-3">
             <span class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M6 3.75A2.75 2.75 0 003.25 6.5a2.75 2.75 0 002.75 2.75h1.5A2.75 2.75 0 0010.25 6.5 2.75 2.75 0 007.5 3.75H6zm0 10.5a.75.75 0 00-1.5 0v1.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75V15a.75.75 0 00-.75-.75H6zM13.5 3.75a.75.75 0 00-1.5 0v1.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75V15a.75.75 0 00-.75-.75h-1.5z" clip-rule="evenodd" /><path d="M6.75 12.75a.75.75 0 00-.75.75V15c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75v-1.5a.75.75 0 00-.75-.75h-1.5z" /></svg>
                Export Report
            </span>
             <a href="{{ route('contracts.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                New Contract
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Revenue -->
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="p-4 px-6 pt-6">
                <div class="flex items-center">
                    <div class="p-2 rounded-lg bg-emerald-50 text-emerald-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" /></svg>
                    </div>
                    <div class="ml-4 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-500">Total Revenue (YTD)</dt>
                            <dd>
                                <div class="text-xl font-bold font-mono text-gray-900">Rp {{ number_format($totalRevenue / 1000000, 1) }} M</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-2">
                <div class="text-xs font-medium text-emerald-600">
                    +12% from last year
                </div>
            </div>
        </div>

        <!-- Occupancy -->
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="p-4 px-6 pt-6">
                <div class="flex items-center">
                    <div class="p-2 rounded-lg bg-blue-50 text-blue-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819" /></svg>
                    </div>
                    <div class="ml-4 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-500">Asset Occupancy</dt>
                            <dd>
                                <div class="text-xl font-bold font-mono text-gray-900">{{ $occupancyRate }}%</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
             <div class="bg-gray-50 px-6 py-2">
                <div class="text-xs font-medium text-blue-600">
                    {{ $rentedAssetIds }} Rented / {{ $rentedAssetIds + $availableAssets }} Total
                </div>
            </div>
        </div>

        <!-- Overdue -->
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="p-4 px-6 pt-6">
                <div class="flex items-center">
                    <div class="p-2 rounded-lg bg-red-50 text-red-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                    </div>
                    <div class="ml-4 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-500">Overdue Payments</dt>
                            <dd>
                                <div class="text-xl font-bold font-mono text-gray-900">{{ $overduePayments }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-2">
                <div class="text-xs font-medium text-red-600">
                    Rp {{ number_format($overdueAmount) }} Outstanding
                </div>
            </div>
        </div>

        <!-- Contracts Expiring -->
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="p-4 px-6 pt-6">
                <div class="flex items-center">
                    <div class="p-2 rounded-lg bg-yellow-50 text-yellow-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div class="ml-4 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-500">Expiring Soon</dt>
                            <dd>
                                <div class="text-xl font-bold font-mono text-gray-900">{{ $contractsExpiringSoon }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-2">
                <div class="text-xs font-medium text-yellow-600">
                    In next 60 days
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <!-- Revenue Chart -->
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 lg:col-span-2 p-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">Revenue Trend</h3>
            <div id="revenueChart" class="w-full h-80"></div>
        </div>

        <!-- Asset Status Chart -->
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 p-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">Asset Utilization</h3>
            <div id="assetChart" class="w-full h-64 flex items-center justify-center"></div>
             <div class="mt-4 flex items-center justify-center gap-4 text-sm">
                <div class="flex items-center gap-1">
                    <span class="w-3 h-3 rounded-full bg-indigo-500"></span> Rented
                </div>
                <div class="flex items-center gap-1">
                    <span class="w-3 h-3 rounded-full bg-gray-200"></span> Available
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Renewal Warning -->
    @if(isset($pendingRenewals) && $pendingRenewals->count() > 0)
    <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 mb-5">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-semibold text-amber-800">
                    {{ $pendingRenewals->count() }} Workflow Perpanjangan Belum Ditindaklanjuti
                </h3>
                <div class="mt-2 space-y-2">
                    @foreach($pendingRenewals as $workflow)
                    <a href="{{ route('workflow.renewalChoice', $workflow->contract) }}"
                       class="flex items-center justify-between rounded-lg bg-white px-3 py-2 ring-1 ring-amber-200 hover:ring-amber-400 transition-all group">
                        <div>
                            <span class="text-sm font-medium text-gray-900">{{ $workflow->contract->tenant->name ?? '-' }}</span>
                            <span class="text-xs text-gray-500 ml-2">{{ $workflow->contract->no_pks ?? $workflow->contract->no_bak ?? '' }}</span>
                        </div>
                        <div class="flex items-center text-xs text-amber-600 group-hover:text-amber-800">
                            Pilih tindakan
                            <svg class="ml-1 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Alerts Section -->
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        <!-- Expiring Contracts List -->
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
            <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Contracts Expiring Soon</h3>
                <p class="mt-1 text-xs text-gray-500">Track renewal/termination progress with notes</p>
            </div>
            <ul role="list" class="divide-y divide-gray-100">
                @forelse($expiringContracts as $contract)
                <li class="py-4 px-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between gap-x-4">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-start gap-x-3">
                                <p class="text-sm font-semibold leading-6 text-gray-900">{{ $contract->tenant->name }}</p>
                                <p class="rounded-md whitespace-nowrap px-1.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $contract->is_expired ? 'text-red-700 bg-red-50 ring-red-600/10' : 'text-yellow-800 bg-yellow-50 ring-yellow-600/20' }}">
                                    {{ $contract->is_expired ? 'Expired' : 'Expiring' }}
                                </p>
                            </div>
                            <div class="mt-1 flex items-center gap-x-3 text-xs leading-5 text-gray-500">
                                <p class="whitespace-nowrap">{{ $contract->no_pks ?? $contract->no_bak ?? '-' }}</p>
                                <span class="text-gray-300">•</span>
                                <p class="whitespace-nowrap">{{ $contract->end_date->format('d M Y') }}</p>
                                @if($contract->is_expired)
                                    <span class="text-red-600 font-medium">({{ $contract->days_expired }} hari lalu)</span>
                                @else
                                    <span class="text-gray-400">({{ $contract->remaining_days }} days left)</span>
                                @endif
                            </div>
                            <!-- Editable Notes -->
                            <div class="mt-3" x-data="{ 
                                editing: false, 
                                notes: @js($contract->renewal_notes ?? ''),
                                saving: false,
                                saved: false,
                                saveNotes() {
                                    this.saving = true;
                                    fetch('{{ route('contracts.updateRenewalNotes', $contract) }}', {
                                        method: 'PATCH',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json'
                                        },
                                        body: JSON.stringify({ renewal_notes: this.notes })
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        this.saving = false;
                                        this.editing = false;
                                        this.saved = true;
                                        setTimeout(() => this.saved = false, 2000);
                                    })
                                    .catch(err => {
                                        this.saving = false;
                                        alert('Failed to save notes');
                                    });
                                }
                            }">
                                <template x-if="!editing">
                                    <div @click="editing = true" class="cursor-pointer group">
                                        <template x-if="notes && notes.trim()">
                                            <div class="text-xs text-gray-600 bg-gray-50 rounded-md px-2 py-1.5 border border-gray-200 group-hover:border-indigo-300 group-hover:bg-indigo-50 transition-colors">
                                                <span class="font-medium text-gray-500">Notes:</span>
                                                <span x-text="notes"></span>
                                                <span class="text-gray-400 ml-1 opacity-0 group-hover:opacity-100">(click to edit)</span>
                                            </div>
                                        </template>
                                        <template x-if="!notes || !notes.trim()">
                                            <div class="text-xs text-gray-400 italic hover:text-indigo-600 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                Click to add progress notes...
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="editing">
                                    <div class="space-y-2">
                                        <textarea 
                                            x-model="notes" 
                                            @keydown.escape="editing = false"
                                            class="block w-full rounded-md border-0 py-1.5 px-2 text-xs text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600"
                                            rows="2"
                                            placeholder="e.g., Menunggu konfirmasi perpanjangan dari tenant, atau Proses terminasi sedang berjalan..."
                                            x-ref="notesInput"
                                            x-init="$nextTick(() => $refs.notesInput.focus())"
                                        ></textarea>
                                        <div class="flex gap-2">
                                            <button 
                                                @click="saveNotes()" 
                                                :disabled="saving"
                                                class="inline-flex items-center rounded-md bg-indigo-600 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 disabled:opacity-50">
                                                <span x-show="!saving">Save</span>
                                                <span x-show="saving">Saving...</span>
                                            </button>
                                            <button @click="editing = false" class="text-xs text-gray-500 hover:text-gray-700">Cancel</button>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="saved">
                                    <div class="text-xs text-green-600 mt-1">✓ Saved!</div>
                                </template>
                            </div>
                        </div>
                        <div class="flex-shrink-0 flex gap-2">
                            @if($contract->workflow)
                                @php
                                    $finalSteps = ['closed', 'renewed'];
                                    $isAtFinalStep = in_array($contract->workflow->current_step, $finalSteps);
                                @endphp
                                <a href="{{ route('workflow.show', $contract) }}"
                                   class="rounded-md px-2.5 py-1.5 text-sm font-semibold shadow-sm ring-1 ring-inset transition-colors
                                       {{ $isAtFinalStep
                                           ? 'bg-green-50 text-green-700 ring-green-300 hover:bg-green-100'
                                           : 'bg-indigo-50 text-indigo-600 ring-indigo-200 hover:bg-indigo-100' }}">
                                    {{ $isAtFinalStep ? '✓ ' : '' }}{{ $contract->workflow->getCurrentStepLabel() }}
                                </a>
                            @else
                                <form action="{{ route('workflow.start', $contract) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="rounded-md bg-yellow-50 px-2.5 py-1.5 text-sm font-semibold text-yellow-700 shadow-sm ring-1 ring-inset ring-yellow-300 hover:bg-yellow-100">SOP Akhir Kontrak</button>
                                </form>
                            @endif
                            <a href="{{ route('contracts.show', $contract) }}" class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">View</a>
                        </div>
                    </div>
                </li>
                @empty
                 <li class="py-5 px-6 text-center text-sm text-gray-500">No contracts expiring in next 60 days.</li>
                @endforelse
            </ul>
        </div>

        <!-- Recent Overdue Payments -->
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
            <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Overdue Payments Needs Action</h3>
            </div>
             <ul role="list" class="divide-y divide-gray-100">
                @forelse($recentOverdue as $payment)
                <li class="flex items-center justify-between gap-x-6 py-5 px-6 hover:bg-gray-50 transition-colors">
                    <div class="min-w-0">
                        <div class="flex items-start gap-x-3">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{ $payment->contract->tenant->name }}</p>
                            <p class="rounded-md whitespace-nowrap mt-0.5 px-1.5 py-0.5 text-xs font-medium ring-1 ring-inset text-red-700 bg-red-50 ring-red-600/10">Overdue</p>
                        </div>
                        <div class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                             <p class="whitespace-nowrap">Period #{{ $payment->period_number }}</p>
                        </div>
                    </div>
                    <div class="flex flex-none items-center gap-x-4">
                        <div class="flex flex-col items-end">
                             <p class="text-sm font-semibold leading-6 text-gray-900">Rp {{ number_format($payment->amount_due) }}</p>
                             <p class="text-xs leading-5 text-red-500">Due {{ $payment->due_date->format('d M Y') }}</p>
                        </div>
                         <a href="{{ route('payments.index') }}" class="hidden rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:block">Action</a>
                    </div>
                </li>
                @empty
                 <li class="py-5 px-6 text-center text-sm text-gray-500">No overdue payments. Good job!</li>
                @endforelse
            </ul>
        </div>

        <!-- Unpaid Invoices -->
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
            <div class="border-b border-gray-200 px-4 py-5 sm:px-6 flex items-center justify-between">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Unpaid Invoices</h3>
                <a href="{{ route('invoices.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800">Lihat Semua →</a>
            </div>
             <ul role="list" class="divide-y divide-gray-100">
                @forelse($unpaidInvoices as $invoice)
                <li class="flex items-center justify-between gap-x-6 py-5 px-6 hover:bg-gray-50 transition-colors">
                    <div class="min-w-0">
                        <div class="flex items-start gap-x-3">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{ $invoice->display_tenant_name }}</p>
                            <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $invoice->status_color }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </div>
                        <div class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                             <p class="whitespace-nowrap">{{ $invoice->invoice_number }}</p>
                        </div>
                    </div>
                    <div class="flex flex-none items-center gap-x-4">
                        <div class="flex flex-col items-end">
                             <p class="text-sm font-semibold leading-6 text-gray-900">Rp {{ number_format($invoice->amount) }}</p>
                             @if($invoice->due_date)
                                <p class="text-xs leading-5 {{ $invoice->due_date->isPast() ? 'text-red-500' : 'text-gray-500' }}">Due {{ $invoice->due_date->format('d M Y') }}</p>
                             @else
                                <p class="text-xs leading-5 text-gray-400">{{ $invoice->invoice_date->format('d M Y') }}</p>
                             @endif
                        </div>
                         <a href="{{ route('invoices.show', $invoice) }}" class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 hidden sm:block">View</a>
                    </div>
                </li>
                @empty
                 <li class="py-5 px-6 text-center text-sm text-gray-500">No unpaid invoices. 🎉</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Revenue Chart
        var revenueOptions = {
            series: [{
                name: 'Revenue',
                data: @json($revenueData)
            }],
            chart: {
                type: 'area',
                height: 320,
                fontFamily: 'Instrument Sans, sans-serif',
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            xaxis: {
                categories: @json($months),
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                         // Format to millions (e.g., 20M)
                        if(value >= 1000000) return (value / 1000000).toFixed(1) + "M";
                        return value;
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.2,
                    stops: [0, 90, 100]
                }
            },
            colors: ['#10b981'], // Emerald 500
            grid: {
                strokeDashArray: 4,
                borderColor: '#e5e7eb'
            }
        };

        var revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
        revenueChart.render();

        // Asset Utilization Chart
        var assetOptions = {
            series: [{{ $rentedAssetIds }}, {{ $availableAssets }}],
            chart: {
                type: 'donut',
                height: 280,
                fontFamily: 'Instrument Sans, sans-serif',
            },
            labels: ['Rented', 'Available'],
            colors: ['#6366f1', '#e5e7eb'], // Indigo 500, Gray 200
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total Assets',
                                fontSize: '14px',
                                fontWeight: 600,
                                color: '#374151',
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
            legend: { show: false },
            tooltip: {
                enabled: true,
                y: {
                    formatter: function(val) {
                        return val + " Units"
                    }
                }
            }
        };

        var assetChart = new ApexCharts(document.querySelector("#assetChart"), assetOptions);
        assetChart.render();
    });
</script>
@endsection
