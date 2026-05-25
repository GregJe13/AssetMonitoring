@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
            Create New Contract
        </h2>
        <p class="mt-2 text-sm text-gray-500">Create a new rental agreement. You can rent partial areas of assets.</p>
    </div>

    <form action="{{ route('contracts.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data" onsubmit="return validateContractForm(event)"
          x-data="{ contractType: '{{ old('contract_type', 'sewa') }}' }"
    >
        @csrf

        <!-- 1. Tenant Selection -->
        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl p-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">Tenant Information</h3>
            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="col-span-full">
                    <label for="tenant_id" class="block text-sm font-medium leading-6 text-gray-900">Select Tenant</label>
                    <div class="mt-2">
                        <select id="tenant_id" name="tenant_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option value="">-- Choose a Tenant --</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}" {{ (old('tenant_id', $selectedTenantId) == $tenant->id) ? 'selected' : '' }}>
                                    {{ $tenant->name }} ({{ $tenant->email ?? 'No Email' }})
                                </option>
                            @endforeach
                        </select>
                        @error('tenant_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="pihak_pertama" class="block text-sm font-medium leading-6 text-gray-900">Perwakilan Pihak Pertama (Lessor)</label>
                    <p class="text-xs text-gray-500 mb-1">Nama orang yang mewakili dan menandatangani kontrak</p>
                    <div class="mt-2">
                        <input type="text" name="pihak_pertama" id="pihak_pertama" value="{{ old('pihak_pertama') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 placeholder:text-gray-400" placeholder="e.g. Ahmad Sulaiman">
                        @error('pihak_pertama') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="pihak_kedua" class="block text-sm font-medium leading-6 text-gray-900">Perwakilan Pihak Kedua (Lessee)</label>
                    <p class="text-xs text-gray-500 mb-1">Nama orang yang mewakili dan menandatangani kontrak</p>
                    <div class="mt-2">
                        <input type="text" name="pihak_kedua" id="pihak_kedua" value="{{ old('pihak_kedua') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 placeholder:text-gray-400" placeholder="e.g. Budi Santoso">
                        @error('pihak_kedua') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Contract Type Selection -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <label class="block text-sm font-medium leading-6 text-gray-900 mb-3">Tipe Kontrak</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="relative flex cursor-pointer rounded-lg border p-4 transition-colors"
                           :class="contractType === 'sewa' ? 'border-indigo-600 bg-indigo-50 ring-2 ring-indigo-600' : 'border-gray-300 hover:border-gray-400'">
                        <input type="radio" name="contract_type" value="sewa" x-model="contractType" class="sr-only">
                        <span class="flex flex-1 flex-col">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" :class="contractType === 'sewa' ? 'text-indigo-600' : 'text-gray-400'" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                <span class="text-sm font-semibold" :class="contractType === 'sewa' ? 'text-indigo-900' : 'text-gray-900'">Kontrak Sewa</span>
                            </span>
                            <span class="mt-1 text-xs" :class="contractType === 'sewa' ? 'text-indigo-700' : 'text-gray-500'">Kontrak sewa dengan nilai tetap (fixed rental value).</span>
                        </span>
                    </label>
                    <label class="relative flex cursor-pointer rounded-lg border p-4 transition-colors"
                           :class="contractType === 'ksu' ? 'border-purple-600 bg-purple-50 ring-2 ring-purple-600' : 'border-gray-300 hover:border-gray-400'">
                        <input type="radio" name="contract_type" value="ksu" x-model="contractType" class="sr-only">
                        <span class="flex flex-1 flex-col">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" :class="contractType === 'ksu' ? 'text-purple-600' : 'text-gray-400'" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                                </svg>
                                <span class="text-sm font-semibold" :class="contractType === 'ksu' ? 'text-purple-900' : 'text-gray-900'">KSU (Bagi Hasil)</span>
                            </span>
                            <span class="mt-1 text-xs" :class="contractType === 'ksu' ? 'text-purple-700' : 'text-gray-500'">Kerjasama Usaha dengan sistem bagi hasil (revenue/profit sharing).</span>
                        </span>
                    </label>
                </div>
                @error('contract_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- 2. Contract Details -->
        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl p-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">Contract Terms</h3>
            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                
                <div class="sm:col-span-3">
                    <label for="no_pks" class="block text-sm font-medium leading-6 text-gray-900">Contract No. (PKS)</label>
                    <div class="mt-2">
                        <input type="text" name="no_pks" id="no_pks" value="{{ old('no_pks') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        @error('no_pks') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="date_pks" class="block text-sm font-medium leading-6 text-gray-900">PKS Date</label>
                    <div class="mt-2">
                        <input type="date" name="date_pks" id="date_pks" value="{{ old('date_pks') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        @error('date_pks') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="col-span-full">
                    <label for="file_pks" class="block text-sm font-medium leading-6 text-gray-900">Upload File PKS (PDF, max 10MB)</label>
                    <div class="mt-2">
                        <input type="file" name="file_pks" id="file_pks" accept=".pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('file_pks') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="no_bak" class="block text-sm font-medium leading-6 text-gray-900">BAK No. (Optional)</label>
                    <div class="mt-2">
                        <input type="text" name="no_bak" id="no_bak" value="{{ old('no_bak') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        @error('no_bak') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="date_bak" class="block text-sm font-medium leading-6 text-gray-900">BAK Date</label>
                    <div class="mt-2">
                        <input type="date" name="date_bak" id="date_bak" value="{{ old('date_bak') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        @error('date_bak') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="col-span-full">
                    <label for="file_bak" class="block text-sm font-medium leading-6 text-gray-900">Upload File BAK (PDF, max 10MB)</label>
                    <div class="mt-2">
                        <input type="file" name="file_bak" id="file_bak" accept=".pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('file_bak') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="start_date" class="block text-sm font-medium leading-6 text-gray-900">Start Date</label>
                    <div class="mt-2">
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        @error('start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="end_date" class="block text-sm font-medium leading-6 text-gray-900">End Date</label>
                    <div class="mt-2">
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        @error('end_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

            </div>
        </div>

        <!-- 3. Financials & Payment Schedule (Sewa only) -->
        <div x-show="contractType === 'sewa'" x-transition class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl p-6" x-data="{
            paymentType: '{{ old('payment_type', 'interval') }}',
            termins: {{ old('termins') ? json_encode(old('termins')) : '[]' }},
            addTermin() {
                this.termins.push({ due_date: '', amount_due: '' });
            },
            removeTermin(index) {
                this.termins.splice(index, 1);
            },
            get totalTermin() {
                return this.termins.reduce((sum, t) => sum + (parseFloat(t.amount_due) || 0), 0);
            }
        }">
            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">Financials & Payment Terms</h3>
             <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                
                <div class="sm:col-span-3" x-show="paymentType !== 'termin'">
                    <label for="total_rental_value" class="block text-sm font-medium leading-6 text-gray-900">Total Rental Value (Rp)</label>
                    <div class="mt-2 relative rounded-md shadow-sm">
                         <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" name="total_rental_value" id="total_rental_value" :value="paymentType === 'termin' ? totalTermin : '{{ old('total_rental_value') }}'" class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="0">
                    </div>
                     @error('total_rental_value') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Auto-calculated total for termin -->
                <div class="sm:col-span-3" x-show="paymentType === 'termin'" x-cloak>
                    <label class="block text-sm font-medium leading-6 text-gray-900">Total Rental Value (Auto)</label>
                    <div class="mt-2 relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" name="total_rental_value" :value="totalTermin" readonly class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 bg-gray-50 ring-1 ring-inset ring-gray-300 sm:text-sm sm:leading-6 cursor-not-allowed" placeholder="0">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Dihitung otomatis dari total semua termin.</p>
                </div>

                <div class="sm:col-span-3">
                    <label for="security_deposit" class="block text-sm font-medium leading-6 text-gray-900">Security Deposit (Optional)</label>
                    <div class="mt-2 relative rounded-md shadow-sm">
                         <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" name="security_deposit" id="security_deposit" value="{{ old('security_deposit') }}" class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="0">
                    </div>
                </div>

                <!-- Payment Type Selection -->
                <div class="col-span-full">
                    <label class="block text-sm font-medium leading-6 text-gray-900 mb-3">Tipe Pembayaran</label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <label class="relative flex cursor-pointer rounded-lg border p-4 transition-colors" :class="paymentType === 'interval' ? 'border-indigo-600 bg-indigo-50 ring-2 ring-indigo-600' : 'border-gray-300 hover:border-gray-400'">
                            <input type="radio" name="payment_type" value="interval" x-model="paymentType" class="sr-only">
                            <span class="flex flex-1 flex-col">
                                <span class="text-sm font-semibold" :class="paymentType === 'interval' ? 'text-indigo-900' : 'text-gray-900'">Interval</span>
                                <span class="mt-1 text-xs" :class="paymentType === 'interval' ? 'text-indigo-700' : 'text-gray-500'">Pembayaran berkala (bulanan, 3 bulan, tahunan, dll.)</span>
                            </span>
                        </label>
                        <label class="relative flex cursor-pointer rounded-lg border p-4 transition-colors" :class="paymentType === 'upfront' ? 'border-indigo-600 bg-indigo-50 ring-2 ring-indigo-600' : 'border-gray-300 hover:border-gray-400'">
                            <input type="radio" name="payment_type" value="upfront" x-model="paymentType" class="sr-only">
                            <span class="flex flex-1 flex-col">
                                <span class="text-sm font-semibold" :class="paymentType === 'upfront' ? 'text-indigo-900' : 'text-gray-900'">Upfront</span>
                                <span class="mt-1 text-xs" :class="paymentType === 'upfront' ? 'text-indigo-700' : 'text-gray-500'">Bayar 100% di muka sekaligus.</span>
                            </span>
                        </label>
                        <label class="relative flex cursor-pointer rounded-lg border p-4 transition-colors" :class="paymentType === 'termin' ? 'border-indigo-600 bg-indigo-50 ring-2 ring-indigo-600' : 'border-gray-300 hover:border-gray-400'">
                            <input type="radio" name="payment_type" value="termin" x-model="paymentType" class="sr-only">
                            <span class="flex flex-1 flex-col">
                                <span class="text-sm font-semibold" :class="paymentType === 'termin' ? 'text-indigo-900' : 'text-gray-900'">Termin</span>
                                <span class="mt-1 text-xs" :class="paymentType === 'termin' ? 'text-indigo-700' : 'text-gray-500'">Tentukan jadwal & nominal per termin secara manual.</span>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Interval Settings (shown if interval) -->
                <div class="sm:col-span-3" x-show="paymentType === 'interval'" x-transition>
                    <label for="payment_interval_value" class="block text-sm font-medium leading-6 text-gray-900">Payment Interval (Every X)</label>
                    <div class="mt-2">
                        <input type="number" name="payment_interval_value" id="payment_interval_value" value="{{ old('payment_interval_value', 1) }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="sm:col-span-3" x-show="paymentType === 'interval'" x-transition>
                    <label for="payment_interval_unit" class="block text-sm font-medium leading-6 text-gray-900">Interval Unit</label>
                    <div class="mt-2">
                        <select id="payment_interval_unit" name="payment_interval_unit" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option value="month" {{ old('payment_interval_unit') == 'month' ? 'selected' : '' }}>Month(s)</option>
                            <option value="year" {{ old('payment_interval_unit') == 'year' ? 'selected' : '' }}>Year(s)</option>
                        </select>
                    </div>
                </div>

                <!-- Payment Start Date (only shown if interval) -->
                <div class="col-span-full" x-show="paymentType === 'interval'" x-transition>
                    <label for="payment_start_date" class="block text-sm font-medium leading-6 text-gray-900">Payment Start Date (Optional)</label>
                    <p class="text-sm text-gray-500 mb-2">Tanggal mulai jadwal pembayaran. Jika kosong, akan menggunakan tanggal mulai kontrak.</p>
                    <div class="mt-2 sm:max-w-xs">
                        <input type="date" name="payment_start_date" id="payment_start_date" value="{{ old('payment_start_date') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        @error('payment_start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Termin Builder (shown if termin) -->
                <div class="col-span-full" x-show="paymentType === 'termin'" x-transition x-cloak>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-sm font-semibold text-gray-900">Daftar Termin</h4>
                            <button type="button" @click="addTermin()" class="inline-flex items-center gap-1 rounded-md bg-indigo-50 px-2.5 py-1.5 text-xs font-semibold text-indigo-600 ring-1 ring-inset ring-indigo-200 hover:bg-indigo-100 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Tambah Termin
                            </button>
                        </div>

                        <div class="space-y-3">
                            <template x-for="(termin, index) in termins" :key="index">
                                <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 border border-gray-200">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex-shrink-0" x-text="index + 1"></span>
                                    <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal Jatuh Tempo</label>
                                            <input type="date" :name="'termins[' + index + '][due_date]'" x-model="termin.due_date" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1">Nominal (Rp)</label>
                                            <div class="relative rounded-md shadow-sm">
                                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                    <span class="text-gray-500 sm:text-xs">Rp</span>
                                                </div>
                                                <input type="number" :name="'termins[' + index + '][amount_due]'" x-model="termin.amount_due" min="1" required class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="0">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" @click="removeTermin(index)" class="flex-shrink-0 rounded-md p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </template>
                        </div>

                        <div x-show="termins.length === 0" class="text-center py-6 text-sm text-gray-400">
                            Belum ada termin. Klik "Tambah Termin" untuk menambahkan.
                        </div>

                        <!-- Termin Summary -->
                        <div x-show="termins.length > 0" class="mt-4 pt-4 border-t border-gray-200 flex items-center justify-between">
                            <span class="text-sm text-gray-600"><span x-text="termins.length"></span> termin</span>
                            <span class="text-sm font-semibold text-gray-900">Total: Rp <span x-text="totalTermin.toLocaleString('id-ID')"></span></span>
                        </div>
                    </div>
                    @error('termins') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

             </div>
        </div>

        <!-- 3b. KSU Sharing Terms (KSU only) -->
        <div x-show="contractType === 'ksu'" x-transition x-cloak class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl p-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">Ketentuan Bagi Hasil (KSU)</h3>
            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                
                <!-- Sharing Type -->
                <div class="col-span-full">
                    <label class="block text-sm font-medium leading-6 text-gray-900 mb-3">Tipe Bagi Hasil</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" x-data="{ sharingType: '{{ old('sharing_type', 'revenue_sharing') }}' }">
                        <label class="relative flex cursor-pointer rounded-lg border p-4 transition-colors"
                               :class="sharingType === 'revenue_sharing' ? 'border-purple-600 bg-purple-50 ring-2 ring-purple-600' : 'border-gray-300 hover:border-gray-400'">
                            <input type="radio" name="sharing_type" value="revenue_sharing" x-model="sharingType" class="sr-only">
                            <span class="flex flex-1 flex-col">
                                <span class="text-sm font-semibold" :class="sharingType === 'revenue_sharing' ? 'text-purple-900' : 'text-gray-900'">Revenue Sharing</span>
                                <span class="mt-1 text-xs" :class="sharingType === 'revenue_sharing' ? 'text-purple-700' : 'text-gray-500'">Bagi hasil berdasarkan pendapatan kotor (omzet).</span>
                            </span>
                        </label>
                        <label class="relative flex cursor-pointer rounded-lg border p-4 transition-colors"
                               :class="sharingType === 'profit_sharing' ? 'border-purple-600 bg-purple-50 ring-2 ring-purple-600' : 'border-gray-300 hover:border-gray-400'">
                            <input type="radio" name="sharing_type" value="profit_sharing" x-model="sharingType" class="sr-only">
                            <span class="flex flex-1 flex-col">
                                <span class="text-sm font-semibold" :class="sharingType === 'profit_sharing' ? 'text-purple-900' : 'text-gray-900'">Profit Sharing</span>
                                <span class="mt-1 text-xs" :class="sharingType === 'profit_sharing' ? 'text-purple-700' : 'text-gray-500'">Bagi hasil berdasarkan keuntungan bersih (net profit).</span>
                            </span>
                        </label>
                    </div>
                    @error('sharing_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Company Share -->
                <div class="sm:col-span-3">
                    <label for="company_share_pct" class="block text-sm font-medium leading-6 text-gray-900">Bagian Perusahaan (%)</label>
                    <div class="mt-2 relative rounded-md shadow-sm">
                        <input type="number" name="company_share_pct" id="company_share_pct" value="{{ old('company_share_pct') }}" step="0.01" min="0" max="100" class="block w-full rounded-md border-0 py-1.5 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-purple-600 sm:text-sm sm:leading-6" placeholder="e.g. 70">
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <span class="text-gray-500 sm:text-sm">%</span>
                        </div>
                    </div>
                    @error('company_share_pct') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Tenant Share -->
                <div class="sm:col-span-3">
                    <label for="tenant_share_pct" class="block text-sm font-medium leading-6 text-gray-900">Bagian Tenant (%)</label>
                    <div class="mt-2 relative rounded-md shadow-sm">
                        <input type="number" name="tenant_share_pct" id="tenant_share_pct" value="{{ old('tenant_share_pct') }}" step="0.01" min="0" max="100" class="block w-full rounded-md border-0 py-1.5 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-purple-600 sm:text-sm sm:leading-6" placeholder="e.g. 30">
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <span class="text-gray-500 sm:text-sm">%</span>
                        </div>
                    </div>
                    @error('tenant_share_pct') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Info note -->
                <div class="col-span-full">
                    <div class="rounded-md bg-purple-50 p-3 border border-purple-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-purple-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs text-purple-700">Untuk KSU, pencatatan cash basis dilakukan melalui pembuatan Invoice manual dari hasil rekonsiliasi. Tidak ada jadwal pembayaran otomatis.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Asset Selection with Area Input (Dynamic based on contract dates) -->
        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl p-6" 
             x-data="assetSelector()" x-init="init()">
            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-2">Select Assets & Specify Area</h3>
            <p class="text-sm text-gray-500 mb-4">Masukkan tanggal mulai dan akhir kontrak terlebih dahulu untuk melihat ketersediaan asset.</p>
            
            @error('asset_areas') <p class="mb-4 text-sm text-red-600">{{ $message }}</p> @enderror

            <!-- Placeholder when dates not yet filled -->
            <div x-show="!hasDates" class="text-center py-10 text-gray-400 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
                <p class="text-sm font-medium">Isi <strong>Start Date</strong> dan <strong>End Date</strong> terlebih dahulu</p>
                <p class="text-xs mt-1">Daftar asset akan muncul sesuai ketersediaan pada periode kontrak.</p>
            </div>

            <!-- Loading state -->
            <div x-show="loading" class="text-center py-10">
                <svg class="animate-spin mx-auto h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <p class="text-sm text-gray-500 mt-2">Memuat ketersediaan asset...</p>
            </div>

            <!-- Asset list (shown after dates filled) -->
            <div x-show="hasDates && !loading && assets.length > 0" x-cloak>
                <div class="mb-4">
                    <input type="text" x-model="search" placeholder="Cari nama atau ID asset..." class="block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>

                <div class="space-y-3 max-h-96 overflow-y-auto">
                    <template x-for="asset in filteredAssets" :key="asset.id">
                        <div class="flex items-center gap-4 p-3 rounded-lg border bg-white transition-colors"
                             :class="asset.is_full ? 'border-red-200 bg-red-50/30' : 'border-gray-200 hover:border-indigo-300'">
                            
                            <!-- Asset Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-gray-900 truncate" x-text="asset.name"></span>
                                    <span class="text-xs text-gray-500 font-mono" x-text="asset.id_gedung"></span>
                                    <span x-show="asset.is_full" class="inline-flex items-center rounded-full bg-red-50 px-2 py-0.5 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">Full</span>
                                </div>
                                <div class="mt-1 flex items-center gap-3 text-xs">
                                    <span class="text-gray-500">Total: <span x-text="Number(asset.area_sqm).toLocaleString('id-ID')"></span> m²</span>
                                    <span :class="asset.is_full ? 'text-red-600' : 'text-emerald-600'" class="font-medium">
                                        Available: <span x-text="Number(asset.available_area).toLocaleString('id-ID')"></span> m²
                                    </span>
                                </div>
                                <!-- Progress Bar -->
                                <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full transition-all duration-300"
                                         :class="asset.is_full ? 'bg-red-400' : 'bg-emerald-500'"
                                         :style="'width: ' + (asset.area_sqm > 0 ? Math.min(100, (asset.available_area / asset.area_sqm) * 100) : 0) + '%'"></div>
                                </div>
                            </div>
                            
                            <!-- Area Input -->
                            <div class="w-32 flex-shrink-0">
                                <div class="relative rounded-md shadow-sm">
                                    <input type="number" 
                                           :name="'asset_areas[' + asset.id + ']'" 
                                           step="0.01"
                                           min="0"
                                           :max="asset.available_area"
                                           placeholder="0"
                                           :disabled="asset.is_full"
                                           :class="asset.is_full ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : ''"
                                           class="block w-full rounded-md border-0 py-1.5 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                        <span class="text-gray-500 sm:text-xs">m²</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-x-6">
            <a href="{{ route('contracts.index') }}" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Create Contract</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function validateContractForm(e) {
    const noPks = document.getElementById('no_pks').value.trim();
    const datePks = document.getElementById('date_pks').value;
    const filePks = document.getElementById('file_pks').files.length > 0;
    
    const noBak = document.getElementById('no_bak').value.trim();
    const dateBak = document.getElementById('date_bak').value;
    const fileBak = document.getElementById('file_bak').files.length > 0;
    
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const paymentStartDate = document.getElementById('payment_start_date')?.value || '';
    const paymentType = document.querySelector('input[name="payment_type"]:checked')?.value || 'interval';
    
    let errors = [];
    
    // At least one of PKS or BAK must be filled
    if (!noPks && !noBak) {
        errors.push('Harus mengisi minimal salah satu: No. PKS atau No. BAK.');
    }
    
    // If PKS is filled, date and file are required
    if (noPks) {
        if (!datePks) {
            errors.push('Tanggal PKS wajib diisi jika No. PKS diisi.');
        }
        if (!filePks) {
            errors.push('File PKS wajib diupload jika No. PKS diisi.');
        }
    }
    
    // If BAK is filled, date and file are required
    if (noBak) {
        if (!dateBak) {
            errors.push('Tanggal BAK wajib diisi jika No. BAK diisi.');
        }
        if (!fileBak) {
            errors.push('File BAK wajib diupload jika No. BAK diisi.');
        }
    }
    
    // Validate payment_start_date (only if interval and date is filled)
    if (paymentType === 'interval' && paymentStartDate) {
        if (startDate && paymentStartDate < startDate) {
            errors.push('Tanggal mulai pembayaran harus sama atau setelah tanggal mulai kontrak.');
        }
        if (endDate && paymentStartDate > endDate) {
            errors.push('Tanggal mulai pembayaran tidak boleh melebihi tanggal akhir kontrak.');
        }
    }
    
    if (errors.length > 0) {
        e.preventDefault();
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: errors.map(err => `• ${err}`).join('<br>'),
                confirmButtonColor: '#4F46E5'
            });
        } else {
            alert('Validasi Gagal:\n\n' + errors.join('\n'));
        }
        return false;
    }
    return true;
}

function assetSelector() {
    return {
        assets: [],
        search: '',
        loading: false,
        hasDates: false,
        startDate: '',
        endDate: '',

        init() {
            const startEl = document.getElementById('start_date');
            const endEl = document.getElementById('end_date');

            // Check if dates are already filled (e.g. from old() after validation failure)
            this.startDate = startEl.value;
            this.endDate = endEl.value;
            if (this.startDate && this.endDate && this.endDate >= this.startDate) {
                this.fetchAssets();
            }

            // Watch for date changes
            startEl.addEventListener('change', () => {
                this.startDate = startEl.value;
                this.checkAndFetch();
            });
            endEl.addEventListener('change', () => {
                this.endDate = endEl.value;
                this.checkAndFetch();
            });
        },

        checkAndFetch() {
            if (this.startDate && this.endDate && this.endDate >= this.startDate) {
                this.fetchAssets();
            } else {
                this.hasDates = false;
                this.assets = [];
            }
        },

        async fetchAssets() {
            this.loading = true;
            this.hasDates = true;

            try {
                const url = new URL('{{ route("contracts.assetsForPeriod") }}', window.location.origin);
                url.searchParams.set('start_date', this.startDate);
                url.searchParams.set('end_date', this.endDate);

                const response = await fetch(url);
                this.assets = await response.json();
            } catch (error) {
                console.error('Error fetching assets:', error);
                this.assets = [];
            } finally {
                this.loading = false;
            }
        },

        get filteredAssets() {
            if (!this.search) return this.assets;
            const q = this.search.toLowerCase();
            return this.assets.filter(a => 
                a.name.toLowerCase().includes(q) || 
                a.id_gedung.toLowerCase().includes(q)
            );
        }
    };
}
</script>
@endpush
