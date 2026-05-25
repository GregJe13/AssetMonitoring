@extends('layouts.app')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-2xl font-semibold leading-6 text-gray-900">Payments</h1>
        <p class="mt-2 text-sm text-gray-700">Monitor incoming payments, track overdue invoices, and update payment statuses.</p>
    </div>
</div>

<!-- Stats -->
<div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-4">
    <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
        <dt class="truncate text-sm font-medium text-gray-500">Overdue Total</dt>
        <dd class="mt-1 text-3xl font-semibold tracking-tight text-red-600">
            {{ \App\Models\Payment::where('payment_status', 'overdue')->count() }}
        </dd>
    </div>
    <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
        <dt class="truncate text-sm font-medium text-gray-500">Pending (Upcoming)</dt>
        <dd class="mt-1 text-3xl font-semibold tracking-tight text-yellow-600">
             {{ \App\Models\Payment::where('payment_status', 'pending')->count() }}
        </dd>
    </div>
    <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
        <dt class="truncate text-sm font-medium text-gray-500">Paid (This Year)</dt>
        <dd class="mt-1 text-3xl font-semibold tracking-tight text-green-600">
             {{ \App\Models\Payment::where('payment_status', 'paid')->whereYear('paid_at', now()->year)->count() }}
        </dd>
    </div>
    <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
        <dt class="truncate text-sm font-medium text-gray-500">Unpaid Invoices</dt>
        <dd class="mt-1 text-3xl font-semibold tracking-tight text-orange-600">
             {{ \App\Models\Invoice::where('status', 'unpaid')->count() }}
        </dd>
    </div>
</div>

<div class="mt-8 flow-root">
    <!-- Filters -->
    <div class="mb-4 flex flex-wrap gap-4 bg-white p-4 rounded-lg shadow-sm">
        <form action="{{ route('payments.index') }}" method="GET" class="flex gap-4 items-end">
            <div class="w-64">
                <label for="search" class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Tenant Name / Invoice No...">
            </div>
            
             <div class="w-40">
                <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    <option value="">All Statuses</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                </select>
            </div>
            
            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Filter</button>
        </form>
    </div>

    {{-- Contract Payments --}}
    <h3 class="text-base font-semibold text-gray-900 mb-3">Contract Payments</h3>
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Status</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tenant</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Period</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Due Date</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Amount</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($payments as $payment)
                        <tr class="{{ $payment->payment_status == 'overdue' ? 'bg-red-50' : '' }}">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset 
                                    {{ $payment->payment_status == 'overdue' ? 'bg-red-50 text-red-700 ring-red-600/10' : '' }}
                                    {{ $payment->payment_status == 'pending' ? 'bg-yellow-50 text-yellow-800 ring-yellow-600/20' : '' }}
                                    {{ $payment->payment_status == 'paid' ? 'bg-green-50 text-green-700 ring-green-600/20' : '' }}">
                                    {{ ucfirst($payment->payment_status) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-gray-900">
                                <a href="{{ route('tenants.show', $payment->contract->tenant) }}" class="hover:underline">{{ $payment->contract->tenant->name }}</a>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                #{{ $payment->period_number }} 
                                <span class="text-xs text-gray-400">({{ $payment->contract->no_pks ?? $payment->contract->no_bak ?? 'No Ref' }})</span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 {{ $payment->payment_status == 'overdue' ? 'font-bold text-red-600' : '' }}">
                                {{ $payment->due_date->format('d M Y') }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-gray-900">
                                Rp {{ number_format($payment->amount_due) }}
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                 @if($payment->payment_status != 'paid')
                                    <form action="{{ route('payments.update', $payment) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="action" value="mark_as_paid">
                                        <button type="submit" class="text-indigo-600 hover:text-indigo-900">
                                            Mark Paid
                                        </button>
                                    </form>
                                @else
                                    @if($payment->paid_at)
                                        <span class="text-green-600 text-xs">Dibayar: {{ $payment->paid_at->format('d/m/Y') }}</span>
                                    @else
                                        <span class="text-green-600 text-xs">Paid</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-3 py-4 text-center text-sm text-gray-500">No contract payments found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    @if($payments->hasPages())
    <div class="mt-4">
        {{ $payments->links() }}
    </div>
    @endif

    {{-- Invoice Payments --}}
    <h3 class="text-base font-semibold text-gray-900 mb-3 mt-10">Invoice Payments (Ad-Hoc)</h3>
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Status</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Invoice</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tenant</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tanggal</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Amount</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($invoices as $invoice)
                        <tr class="{{ $invoice->status == 'unpaid' ? 'bg-orange-50' : '' }} hover:bg-gray-50">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $invoice->status_color }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                <div class="font-medium text-gray-900">{{ $invoice->invoice_number }}</div>
                                <div class="text-xs text-gray-400 truncate max-w-[180px]">{{ $invoice->description }}</div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-gray-900">
                                {{ $invoice->display_tenant_name }}
                                @if(!$invoice->tenant)
                                    <span class="inline-flex items-center rounded px-1 py-0.5 text-xs text-yellow-700 bg-yellow-50 ring-1 ring-inset ring-yellow-600/20 ml-1">Manual</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $invoice->invoice_date->format('d M Y') }}
                                @if($invoice->due_date)
                                    <div class="text-xs {{ $invoice->due_date->isPast() && $invoice->status != 'paid' ? 'text-red-500 font-semibold' : 'text-gray-400' }}">
                                        Due: {{ $invoice->due_date->format('d M Y') }}
                                    </div>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-gray-900">
                                Rp {{ number_format($invoice->amount) }}
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 flex gap-2 justify-end">
                                @if($invoice->status == 'unpaid')
                                    <form action="{{ route('invoices.markPaid', $invoice) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-indigo-600 hover:text-indigo-900">Mark Paid</button>
                                    </form>
                                @else
                                    @if($invoice->paid_at)
                                        <span class="text-green-600 text-xs">Dibayar: {{ $invoice->paid_at->format('d/m/Y') }}</span>
                                    @else
                                        <span class="text-green-600 text-xs">Paid</span>
                                    @endif
                                @endif
                                <a href="{{ route('invoices.show', $invoice) }}" class="text-gray-500 hover:text-gray-700">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-3 py-4 text-center text-sm text-gray-500">No invoices found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($invoices->hasPages())
    <div class="mt-4">
        {{ $invoices->links() }}
    </div>
    @endif
</div>
@endsection
