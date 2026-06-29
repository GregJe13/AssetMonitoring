@extends('layouts.app')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-2xl font-semibold leading-6 text-gray-900">Invoices</h1>
        <p class="mt-2 text-sm text-gray-700">Pencatatan penerimaan pembayaran.</p>
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <a href="{{ route('invoices.create') }}" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
            New Invoice
        </a>
    </div>
</div>

{{-- Search --}}
<div class="mt-6">
    <form method="GET" action="{{ route('invoices.index') }}">
        <div class="relative max-w-sm">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari invoice..."
                class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd"/></svg>
            </div>
        </div>
    </form>
</div>

@if(session('success'))
    <div class="mt-4 rounded-md bg-green-50 p-4">
        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
    </div>
@endif

{{-- Table --}}
<div class="mt-6 flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Invoice</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tenant</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Assets</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Amount</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tanggal Bayar</th>
                            <th class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($invoices as $invoice)
                        <tr class="hover:bg-gray-50">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                <div class="font-medium text-gray-900">{{ $invoice->invoice_number }}</div>
                                <div class="text-gray-500 text-xs truncate max-w-[200px]">{{ $invoice->description }}</div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                <div class="font-medium text-gray-900">{{ $invoice->display_tenant_name }}</div>
                                @if($invoice->tenant)
                                    <div class="text-xs text-gray-400">Tenant terdaftar</div>
                                @else
                                    <div class="text-xs text-yellow-600">Manual</div>
                                @endif
                            </td>
                            <td class="px-3 py-4 text-sm text-gray-500 max-w-[150px]">
                                @if($invoice->assets->count() > 0)
                                    {{ $invoice->assets->count() }} Unit
                                    <div class="text-xs text-gray-400 truncate" title="{{ $invoice->assets->pluck('name')->join(', ') }}">
                                        {{ $invoice->assets->pluck('name')->join(', ') }}
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 font-medium">
                                Rp {{ number_format($invoice->amount) }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $invoice->payment_date->format('d M Y') }}
                                @if($invoice->invoice_date)
                                    <div class="text-xs text-gray-400">Invoice: {{ $invoice->invoice_date->format('d M Y') }}</div>
                                @endif
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <a href="{{ route('invoices.show', $invoice) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-3 py-8 text-center text-sm text-gray-500">Belum ada invoice.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Pagination --}}
@if($invoices->hasPages())
    <div class="mt-4">
        {{ $invoices->links() }}
    </div>
@endif
@endsection
