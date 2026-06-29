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
            @unless(Auth::user()->isGuest())
                <div class="flex items-center gap-3">
                    <span
                        class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                            aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M6 3.75A2.75 2.75 0 003.25 6.5a2.75 2.75 0 002.75 2.75h1.5A2.75 2.75 0 0010.25 6.5 2.75 2.75 0 007.5 3.75H6zm0 10.5a.75.75 0 00-1.5 0v1.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75V15a.75.75 0 00-.75-.75H6zM13.5 3.75a.75.75 0 00-1.5 0v1.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75V15a.75.75 0 00-.75-.75h-1.5z"
                                clip-rule="evenodd" />
                            <path
                                d="M6.75 12.75a.75.75 0 00-.75.75V15c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75v-1.5a.75.75 0 00-.75-.75h-1.5z" />
                        </svg>
                        Export Report
                    </span>
                    <a href="{{ route('contracts.create') }}"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                clip-rule="evenodd" />
                        </svg>
                        New Contract
                    </a>
                </div>
            @endunless
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-6">
            <!-- Revenue -->
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 lg:col-span-2">
                <div class="p-4 px-6 pt-6">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-emerald-50 text-emerald-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                            </svg>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="truncate text-sm font-medium text-gray-500">Total Cash In</dt>
                                <dd>
                                    <div class="text-xl font-bold font-mono text-gray-900">Rp
                                        {{ number_format($totalRevenue / 1000000, 1) }} M
                                    </div>
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

            <!-- Total YTD Actual -->
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 lg:col-span-2">
                <div class="p-4 px-6 pt-6">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-teal-50 text-teal-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="truncate text-sm font-medium text-gray-500">Total YTD (Aktual)</dt>
                                <dd>
                                    <div class="text-xl font-bold font-mono text-gray-900">Rp
                                        {{ number_format($totalActualYtd / 1000000, 1) }} M
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-2">
                    <div class="text-xs font-medium text-teal-600">
                        Realisasi aktual Jan–Des {{ $accrualYear }}
                    </div>
                </div>
            </div>

            <!-- Active Tenants -->
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 lg:col-span-2">
                <div class="p-4 px-6 pt-6">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-purple-50 text-purple-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="truncate text-sm font-medium text-gray-500">Tenant Aktif</dt>
                                <dd>
                                    <div class="text-xl font-bold font-mono text-gray-900">{{ $totalActiveTenantsCount }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-2">
                    <div class="flex items-center justify-between text-xs font-medium">
                        <span class="text-purple-600">Sewa: {{ $activeSewaTenantsCount }}</span>
                        <span class="text-gray-300">|</span>
                        <span class="text-purple-600">KSU: {{ $activeKsuTenantsCount }}</span>
                    </div>
                </div>
            </div>

            <!-- Total Accrual YTD -->
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 lg:col-span-2">
                <div class="p-4 px-6 pt-6">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-indigo-50 text-indigo-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="truncate text-sm font-medium text-gray-500">Total Accrual</dt>
                                <dd>
                                    <div class="text-xl font-bold font-mono text-gray-900">Rp
                                        {{ number_format($totalAccrualYtd / 1000000, 1) }} M
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-2">
                    <div class="text-xs font-medium text-indigo-600">
                        Based on contract value
                    </div>
                </div>
            </div>

            <!-- Overdue -->
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 lg:col-span-2">
                <div class="p-4 px-6 pt-6">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-red-50 text-red-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
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
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 lg:col-span-2">
                <div class="p-4 px-6 pt-6">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-yellow-50 text-yellow-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="truncate text-sm font-medium text-gray-500">Expiring Soon</dt>
                                <dd>
                                    <div class="text-xl font-bold font-mono text-gray-900">{{ $contractsExpiringSoon }}
                                    </div>
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
                <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">Cash In Trend</h3>
                <div id="revenueChart" class="w-full h-80"></div>
            </div>

            <!-- Asset Area Composition Chart -->
            <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 p-6">
                <h3 class="text-base font-semibold leading-6 text-gray-900 mb-1">Komposisi Luas Aset</h3>
                <p class="mb-4 text-xs text-gray-500">Pembagian total luas: dipakai perusahaan, disewa tenant, dan belum
                    dipakai (m²)</p>
                <div id="assetAreaChart" class="w-full h-64 flex items-center justify-center"></div>
            </div>
        </div>

        <!-- Accrual vs Actual Revenue Chart -->
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 p-6" x-data="actualRevenueModal()">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-3">
                <div>
                    <h3 class="text-base font-semibold leading-6 text-gray-900">Accrual vs Actual Revenue</h3>
                    <p class="mt-1 text-xs text-gray-500">Perbandingan nilai accrual basis dengan pendapatan aktual (manual
                        input)</p>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Year Filter -->
                    <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <label for="accrualYearFilter"
                            class="text-xs font-medium text-gray-500 whitespace-nowrap">Tahun:</label>
                        <select name="accrual_year" id="accrualYearFilter" onchange="this.form.submit()"
                            class="block rounded-md border-0 py-1.5 pl-3 pr-8 text-sm text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 bg-white">
                            @foreach($availableYears as $yr)
                                <option value="{{ $yr }}" {{ $yr == $accrualYear ? 'selected' : '' }}>{{ $yr }}</option>
                            @endforeach
                        </select>
                    </form>

                    @unless(Auth::user()->isGuest())
                        <!-- Input Actual Button -->
                        <button @click="openModal()"
                            class="inline-flex items-center gap-1.5 rounded-md bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Input Aktual
                        </button>
                    @endunless
                </div>
            </div>

            <!-- Legend -->
            <div class="flex items-center gap-6 mb-4">
                <div class="flex items-center gap-2 text-xs font-medium text-gray-600">
                    <span class="inline-block w-3 h-3 rounded-sm" style="background-color: #6366f1;"></span>
                    Accrual Basis
                </div>
                <div class="flex items-center gap-2 text-xs font-medium text-gray-600">
                    <span class="inline-block w-3 h-3 rounded-sm" style="background-color: #10b981;"></span>
                    Aktual (Manual Input)
                </div>
            </div>

            <div id="accrualChart" class="w-full h-80"></div>

            <!-- Modal Input Actual Revenue -->
            <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
                role="dialog" aria-modal="true">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <!-- Backdrop -->
                    <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal()"></div>

                    <!-- Modal Panel -->
                    <div x-show="showModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                        Input Pendapatan Aktual
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Masukkan nilai pendapatan aktual yang diterima untuk bulan dan tahun tertentu.
                                    </p>

                                    <div class="mt-4 space-y-4">
                                        <!-- Year & Month -->
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label for="inputYear"
                                                    class="block text-xs font-medium text-gray-700 mb-1">Tahun</label>
                                                <select x-model="form.year" id="inputYear"
                                                    class="block w-full rounded-md border-0 py-2 pl-3 pr-8 text-sm text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600">
                                                    @foreach($availableYears as $yr)
                                                        <option value="{{ $yr }}">{{ $yr }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label for="inputMonth"
                                                    class="block text-xs font-medium text-gray-700 mb-1">Bulan</label>
                                                <select x-model="form.month" id="inputMonth"
                                                    class="block w-full rounded-md border-0 py-2 pl-3 pr-8 text-sm text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600">
                                                    <option value="1">Januari</option>
                                                    <option value="2">Februari</option>
                                                    <option value="3">Maret</option>
                                                    <option value="4">April</option>
                                                    <option value="5">Mei</option>
                                                    <option value="6">Juni</option>
                                                    <option value="7">Juli</option>
                                                    <option value="8">Agustus</option>
                                                    <option value="9">September</option>
                                                    <option value="10">Oktober</option>
                                                    <option value="11">November</option>
                                                    <option value="12">Desember</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Amount -->
                                        <div>
                                            <label for="inputAmount"
                                                class="block text-xs font-medium text-gray-700 mb-1">Nilai Pendapatan Aktual
                                                (Rp)</label>
                                            <div class="relative">
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                    <span class="text-gray-500 text-sm">Rp</span>
                                                </div>
                                                <input x-model="form.amount" type="number" id="inputAmount" step="0.01"
                                                    min="0"
                                                    class="block w-full rounded-md border-0 py-2 pl-10 pr-3 text-sm text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600"
                                                    placeholder="0.00">
                                            </div>
                                        </div>

                                        <!-- Notes -->
                                        <div>
                                            <label for="inputNotes"
                                                class="block text-xs font-medium text-gray-700 mb-1">Catatan
                                                (opsional)</label>
                                            <textarea x-model="form.notes" id="inputNotes" rows="2"
                                                class="block w-full rounded-md border-0 py-2 px-3 text-sm text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600"
                                                placeholder="Keterangan tambahan..."></textarea>
                                        </div>

                                        <!-- Error Message -->
                                        <div x-show="errorMsg" x-cloak class="rounded-md bg-red-50 p-3">
                                            <p class="text-xs text-red-700" x-text="errorMsg"></p>
                                        </div>

                                        <!-- Success Message -->
                                        <div x-show="successMsg" x-cloak class="rounded-md bg-green-50 p-3">
                                            <p class="text-xs text-green-700" x-text="successMsg"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                            <button @click="submitForm()" :disabled="saving"
                                class="inline-flex w-full justify-center rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                <span x-show="!saving">Simpan</span>
                                <span x-show="saving" class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    Menyimpan...
                                </span>
                            </button>
                            <button @click="closeModal()" type="button"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Detail Accrual Basis -->
        <div x-data="accrualDetailModal()" @open-accrual-detail.window="openModal($event.detail.year, $event.detail.month)"
            x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-detail-title"
            role="dialog" aria-modal="true">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!-- Backdrop -->
                <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal()"></div>

                <!-- Modal Panel -->
                <div x-show="showModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">

                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start w-full">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full min-w-0">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-detail-title">
                                    Detail Accrual Basis - <span x-text="monthName"></span>
                                </h3>
                                <p class="text-xs text-gray-500 mt-1">
                                    Rincian nilai perhitungan accrual dari kontrak aktif pada bulan ini.
                                </p>

                                <div class="mt-4" x-show="loading">
                                    <div class="flex justify-center py-4">
                                        <svg class="animate-spin h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                        </svg>
                                    </div>
                                </div>

                                <div class="mt-4" x-show="!loading && errorMsg">
                                    <div class="rounded-md bg-red-50 p-3">
                                        <p class="text-xs text-red-700" x-text="errorMsg"></p>
                                    </div>
                                </div>

                                <div class="mt-4 shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg overflow-hidden"
                                    x-show="!loading && !errorMsg">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-300">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col"
                                                        class="py-3.5 pl-4 pr-3 text-left text-xs font-semibold text-gray-900 sm:pl-6">
                                                        Tipe</th>
                                                    <th scope="col"
                                                        class="px-3 py-3.5 text-left text-xs font-semibold text-gray-900">
                                                        Tenant</th>
                                                    <th scope="col"
                                                        class="px-3 py-3.5 text-left text-xs font-semibold text-gray-900">
                                                        No. Kontrak / Invoice</th>
                                                    <th scope="col"
                                                        class="py-3.5 pl-3 pr-4 text-right text-xs font-semibold text-gray-900 sm:pr-6">
                                                        Nilai (Rp)</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                <template x-for="(item, index) in details" :key="index">
                                                    <tr>
                                                        <td class="whitespace-nowrap py-3 pl-4 pr-3 text-xs font-medium text-gray-900 sm:pl-6"
                                                            x-text="item.type"></td>
                                                        <td class="whitespace-nowrap px-3 py-3 text-xs text-gray-500"
                                                            x-text="item.tenant_name"></td>
                                                        <td class="whitespace-nowrap px-3 py-3 text-xs text-gray-500"
                                                            x-text="item.contract_number"></td>
                                                        <td class="whitespace-nowrap py-3 pl-3 pr-4 text-xs text-gray-900 text-right font-mono sm:pr-6"
                                                            x-text="formatCurrency(item.amount)"></td>
                                                    </tr>
                                                </template>
                                                <tr x-show="details.length === 0">
                                                    <td colspan="4" class="py-4 text-center text-xs text-gray-500">Tidak ada
                                                        data accrual pada bulan ini.</td>
                                                </tr>
                                            </tbody>
                                            <tfoot class="bg-gray-50" x-show="details.length > 0">
                                                <tr>
                                                    <th scope="row" colspan="3"
                                                        class="hidden pl-6 pr-3 py-4 text-right text-sm font-semibold text-gray-900 sm:table-cell">
                                                        Total</th>
                                                    <th scope="row"
                                                        class="pl-4 pr-3 py-4 text-left text-sm font-semibold text-gray-900 sm:hidden">
                                                        Total</th>
                                                    <td class="py-4 pl-3 pr-4 text-right text-sm font-semibold text-gray-900 sm:pr-6 font-mono"
                                                        x-text="formatCurrency(totalAmount)"></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button @click="closeModal()" type="button"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deviation / Difference Chart (Aktual − Accrual) -->
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-3">
                <div>
                    <h3 class="text-base font-semibold leading-6 text-gray-900">Selisih Aktual vs Accrual</h3>
                    <p class="mt-1 text-xs text-gray-500">Deviasi bulanan: nilai positif berarti aktual melebihi accrual,
                        negatif berarti di bawah target</p>
                </div>
                <div class="flex items-center gap-4 text-xs font-medium text-gray-600">
                    <div class="flex items-center gap-1.5">
                        <span class="inline-block w-2.5 h-2.5 rounded-full" style="background-color: #10b981;"></span>
                        Surplus
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="inline-block w-2.5 h-2.5 rounded-full" style="background-color: #ef4444;"></span>
                        Defisit
                    </div>
                </div>
            </div>
            <div id="deviationChart" class="w-full" style="height: 220px;"></div>
        </div>


        <!-- Pending Renewal Warning -->
        @unless(Auth::user()->isGuest())
            @if(isset($pendingRenewals) && $pendingRenewals->count() > 0)
                <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 mb-5">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z"
                                    clip-rule="evenodd" />
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
                                            <span
                                                class="text-sm font-medium text-gray-900">{{ $workflow->contract->tenant->name ?? '-' }}</span>
                                            <span
                                                class="text-xs text-gray-500 ml-2">{{ $workflow->contract->no_pks ?? $workflow->contract->no_bak ?? '' }}</span>
                                        </div>
                                        <div class="flex items-center text-xs text-amber-600 group-hover:text-amber-800">
                                            Pilih tindakan
                                            <svg class="ml-1 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                            </svg>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endunless

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
                                        <p class="text-sm font-semibold leading-6 text-gray-900">{{ $contract->tenant->name }}
                                        </p>
                                        <p
                                            class="rounded-md whitespace-nowrap px-1.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $contract->is_expired ? 'text-red-700 bg-red-50 ring-red-600/10' : 'text-yellow-800 bg-yellow-50 ring-yellow-600/20' }}">
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
                                    <!-- Notes (editable for worker+, read-only for guest) -->
                                    @if(Auth::user()->isGuest())
                                        @if($contract->renewal_notes)
                                            <div class="mt-3">
                                                <div
                                                    class="text-xs text-gray-600 bg-gray-50 rounded-md px-2 py-1.5 border border-gray-200">
                                                    <span class="font-medium text-gray-500">Notes:</span>
                                                    {{ $contract->renewal_notes }}
                                                </div>
                                            </div>
                                        @endif
                                    @else
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
                                                        <div
                                                            class="text-xs text-gray-600 bg-gray-50 rounded-md px-2 py-1.5 border border-gray-200 group-hover:border-indigo-300 group-hover:bg-indigo-50 transition-colors">
                                                            <span class="font-medium text-gray-500">Notes:</span>
                                                            <span x-text="notes"></span>
                                                            <span
                                                                class="text-gray-400 ml-1 opacity-0 group-hover:opacity-100">(click
                                                                to edit)</span>
                                                        </div>
                                                    </template>
                                                    <template x-if="!notes || !notes.trim()">
                                                        <div
                                                            class="text-xs text-gray-400 italic hover:text-indigo-600 flex items-center gap-1">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                                </path>
                                                            </svg>
                                                            Click to add progress notes...
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                            <template x-if="editing">
                                                <div class="space-y-2">
                                                    <textarea x-model="notes" @keydown.escape="editing = false"
                                                        class="block w-full rounded-md border-0 py-1.5 px-2 text-xs text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600"
                                                        rows="2"
                                                        placeholder="e.g., Menunggu konfirmasi perpanjangan dari tenant, atau Proses terminasi sedang berjalan..."
                                                        x-ref="notesInput"
                                                        x-init="$nextTick(() => $refs.notesInput.focus())"></textarea>
                                                    <div class="flex gap-2">
                                                        <button @click="saveNotes()" :disabled="saving"
                                                            class="inline-flex items-center rounded-md bg-indigo-600 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 disabled:opacity-50">
                                                            <span x-show="!saving">Save</span>
                                                            <span x-show="saving">Saving...</span>
                                                        </button>
                                                        <button @click="editing = false"
                                                            class="text-xs text-gray-500 hover:text-gray-700">Cancel</button>
                                                    </div>
                                                </div>
                                            </template>
                                            <template x-if="saved">
                                                <div class="text-xs text-green-600 mt-1">✓ Saved!</div>
                                            </template>
                                        </div>
                                    @endif
                                </div>
                                @unless(Auth::user()->isGuest())
                                    <div class="flex-shrink-0 flex gap-2">
                                        @if($contract->workflow)
                                                        @php
                                                            $finalSteps = ['closed', 'renewed'];
                                                            $isAtFinalStep = in_array($contract->workflow->current_step, $finalSteps);
                                                        @endphp
                                                        <a href="{{ route('workflow.show', $contract) }}" class="rounded-md px-2.5 py-1.5 text-sm font-semibold shadow-sm ring-1 ring-inset transition-colors
                                                                                                                                                                                                                                                                       {{ $isAtFinalStep
                                            ? 'bg-green-50 text-green-700 ring-green-300 hover:bg-green-100'
                                            : 'bg-indigo-50 text-indigo-600 ring-indigo-200 hover:bg-indigo-100' }}">
                                                            {{ $isAtFinalStep ? '✓ ' : '' }}{{ $contract->workflow->getCurrentStepLabel() }}
                                                        </a>
                                        @else
                                            <form action="{{ route('workflow.start', $contract) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="rounded-md bg-yellow-50 px-2.5 py-1.5 text-sm font-semibold text-yellow-700 shadow-sm ring-1 ring-inset ring-yellow-300 hover:bg-yellow-100">SOP
                                                    Akhir Kontrak</button>
                                            </form>
                                        @endif
                                        <a href="{{ route('contracts.show', $contract) }}"
                                            class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">View</a>
                                    </div>
                                @endunless
                            </div>
                        </li>
                    @empty
                        <li class="py-5 px-6 text-center text-sm text-gray-500">No contracts expiring in next 60 days.</li>
                    @endforelse
                </ul>
                @if($totalExpiringContracts > 3)
                    <div class="border-t border-gray-200 px-6 py-3">
                        <a href="{{ route('expiring-contracts.index') }}"
                            class="flex items-center justify-center gap-1.5 text-sm font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">
                            See More ({{ $totalExpiringContracts - 3 }} more)
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                @endif
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
                                    <p class="text-sm font-semibold leading-6 text-gray-900">
                                        {{ $payment->contract->tenant->name }}
                                    </p>
                                    <p
                                        class="rounded-md whitespace-nowrap mt-0.5 px-1.5 py-0.5 text-xs font-medium ring-1 ring-inset text-red-700 bg-red-50 ring-red-600/10">
                                        Overdue</p>
                                </div>
                                <div class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                                    <p class="whitespace-nowrap">Period #{{ $payment->period_number }}</p>
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
                        <li class="py-5 px-6 text-center text-sm text-gray-500">No overdue payments. Good job!</li>
                    @endforelse
                </ul>
                @if($totalOverduePayments > 5)
                    <div class="border-t border-gray-200 px-6 py-3">
                        <a href="{{ route('overdue-payments.index') }}"
                            class="flex items-center justify-center gap-1.5 text-sm font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">
                            See More ({{ $totalOverduePayments - 5 }} more)
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                @endif
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
                            if (value >= 1000000) return (value / 1000000).toFixed(1) + "M";
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

            // Asset Area Composition Chart (m²: perusahaan / tenant / belum dipakai)
            var areaUsageOptions = {
                series: @json($areaUsageData),
                chart: { type: 'donut', height: 280, fontFamily: 'Instrument Sans, sans-serif' },
                labels: ['Dipakai Perusahaan', 'Dipakai Tenant', 'Belum Dipakai'],
                colors: ['#6366f1', '#f59e0b', '#e5e7eb'],
                legend: { position: 'bottom' },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) { return Math.round(val) + '%'; }
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return new Intl.NumberFormat('id-ID').format(val) + ' m²';
                        }
                    }
                },
                noData: { text: 'Belum ada data luas' }
            };
            var areaUsageChart = new ApexCharts(document.querySelector("#assetAreaChart"), areaUsageOptions);
            areaUsageChart.render();

            // Accrual vs Actual Revenue Chart (Grouped Bar)
            var accrualRaw = @json($accrualData);
            var actualRaw = @json($actualData);
            var accrualMonthLabels = @json($accrualMonths);

            var accrualOptions = {
                series: [{
                    name: 'Accrual Basis',
                    data: accrualRaw
                }, {
                    name: 'Aktual (Manual)',
                    data: actualRaw
                }],
                chart: {
                    type: 'bar',
                    height: 320,
                    fontFamily: 'Instrument Sans, sans-serif',
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    events: {
                        dataPointSelection: function (event, chartContext, config) {
                            if (config.seriesIndex === 0) { // Accrual Basis series
                                var monthIndex = config.dataPointIndex + 1;
                                var year = {{ $accrualYear }};
                                window.dispatchEvent(new CustomEvent('open-accrual-detail', {
                                    detail: { month: monthIndex, year: year }
                                }));
                            }
                        }
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        borderRadius: 4,
                        borderRadiusApplication: 'end'
                    }
                },
                dataLabels: { enabled: false },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: accrualMonthLabels,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        style: {
                            fontSize: '11px',
                            colors: '#6b7280'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function (value) {
                            if (value >= 1000000000) return (value / 1000000000).toFixed(1) + "B";
                            if (value >= 1000000) return (value / 1000000).toFixed(1) + "M";
                            if (value >= 1000) return (value / 1000).toFixed(0) + "K";
                            return value;
                        },
                        style: {
                            fontSize: '11px',
                            colors: '#6b7280'
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                colors: ['#6366f1', '#10b981'], // Indigo, Emerald
                grid: {
                    strokeDashArray: 4,
                    borderColor: '#e5e7eb'
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
                        }
                    }
                },
                legend: {
                    show: false // Using custom legend above chart
                }
            };

            var accrualChart = new ApexCharts(document.querySelector("#accrualChart"), accrualOptions);
            accrualChart.render();

            // Deviation Chart (Aktual − Accrual) — Separate section
            var differenceData = accrualRaw.map(function (val, i) {
                return Math.round((actualRaw[i] - val) * 100) / 100;
            });

            // Color each data point: green for positive (surplus), red for negative (deficit)
            var deviationColors = differenceData.map(function (val) {
                return val >= 0 ? '#10b981' : '#ef4444';
            });

            var deviationOptions = {
                series: [{
                    name: 'Selisih (Aktual − Accrual)',
                    data: differenceData
                }],
                chart: {
                    type: 'area',
                    height: 220,
                    fontFamily: 'Instrument Sans, sans-serif',
                    toolbar: { show: false },
                    zoom: { enabled: false }
                },
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                markers: {
                    size: 5,
                    strokeWidth: 2,
                    strokeColors: '#fff',
                    hover: { size: 7 },
                    discrete: differenceData.map(function (val, i) {
                        return {
                            seriesIndex: 0,
                            dataPointIndex: i,
                            fillColor: val >= 0 ? '#10b981' : '#ef4444',
                            strokeColor: '#fff',
                            size: 5
                        };
                    })
                },
                xaxis: {
                    categories: accrualMonthLabels,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        style: {
                            fontSize: '11px',
                            colors: '#6b7280'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function (value) {
                            var abs = Math.abs(value);
                            var sign = value < 0 ? '-' : '';
                            var prefix = value > 0 ? '+' : '';
                            if (abs >= 1000000000) return prefix + sign + (abs / 1000000000).toFixed(1) + "B";
                            if (abs >= 1000000) return prefix + sign + (abs / 1000000).toFixed(1) + "M";
                            if (abs >= 1000) return prefix + sign + (abs / 1000).toFixed(0) + "K";
                            return (value > 0 ? '+' : '') + value;
                        },
                        style: {
                            fontSize: '11px',
                            colors: '#6b7280'
                        }
                    }
                },
                annotations: {
                    yaxis: [{
                        y: 0,
                        borderColor: '#9ca3af',
                        strokeDashArray: 0,
                        borderWidth: 1,
                        label: {
                            text: 'Break-even',
                            position: 'left',
                            style: {
                                fontSize: '10px',
                                color: '#6b7280',
                                background: '#f9fafb',
                                padding: { left: 6, right: 6, top: 2, bottom: 2 }
                            }
                        }
                    }]
                },
                colors: ['#f59e0b'], // Amber line
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                },
                grid: {
                    strokeDashArray: 4,
                    borderColor: '#e5e7eb'
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            var prefix = val > 0 ? '+' : '';
                            return prefix + 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
                        }
                    }
                },
                legend: { show: false }
            };

            var deviationChart = new ApexCharts(document.querySelector("#deviationChart"), deviationOptions);
            deviationChart.render();
        });

        // Alpine.js component for Actual Revenue Modal
        function actualRevenueModal() {
            return {
                showModal: false,
                saving: false,
                errorMsg: '',
                successMsg: '',
                form: {
                    year: {{ $accrualYear }},
                    month: new Date().getMonth() + 1,
                    amount: '',
                    notes: ''
                },
                openModal() {
                    this.showModal = true;
                    this.errorMsg = '';
                    this.successMsg = '';
                },
                closeModal() {
                    this.showModal = false;
                    this.errorMsg = '';
                    this.successMsg = '';
                },
                async submitForm() {
                    this.saving = true;
                    this.errorMsg = '';
                    this.successMsg = '';

                    if (!this.form.amount || parseFloat(this.form.amount) < 0) {
                        this.errorMsg = 'Nilai pendapatan harus diisi dan tidak boleh negatif.';
                        this.saving = false;
                        return;
                    }

                    try {
                        const response = await fetch('{{ route("actual-revenue.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                year: parseInt(this.form.year),
                                month: parseInt(this.form.month),
                                amount: parseFloat(this.form.amount),
                                notes: this.form.notes || null
                            })
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                            this.successMsg = data.message || 'Berhasil disimpan!';
                            this.saving = false;

                            // Reload page after short delay to refresh chart data
                            setTimeout(() => {
                                const url = new URL(window.location.href);
                                url.searchParams.set('accrual_year', this.form.year);
                                window.location.href = url.toString();
                            }, 1000);
                        } else {
                            // Handle validation errors
                            if (data.errors) {
                                const firstError = Object.values(data.errors)[0];
                                this.errorMsg = Array.isArray(firstError) ? firstError[0] : firstError;
                            } else {
                                this.errorMsg = data.message || 'Terjadi kesalahan saat menyimpan.';
                            }
                            this.saving = false;
                        }
                    } catch (err) {
                        this.errorMsg = 'Gagal terhubung ke server. Silakan coba lagi.';
                        this.saving = false;
                    }
                }
            };
        }

        // Alpine.js component for Accrual Detail Modal
        function accrualDetailModal() {
            return {
                showModal: false,
                loading: false,
                errorMsg: '',
                monthName: '',
                details: [],
                totalAmount: 0,

                openModal(year, month) {
                    this.showModal = true;
                    this.fetchDetails(year, month);
                },

                closeModal() {
                    this.showModal = false;
                    // Reset state
                    setTimeout(() => {
                        this.details = [];
                        this.monthName = '';
                        this.totalAmount = 0;
                        this.errorMsg = '';
                    }, 300);
                },

                formatCurrency(value) {
                    return new Intl.NumberFormat('id-ID').format(value);
                },

                async fetchDetails(year, month) {
                    this.loading = true;
                    this.errorMsg = '';

                    try {
                        const response = await fetch(`{{ route('dashboard.accrual-details') }}?year=${year}&month=${month}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const data = await response.json();

                        if (response.ok) {
                            this.details = data.details;
                            this.monthName = data.month_name;
                            this.totalAmount = data.total;
                        } else {
                            this.errorMsg = data.message || 'Gagal memuat detail data.';
                        }
                    } catch (err) {
                        this.errorMsg = 'Terjadi kesalahan jaringan saat memuat data.';
                    } finally {
                        this.loading = false;
                    }
                }
            };
        }
    </script>
@endsection