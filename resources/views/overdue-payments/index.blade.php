@extends('layouts.app')

@section('title', 'Overdue Payments - INTI Asset Monitoring')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Dashboard
                    </a>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Overdue Payments</h1>
                <p class="mt-1 text-sm text-gray-500">All payments that are past their due date and require action.</p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="p-4 px-6">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-red-50 text-red-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Overdue</p>
                            <p class="text-2xl font-bold font-mono text-gray-900">{{ $overduePayments->total() }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="p-4 px-6">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-red-50 text-red-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Outstanding</p>
                            <p class="text-2xl font-bold font-mono text-gray-900">Rp {{ number_format($totalOutstanding) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments List -->
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
            <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Overdue Payment List</h3>
                <p class="mt-1 text-xs text-gray-500">Sorted by due date (oldest first)</p>
            </div>
            <ul role="list" class="divide-y divide-gray-100">
                @forelse($overduePayments as $payment)
                    <li class="flex items-center justify-between gap-x-6 py-5 px-6 hover:bg-gray-50 transition-colors">
                        <div class="min-w-0">
                            <div class="flex items-start gap-x-3">
                                <p class="text-sm font-semibold leading-6 text-gray-900">
                                    {{ $payment->contract?->tenant?->name ?? 'Unknown' }}
                                </p>
                                <p
                                    class="rounded-md whitespace-nowrap mt-0.5 px-1.5 py-0.5 text-xs font-medium ring-1 ring-inset text-red-700 bg-red-50 ring-red-600/10">
                                    Overdue</p>
                            </div>
                            <div class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                                <p class="whitespace-nowrap">{{ $payment->contract?->no_pks ?? $payment->contract?->no_bak ?? '-' }}</p>
                                <span class="text-gray-300">•</span>
                                <p class="whitespace-nowrap">Period #{{ $payment->period_number }}</p>
                                <span class="text-gray-300">•</span>
                                <p class="whitespace-nowrap text-red-500 font-medium">
                                    {{ floor($payment->due_date->diffInDays(now())) }} days overdue
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-none items-center gap-x-4">
                            <div class="flex flex-col items-end">
                                <p class="text-sm font-semibold leading-6 text-gray-900">Rp
                                    {{ number_format($payment->amount_due) }}
                                </p>
                                <p class="text-xs leading-5 text-red-500">Due {{ $payment->due_date->format('d M Y') }}</p>
                            </div>
                            @unless(Auth::user()->isGuest())
                                <a href="{{ route('payments.index') }}"
                                    class="hidden rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:block">Action</a>
                            @endunless
                        </div>
                    </li>
                @empty
                    <li class="py-5 px-6 text-center text-sm text-gray-500">No overdue payments. Good job! 🎉</li>
                @endforelse
            </ul>

            <!-- Pagination -->
            @if($overduePayments->hasPages())
                <div class="border-t border-gray-200 px-6 py-4">
                    {{ $overduePayments->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
