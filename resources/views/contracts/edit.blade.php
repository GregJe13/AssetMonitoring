@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
            Edit Contract
        </h2>
    </div>

    <form action="{{ route('contracts.update', $contract) }}" method="POST" class="space-y-6" enctype="multipart/form-data" onsubmit="return validateEditContractForm(event)">
        @csrf
        @method('PUT')

        <!-- 1. Tenant Selection -->
        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl p-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">Tenant Information</h3>
            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="col-span-full">
                    <label class="block text-sm font-medium leading-6 text-gray-900">Tenant</label>
                    <div class="mt-2">
                        <select name="tenant_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}" {{ $contract->tenant_id == $tenant->id ? 'selected' : '' }}>
                                    {{ $tenant->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label class="block text-sm font-medium leading-6 text-gray-900">Perwakilan Pihak Pertama (Lessor)</label>
                    <p class="text-xs text-gray-500 mb-1">Nama orang yang mewakili dan menandatangani</p>
                    <div class="mt-2">
                        <input type="text" name="pihak_pertama" value="{{ old('pihak_pertama', $contract->pihak_pertama) }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 placeholder:text-gray-400" placeholder="e.g. Ahmad Sulaiman">
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label class="block text-sm font-medium leading-6 text-gray-900">Perwakilan Pihak Kedua (Lessee)</label>
                    <p class="text-xs text-gray-500 mb-1">Nama orang yang mewakili dan menandatangani</p>
                    <div class="mt-2">
                        <input type="text" name="pihak_kedua" value="{{ old('pihak_kedua', $contract->pihak_kedua) }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 placeholder:text-gray-400" placeholder="e.g. Budi Santoso">
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Contract Details -->
        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl p-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">Contract Terms</h3>
            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                
                <div class="sm:col-span-3">
                    <label class="block text-sm font-medium leading-6 text-gray-900">Contract No. (PKS)</label>
                    <div class="mt-2">
                        <input type="text" name="no_pks" id="no_pks" value="{{ old('no_pks', $contract->no_pks) }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        @error('no_pks') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label class="block text-sm font-medium leading-6 text-gray-900">PKS Date</label>
                    <div class="mt-2">
                        <input type="date" name="date_pks" id="date_pks" value="{{ old('date_pks', $contract->date_pks?->format('Y-m-d')) }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        @error('date_pks') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="col-span-full">
                    <label class="block text-sm font-medium leading-6 text-gray-900">File PKS (PDF, max 10MB)</label>
                    <div class="mt-2">
                        @if($contract->file_pks)
                            <p class="text-sm text-gray-600 mb-2">
                                Existing: <a href="{{ route('contracts.file', [$contract, 'pks']) }}" target="_blank" class="text-indigo-600 hover:underline">View Current PKS</a>
                            </p>
                        @endif
                        <input type="file" name="file_pks" accept=".pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('file_pks') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label class="block text-sm font-medium leading-6 text-gray-900">BAK No.</label>
                    <div class="mt-2">
                        <input type="text" name="no_bak" id="no_bak" value="{{ old('no_bak', $contract->no_bak) }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        @error('no_bak') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label class="block text-sm font-medium leading-6 text-gray-900">BAK Date</label>
                    <div class="mt-2">
                        <input type="date" name="date_bak" id="date_bak" value="{{ old('date_bak', $contract->date_bak?->format('Y-m-d')) }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        @error('date_bak') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="col-span-full">
                    <label class="block text-sm font-medium leading-6 text-gray-900">File BAK (PDF, max 10MB)</label>
                    <div class="mt-2">
                        @if($contract->file_bak)
                            <p class="text-sm text-gray-600 mb-2">
                                Existing: <a href="{{ route('contracts.file', [$contract, 'bak']) }}" target="_blank" class="text-indigo-600 hover:underline">View Current BAK</a>
                            </p>
                        @endif
                        <input type="file" name="file_bak" accept=".pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('file_bak') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label class="block text-sm font-medium leading-6 text-gray-900">Start Date</label>
                    <div class="mt-2">
                        <input type="date" name="start_date" value="{{ old('start_date', $contract->start_date->format('Y-m-d')) }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label class="block text-sm font-medium leading-6 text-gray-900">End Date</label>
                    <div class="mt-2">
                        <input type="date" name="end_date" value="{{ old('end_date', $contract->end_date->format('Y-m-d')) }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>
                
                 <div class="sm:col-span-3">
                    <label class="block text-sm font-medium leading-6 text-gray-900">Total Value (Rp)</label>
                    <div class="mt-2">
                        <input type="number" name="total_rental_value" value="{{ old('total_rental_value', $contract->total_rental_value) }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                @if($contract->payment_type === 'interval')
                <div class="sm:col-span-3">
                    <label for="payment_start_date" class="block text-sm font-medium leading-6 text-gray-900">Payment Start Date</label>
                    <p class="text-sm text-gray-500 mb-1">Tanggal mulai jadwal pembayaran</p>
                    <div class="mt-2">
                        <input type="date" name="payment_start_date" id="payment_start_date" value="{{ old('payment_start_date', $contract->payment_start_date ? $contract->payment_start_date->format('Y-m-d') : '') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        @error('payment_start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
                @endif

            </div>
        </div>

         <!-- 3. Asset Selection with Area Input -->
        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl p-6" x-data="{ search: '' }">
            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-2">Edit Rented Assets & Areas</h3>
            <p class="text-sm text-gray-500 mb-4">Modify rented areas. Set to 0 or leave empty to remove an asset.</p>
            
            <div class="mb-4">
                 <input type="text" x-model="search" placeholder="Search..." class="block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 sm:text-sm sm:leading-6">
            </div>

            <div class="space-y-3 max-h-96 overflow-y-auto">
                @foreach($assets as $asset)
                @php
                    // Get currently rented area by THIS contract
                    $currentRented = $attachedAssets[$asset->id] ?? 0;
                    // Effective available = asset.available_area + what this contract already uses
                    // But NEVER more than the asset's total area
                    $effectiveAvailable = min($asset->area_sqm, $asset->available_area + $currentRented);
                    // Skip assets with no available space AND not attached to this contract
                    $shouldHide = $effectiveAvailable <= 0 && $currentRented <= 0;
                @endphp
                @if(!$shouldHide)
                <div class="flex items-center gap-4 p-3 rounded-lg border {{ $currentRented > 0 ? 'bg-indigo-50 border-indigo-200' : 'bg-white border-gray-200 hover:border-indigo-300' }}" 
                     x-show="search === '' || '{{ strtolower($asset->name) }}'.includes(search.toLowerCase())">
                    
                    <!-- Asset Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="font-medium text-gray-900 truncate">{{ $asset->name }}</span>
                            <span class="text-xs text-gray-500 font-mono">{{ $asset->id_gedung }}</span>
                            @if($currentRented > 0)
                                <span class="inline-flex items-center rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-700">Currently Rented</span>
                            @endif
                        </div>
                        <div class="mt-1 flex items-center gap-3 text-xs flex-wrap">
                            <span class="text-gray-500">Total: {{ number_format($asset->area_sqm, 0) }} m²</span>
                            @if($currentRented > 0)
                                <span class="text-indigo-600 font-medium">You rent: {{ number_format($currentRented, 0) }} m²</span>
                            @endif
                            <span class="text-emerald-600 font-medium">
                                Max editable: {{ number_format($effectiveAvailable, 0) }} m²
                            </span>
                        </div>
                    </div>
                    
                    <!-- Area Input -->
                    <div class="w-32 flex-shrink-0">
                        <div class="relative rounded-md shadow-sm">
                            <input type="number" 
                                   name="asset_areas[{{ $asset->id }}]" 
                                   step="0.01"
                                   min="0"
                                   max="{{ $effectiveAvailable }}"
                                   placeholder="0"
                                   value="{{ old('asset_areas.' . $asset->id, $currentRented > 0 ? $currentRented : '') }}"
                                   class="block w-full rounded-md border-0 py-1.5 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <span class="text-gray-500 sm:text-xs">m²</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>

        <div class="flex items-center justify-end gap-x-6">
            <a href="{{ route('contracts.show', $contract) }}" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Update Contract</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
var hasExistingFilePks = {{ $contract->file_pks ? 'true' : 'false' }};
var hasExistingFileBak = {{ $contract->file_bak ? 'true' : 'false' }};

function validateEditContractForm(e) {
    const noPks = document.getElementById('no_pks').value.trim();
    const datePks = document.getElementById('date_pks').value;
    const filePks = document.getElementById('file_pks').files.length > 0;
    
    const noBak = document.getElementById('no_bak').value.trim();
    const dateBak = document.getElementById('date_bak').value;
    const fileBak = document.getElementById('file_bak').files.length > 0;
    
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
        if (!filePks && !hasExistingFilePks) {
            errors.push('File PKS wajib diupload jika No. PKS diisi.');
        }
    }
    
    // If BAK is filled, date and file are required
    if (noBak) {
        if (!dateBak) {
            errors.push('Tanggal BAK wajib diisi jika No. BAK diisi.');
        }
        if (!fileBak && !hasExistingFileBak) {
            errors.push('File BAK wajib diupload jika No. BAK diisi.');
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
</script>
@endpush
