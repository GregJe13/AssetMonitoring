@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto" x-data="amendmentForm()">
    <div class="mb-8">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
            Buat Amandemen Kontrak
        </h2>
        <p class="mt-2 text-sm text-gray-500">Buat amandemen untuk perpanjangan/perubahan kontrak existing.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-md bg-red-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Terdapat {{ $errors->count() }} kesalahan:</h3>
                    <ul class="mt-2 list-disc list-inside text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('amendments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Step 1: Pilih Tenant -->
        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl p-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 text-sm font-bold mr-2">1</span>
                Pilih Tenant
            </h3>
            <select id="tenant_id" x-model="selectedTenantId" @change="fetchContracts()" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                <option value="">-- Pilih Tenant --</option>
                @foreach($tenants as $tenant)
                    <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>{{ $tenant->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Step 2: Pilih Kontrak -->
        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl p-6" x-show="selectedTenantId" x-cloak x-transition>
            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 text-sm font-bold mr-2">2</span>
                Pilih Kontrak (5 Terbaru)
            </h3>

            <div x-show="loadingContracts" class="flex items-center justify-center py-8">
                <svg class="animate-spin h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="ml-2 text-sm text-gray-500">Memuat kontrak...</span>
            </div>

            <div x-show="!loadingContracts && contracts.length === 0" class="text-center py-6 text-gray-500 text-sm">
                Tenant ini belum memiliki kontrak.
            </div>

            <div x-show="!loadingContracts && contracts.length > 0" class="space-y-3">
                <template x-for="contract in contracts" :key="contract.id">
                    <label class="block cursor-pointer" :class="selectedContractId == contract.id ? 'ring-2 ring-indigo-500' : 'ring-1 ring-gray-200 hover:ring-gray-300'">
                        <div class="rounded-lg p-4 transition-all" :class="selectedContractId == contract.id ? 'bg-indigo-50' : 'bg-white hover:bg-gray-50'">
                            <div class="flex items-start">
                                <input type="radio" name="contract_id" :value="contract.id" x-model="selectedContractId" @change="selectContract(contract)" class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900" x-text="contract.no_pks || contract.no_bak || 'No Number'"></p>
                                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium"
                                              :class="contract.status === 'active' ? 'bg-green-100 text-green-700' : (contract.status === 'expired' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700')"
                                              x-text="contract.status"></span>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">
                                        <span x-text="contract.start_date_formatted"></span> — <span x-text="contract.end_date_formatted"></span>
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1" x-show="contract.amendment_count > 0">
                                        Sudah ada <span x-text="contract.amendment_count" class="font-medium"></span> amandemen
                                    </p>
                                    <!-- Assets list -->
                                    <div class="mt-2 flex flex-wrap gap-1.5" x-show="contract.assets && contract.assets.length > 0">
                                        <template x-for="asset in contract.assets" :key="asset.name">
                                            <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/10">
                                                <span x-text="asset.name"></span>
                                                <span class="ml-1 text-blue-400" x-text="'(' + asset.rented_area + ' m²)'"></span>
                                            </span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </label>
                </template>
            </div>
        </div>

        <!-- Step 3: Detail Amandemen (shown after contract is selected) -->
        <div x-show="selectedContract" x-cloak x-transition class="space-y-6">

            <!-- Old Dates Display -->
            <div class="bg-amber-50 border border-amber-200 sm:rounded-xl p-6">
                <h3 class="text-base font-semibold leading-6 text-amber-800 mb-4">
                    <svg class="inline-block w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                    Data Kontrak Sebelumnya
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-amber-600 font-medium">Start Date (Kontrak Lama)</p>
                        <p class="text-sm font-semibold text-amber-900" x-text="selectedContract?.start_date_formatted || '-'"></p>
                    </div>
                    <div>
                        <p class="text-xs text-amber-600 font-medium">End Date (Kontrak Lama)</p>
                        <p class="text-sm font-semibold text-amber-900" x-text="selectedContract?.end_date_formatted || '-'"></p>
                    </div>
                </div>
            </div>

            <!-- Amendment Info -->
            <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl p-6">
                <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 text-sm font-bold mr-2">3</span>
                    Detail Amandemen
                </h3>
                <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium leading-6 text-gray-900">Amandemen Ke-</label>
                        <input type="number" name="amendment_number" :value="(selectedContract?.amendment_count || 0) + 1" min="1" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium leading-6 text-gray-900">Nomor Amandemen</label>
                        <input type="text" name="no_amendment" value="{{ old('no_amendment') }}" placeholder="AMD/001/2026" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium leading-6 text-gray-900">Tanggal Amandemen</label>
                        <input type="date" name="date_amendment" value="{{ old('date_amendment') }}" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                    </div>
                </div>
            </div>

            <!-- New Dates -->
            <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl p-6">
                <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 text-sm font-bold mr-2">4</span>
                    Periode Amandemen
                </h3>
                <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label class="block text-sm font-medium leading-6 text-gray-900">Tanggal Mulai Baru</label>
                        <input type="date" name="new_start_date" x-model="newStartDate" value="{{ old('new_start_date') }}" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-sm font-medium leading-6 text-gray-900">Tanggal Akhir Baru</label>
                        <input type="date" name="new_end_date" x-model="newEndDate" value="{{ old('new_end_date') }}" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                    </div>
                </div>
            </div>

            <!-- Assets Selection -->
            <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl p-6" x-show="newStartDate && newEndDate" x-transition>
                <h3 class="text-base font-semibold leading-6 text-gray-900 mb-2">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 text-sm font-bold mr-2">5</span>
                    Pilih Assets & Tentukan Area
                </h3>
                <p class="text-sm text-gray-500 mb-4">Masukkan tanggal mulai dan akhir amandemen terlebih dahulu untuk melihat ketersediaan asset.</p>

                <!-- Loading state -->
                <div x-show="loadingAssets" class="text-center py-10">
                    <svg class="animate-spin mx-auto h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <p class="text-sm text-gray-500 mt-2">Memuat ketersediaan asset...</p>
                </div>

                <!-- Asset list -->
                <div x-show="!loadingAssets && assets.length > 0" x-cloak>
                    <div class="mb-4">
                        <input type="text" x-model="assetSearch" placeholder="Cari nama atau ID asset..." class="block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
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

            <!-- Financial Terms -->
            <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl p-6" x-data="{
                paymentType: '{{ old('payment_type', 'interval') }}',
                termins: {{ old('termins') ? json_encode(old('termins')) : '[]' }},
                addTermin() { this.termins.push({ due_date: '', amount_due: '' }); },
                removeTermin(i) { this.termins.splice(i, 1); },
                get totalTermin() { return this.termins.reduce((s, t) => s + (parseFloat(t.amount_due) || 0), 0); }
            }">
                <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 text-sm font-bold mr-2">6</span>
                    Ketentuan Pembayaran
                </h3>
                <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                    <div class="sm:col-span-3" x-show="paymentType !== 'termin'">
                        <label class="block text-sm font-medium leading-6 text-gray-900">Total Nilai Sewa</label>
                        <div class="relative mt-2">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-sm text-gray-500">Rp</span>
                            <input type="number" name="total_rental_value" value="{{ old('total_rental_value') }}" step="0.01" class="pl-10 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                        </div>
                    </div>
                    <div class="sm:col-span-3" x-show="paymentType === 'termin'" x-cloak>
                        <label class="block text-sm font-medium leading-6 text-gray-900">Total Nilai Sewa (Auto)</label>
                        <div class="relative mt-2">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-sm text-gray-500">Rp</span>
                            <input type="number" name="total_rental_value" :value="totalTermin" readonly class="pl-10 block w-full rounded-md border-0 py-1.5 text-gray-900 bg-gray-50 shadow-sm ring-1 ring-inset ring-gray-300 sm:text-sm sm:leading-6 cursor-not-allowed">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Dihitung otomatis dari total semua termin.</p>
                    </div>
                    <div class="sm:col-span-3" x-show="paymentType === 'interval'">
                        <label class="block text-sm font-medium leading-6 text-gray-900">Tanggal Mulai Bayar</label>
                        <input type="date" name="payment_start_date" value="{{ old('payment_start_date') }}" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        <p class="text-xs text-gray-400 mt-1">Kosongkan untuk mengikuti tanggal mulai amandemen</p>
                    </div>

                    <!-- Payment Type Selection -->
                    <div class="col-span-full">
                        <label class="block text-sm font-medium leading-6 text-gray-900 mb-3">Tipe Pembayaran</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <label class="relative flex cursor-pointer rounded-lg border p-4 transition-colors" :class="paymentType === 'interval' ? 'border-indigo-600 bg-indigo-50 ring-2 ring-indigo-600' : 'border-gray-300 hover:border-gray-400'">
                                <input type="radio" name="payment_type" value="interval" x-model="paymentType" class="sr-only">
                                <span class="flex flex-1 flex-col">
                                    <span class="text-sm font-semibold" :class="paymentType === 'interval' ? 'text-indigo-900' : 'text-gray-900'">Interval</span>
                                    <span class="mt-1 text-xs" :class="paymentType === 'interval' ? 'text-indigo-700' : 'text-gray-500'">Pembayaran berkala.</span>
                                </span>
                            </label>
                            <label class="relative flex cursor-pointer rounded-lg border p-4 transition-colors" :class="paymentType === 'upfront' ? 'border-indigo-600 bg-indigo-50 ring-2 ring-indigo-600' : 'border-gray-300 hover:border-gray-400'">
                                <input type="radio" name="payment_type" value="upfront" x-model="paymentType" class="sr-only">
                                <span class="flex flex-1 flex-col">
                                    <span class="text-sm font-semibold" :class="paymentType === 'upfront' ? 'text-indigo-900' : 'text-gray-900'">Upfront</span>
                                    <span class="mt-1 text-xs" :class="paymentType === 'upfront' ? 'text-indigo-700' : 'text-gray-500'">Bayar 100% di muka.</span>
                                </span>
                            </label>
                            <label class="relative flex cursor-pointer rounded-lg border p-4 transition-colors" :class="paymentType === 'termin' ? 'border-indigo-600 bg-indigo-50 ring-2 ring-indigo-600' : 'border-gray-300 hover:border-gray-400'">
                                <input type="radio" name="payment_type" value="termin" x-model="paymentType" class="sr-only">
                                <span class="flex flex-1 flex-col">
                                    <span class="text-sm font-semibold" :class="paymentType === 'termin' ? 'text-indigo-900' : 'text-gray-900'">Termin</span>
                                    <span class="mt-1 text-xs" :class="paymentType === 'termin' ? 'text-indigo-700' : 'text-gray-500'">Jadwal & nominal manual.</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="sm:col-span-3" x-show="paymentType === 'interval'" x-transition>
                        <label class="block text-sm font-medium leading-6 text-gray-900">Interval Pembayaran</label>
                        <input type="number" name="payment_interval_value" value="{{ old('payment_interval_value', 1) }}" min="1" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                    <div class="sm:col-span-3" x-show="paymentType === 'interval'" x-transition>
                        <label class="block text-sm font-medium leading-6 text-gray-900">Unit Interval</label>
                        <select name="payment_interval_unit" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option value="month" {{ old('payment_interval_unit') === 'month' ? 'selected' : '' }}>Bulan</option>
                            <option value="year" {{ old('payment_interval_unit') === 'year' ? 'selected' : '' }}>Tahun</option>
                        </select>
                    </div>

                    <!-- Termin Builder -->
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
                            <div x-show="termins.length > 0" class="mt-4 pt-4 border-t border-gray-200 flex items-center justify-between">
                                <span class="text-sm text-gray-600"><span x-text="termins.length"></span> termin</span>
                                <span class="text-sm font-semibold text-gray-900">Total: Rp <span x-text="totalTermin.toLocaleString('id-ID')"></span></span>
                            </div>
                        </div>
                        @error('termins') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Parties -->
            <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl p-6">
                <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 text-sm font-bold mr-2">7</span>
                    Pihak-pihak
                </h3>
                <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label class="block text-sm font-medium leading-6 text-gray-900">Pihak Pertama (Lessor)</label>
                        <input type="text" name="pihak_pertama" value="{{ old('pihak_pertama') }}" placeholder="Nama perwakilan" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-sm font-medium leading-6 text-gray-900">Pihak Kedua (Lessee)</label>
                        <input type="text" name="pihak_kedua" value="{{ old('pihak_kedua') }}" placeholder="Nama perwakilan" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl p-6">
                <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 text-sm font-bold mr-2">8</span>
                    Dokumen
                </h3>
                <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label class="block text-sm font-medium leading-6 text-gray-900">Nomor BAK</label>
                        <input type="text" name="no_bak" value="{{ old('no_bak') }}" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-sm font-medium leading-6 text-gray-900">Tanggal BAK</label>
                        <input type="date" name="date_bak" value="{{ old('date_bak') }}" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                    <div class="col-span-full">
                        <label class="block text-sm font-medium leading-6 text-gray-900">Upload File BAK (PDF)</label>
                        <input type="file" name="file_bak" accept=".pdf" class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>

                    <div class="col-span-full border-t pt-4"></div>

                    <div class="sm:col-span-3">
                        <label class="block text-sm font-medium leading-6 text-gray-900">Nomor PKS</label>
                        <input type="text" name="no_pks" value="{{ old('no_pks') }}" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-sm font-medium leading-6 text-gray-900">Tanggal PKS</label>
                        <input type="date" name="date_pks" value="{{ old('date_pks') }}" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                    <div class="col-span-full">
                        <label class="block text-sm font-medium leading-6 text-gray-900">Upload File PKS (PDF)</label>
                        <input type="file" name="file_pks" accept=".pdf" class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl p-6">
                <label class="block text-sm font-medium leading-6 text-gray-900">Catatan</label>
                <textarea name="notes" rows="3" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-x-4">
                <a href="{{ route('amendments.index') }}" class="text-sm font-semibold leading-6 text-gray-600 hover:text-gray-900">Batal</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors">
                    Simpan Amandemen
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function amendmentForm() {
    return {
        selectedTenantId: '{{ old("tenant_id", $selectedTenantId ?? "") }}',
        contracts: [],
        selectedContractId: '{{ old("contract_id", $selectedContractId ?? "") }}',
        selectedContract: null,
        loadingContracts: false,
        newStartDate: '{{ old("new_start_date", "") }}',
        newEndDate: '{{ old("new_end_date", "") }}',
        assets: [],
        assetSearch: '',
        loadingAssets: false,

        async init() {
            if (this.selectedTenantId) {
                await this.fetchContracts();
                // Auto-select contract if passed via query param
                if (this.selectedContractId) {
                    const contract = this.contracts.find(c => c.id == this.selectedContractId);
                    if (contract) {
                        this.selectContract(contract);
                    }
                }
            }

            // Reactively watch date changes to fetch assets
            this.$watch('newStartDate', () => this.fetchAssets());
            this.$watch('newEndDate', () => this.fetchAssets());
        },

        async fetchContracts() {
            if (!this.selectedTenantId) {
                this.contracts = [];
                this.selectedContract = null;
                return;
            }
            this.loadingContracts = true;
            this.selectedContract = null;
            this.selectedContractId = '';
            try {
                const res = await fetch(`/amendments/contracts-for-tenant/${this.selectedTenantId}`);
                this.contracts = await res.json();
            } catch (e) {
                console.error('Failed to fetch contracts', e);
                this.contracts = [];
            }
            this.loadingContracts = false;
        },

        selectContract(contract) {
            this.selectedContract = contract;
            this.selectedContractId = contract.id;
        },

        async fetchAssets() {
            if (!this.newStartDate || !this.newEndDate) return;
            this.loadingAssets = true;
            try {
                const res = await fetch(`/contracts/assets-for-period?start_date=${this.newStartDate}&end_date=${this.newEndDate}`);
                const data = await res.json();
                this.assets = data.map(a => ({
                    ...a,
                    is_full: Number(a.available_area) <= 0
                }));
            } catch (e) {
                console.error('Failed to fetch assets', e);
                this.assets = [];
            }
            this.loadingAssets = false;
        },

        get filteredAssets() {
            if (!this.assetSearch) return this.assets;
            const q = this.assetSearch.toLowerCase();
            return this.assets.filter(a => 
                a.name.toLowerCase().includes(q) || 
                a.id_gedung.toLowerCase().includes(q)
            );
        }
    };
}
</script>
@endsection
