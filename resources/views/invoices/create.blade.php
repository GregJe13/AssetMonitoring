@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold leading-7 text-gray-900">New Invoice</h2>
            <p class="mt-1 text-sm text-gray-500">Buat pencatatan ad-hoc baru</p>
        </div>
        <a href="{{ route('invoices.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">← Kembali</a>
    </div>

    @if($errors->any())
        <div class="mb-6 rounded-md bg-red-50 p-4">
            <ul class="list-disc list-inside text-sm text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('invoices.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl"
          x-data="{
              tenantType: '{{ old('tenant_id') ? 'registered' : (old('tenant_name_manual') ? 'manual' : 'registered') }}',
              tenantName: '{{ old('tenant_name_manual', '') }}'
          }">
        @csrf

        <div class="px-4 py-6 sm:p-8 space-y-6">
            {{-- Invoice Number --}}
            <div>
                <label for="invoice_number" class="block text-sm font-medium text-gray-900">Nomor Invoice <span class="text-red-500">*</span></label>
                <input type="text" name="invoice_number" id="invoice_number" value="{{ old('invoice_number') }}" required
                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                    placeholder="INV-001/INTI/2026">
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-900">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="description" id="description" rows="3" required
                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                    placeholder="Deskripsi penggunaan asset...">{{ old('description') }}</textarea>
            </div>

            {{-- Amount --}}
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-900">Jumlah (Rp) <span class="text-red-500">*</span></label>
                <div class="relative mt-2">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="text-gray-500 sm:text-sm">Rp</span>
                    </div>
                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}" required min="0" step="0.01"
                        class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                </div>
            </div>

            {{-- Tenant Selection --}}
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-2">Tenant / Peminjam <span class="text-red-500">*</span></label>
                <div class="flex gap-4 mb-3">
                    <label class="flex items-center gap-2 text-sm">
                        <input type="radio" x-model="tenantType" value="registered" class="text-indigo-600 focus:ring-indigo-600">
                        Tenant Terdaftar
                    </label>
                    <label class="flex items-center gap-2 text-sm">
                        <input type="radio" x-model="tenantType" value="manual" class="text-indigo-600 focus:ring-indigo-600">
                        Input Manual
                    </label>
                </div>

                {{-- Registered Tenant Dropdown --}}
                <div x-show="tenantType === 'registered'">
                    <select name="tenant_id" id="tenant_id"
                        x-on:change="tenantName = $event.target.options[$event.target.selectedIndex].text; if($event.target.value === '') tenantName = ''"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        <option value="">-- Pilih Tenant --</option>
                        @foreach($tenants as $tenant)
                            <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>{{ $tenant->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Manual Tenant Name --}}
                <div x-show="tenantType === 'manual'">
                    <input type="text" x-model="tenantName" placeholder="Nama peminjam..."
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                </div>

                {{-- Hidden field to always send tenant_name_manual --}}
                <input type="hidden" name="tenant_name_manual" :value="tenantName">
            </div>

            {{-- Assets --}}
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-2">Assets yang Digunakan</label>
                <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto border rounded-md p-3">
                    @foreach($assets as $asset)
                        <label class="flex items-center gap-2 text-sm py-1 px-2 rounded hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="asset_ids[]" value="{{ $asset->id }}"
                                {{ in_array($asset->id, old('asset_ids', [])) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                            <span>{{ $asset->name }}</span>
                            <span class="text-xs text-gray-400">({{ $asset->id_gedung }})</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Dates --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="invoice_date" class="block text-sm font-medium text-gray-900">Tanggal Invoice <span class="text-red-500">*</span></label>
                    <input type="date" name="invoice_date" id="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" required
                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                </div>
                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-900">Jatuh Tempo</label>
                    <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}"
                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                </div>
            </div>

            {{-- File Upload --}}
            <div>
                <label for="invoice_file" class="block text-sm font-medium text-gray-900">Lampiran</label>
                <input type="file" name="invoice_file" id="invoice_file"
                    class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="text-xs text-gray-400 mt-1">Format: PDF, JPG, PNG, DOC, DOCX (maks 10MB)</p>
            </div>

            {{-- Notes --}}
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-900">Catatan</label>
                <textarea name="notes" id="notes" rows="2"
                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                    placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-end gap-x-4 border-t border-gray-900/10 px-4 py-4 sm:px-8">
            <a href="{{ route('invoices.index') }}" class="text-sm font-semibold text-gray-900">Batal</a>
            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                Simpan Invoice
            </button>
        </div>
    </form>
</div>
@endsection
