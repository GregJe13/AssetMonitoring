@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex flex-wrap items-start justify-between gap-4">
        <div class="min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Detail Amandemen
            </h2>
            <p class="mt-2 text-sm text-gray-500">{{ $amendment->no_amendment }}</p>
        </div>
        <a href="{{ route('amendments.index') }}" class="inline-flex shrink-0 items-center gap-1 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <!-- Amendment Info -->
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl px-4 py-5 sm:p-6 mb-6">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <h3 class="text-base font-semibold text-gray-900">Informasi Amandemen</h3>
            <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium
                {{ $amendment->status === 'active' ? 'bg-green-100 text-green-700' : ($amendment->status === 'expired' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700') }}">
                {{ ucfirst($amendment->status) }}
            </span>
        </div>
        <dl class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-4">
            <div>
                <dt class="text-xs font-medium text-gray-500">Nomor Amandemen</dt>
                <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $amendment->no_amendment }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500">Amandemen Ke-</dt>
                <dd class="mt-1 text-sm font-semibold text-gray-900">#{{ $amendment->amendment_number }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500">Tanggal Amandemen</dt>
                <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $amendment->date_amendment->format('d M Y') }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500">Tenant</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $amendment->contract->tenant->name ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500">Kontrak Induk</dt>
                <dd class="mt-1 text-sm text-gray-900">
                    <a href="{{ route('contracts.show', $amendment->contract) }}" class="text-indigo-600 hover:text-indigo-900">
                        {{ $amendment->contract->no_pks ?? $amendment->contract->no_bak ?? 'ID: ' . $amendment->contract->id }}
                    </a>
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500">Sisa Hari</dt>
                <dd class="mt-1 text-sm font-semibold {{ $amendment->remaining_days <= 30 ? 'text-red-600' : 'text-gray-900' }}">
                    {{ $amendment->remaining_days }} hari
                </dd>
            </div>
        </dl>
    </div>

    <!-- Comparison: Old vs New -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
        <div class="bg-amber-50 border border-amber-200 sm:rounded-xl p-6">
            <h3 class="text-sm font-semibold text-amber-800 mb-3">Periode Sebelumnya</h3>
            <div class="space-y-2">
                <div>
                    <p class="text-xs text-amber-600">Start Date</p>
                    <p class="text-sm font-semibold text-amber-900">{{ $amendment->old_start_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-amber-600">End Date</p>
                    <p class="text-sm font-semibold text-amber-900">{{ $amendment->old_end_date->format('d M Y') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-green-50 border border-green-200 sm:rounded-xl p-6">
            <h3 class="text-sm font-semibold text-green-800 mb-3">Periode Amandemen (Baru)</h3>
            <div class="space-y-2">
                <div>
                    <p class="text-xs text-green-600">Start Date</p>
                    <p class="text-sm font-semibold text-green-900">{{ $amendment->new_start_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-green-600">End Date</p>
                    <p class="text-sm font-semibold text-green-900">{{ $amendment->new_end_date->format('d M Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial -->
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl px-4 py-5 sm:p-6 mb-6">
        <h3 class="text-base font-semibold text-gray-900 mb-4">Ketentuan Pembayaran</h3>
        <dl class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-4">
            <div>
                <dt class="text-xs font-medium text-gray-500">Total Nilai Sewa</dt>
                <dd class="mt-1 text-sm font-semibold text-gray-900">Rp {{ number_format($amendment->total_rental_value, 0, ',', '.') }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500">Metode Pembayaran</dt>
                <dd class="mt-1 text-sm text-gray-900">
                    @if($amendment->is_upfront)
                        100% Dimuka
                    @else
                        Per {{ $amendment->payment_interval_value }} {{ $amendment->payment_interval_unit === 'month' ? 'Bulan' : 'Tahun' }}
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500">Pihak Pertama / Kedua</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $amendment->pihak_pertama }} / {{ $amendment->pihak_kedua }}</dd>
            </div>
        </dl>
    </div>

    <!-- Assets -->
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl px-4 py-5 sm:p-6 mb-6">
        <h3 class="text-base font-semibold text-gray-900 mb-4">Assets Disewa</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Asset</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Gedung</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Area Disewa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($amendment->assets as $asset)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $asset->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $asset->id_gedung }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900 text-right">{{ number_format($asset->pivot->rented_area_sqm, 2) }} m²</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Documents -->
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl px-4 py-5 sm:p-6 mb-6">
        <h3 class="text-base font-semibold text-gray-900 mb-4">Dokumen</h3>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
            <div>
                <dt class="text-xs font-medium text-gray-500">BAK</dt>
                <dd class="mt-1">
                    @if($amendment->no_bak)
                        <p class="text-sm text-gray-900">{{ $amendment->no_bak }} — {{ $amendment->date_bak?->format('d M Y') ?? '-' }}</p>
                    @else
                        <p class="text-sm text-gray-400">Belum ada</p>
                    @endif
                    @if($amendment->file_bak)
                        <a href="{{ route('amendments.file', [$amendment, 'bak']) }}" target="_blank" class="mt-1 inline-flex items-center text-xs text-indigo-600 hover:text-indigo-900">
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Lihat File BAK
                        </a>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500">PKS</dt>
                <dd class="mt-1">
                    @if($amendment->no_pks)
                        <p class="text-sm text-gray-900">{{ $amendment->no_pks }} — {{ $amendment->date_pks?->format('d M Y') ?? '-' }}</p>
                    @else
                        <p class="text-sm text-gray-400">Belum ada</p>
                    @endif
                    @if($amendment->file_pks)
                        <a href="{{ route('amendments.file', [$amendment, 'pks']) }}" target="_blank" class="mt-1 inline-flex items-center text-xs text-indigo-600 hover:text-indigo-900">
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Lihat File PKS
                        </a>
                    @endif
                </dd>
            </div>
        </dl>
    </div>

    <!-- Notes -->
    @if($amendment->notes)
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl px-4 py-5 sm:p-6 mb-6">
        <h3 class="text-base font-semibold text-gray-900 mb-2">Catatan</h3>
        <p class="text-sm text-gray-700 whitespace-pre-line">{{ $amendment->notes }}</p>
    </div>
    @endif

    <!-- Payment Schedule -->
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl px-4 py-5 sm:p-6">
        <h3 class="text-base font-semibold text-gray-900 mb-4">Jadwal Pembayaran</h3>
        @if($amendment->payments->isEmpty())
            <p class="text-sm text-gray-400">Belum ada jadwal pembayaran.</p>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($amendment->payments->sortBy('period_number') as $payment)
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $payment->period_number }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $payment->due_date->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900 text-right">Rp {{ number_format($payment->amount_due, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $payment->payment_status === 'paid' ? 'bg-green-100 text-green-700' : ($payment->payment_status === 'overdue' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ ucfirst($payment->payment_status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
