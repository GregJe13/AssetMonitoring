@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
            {{ isset($asset) ? 'Edit Asset' : 'New Asset' }}
        </h2>
    </div>

    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
        <form action="{{ isset($asset) ? route('assets.update', $asset) : route('assets.store') }}" method="POST">
            @csrf
            @if(isset($asset))
                @method('PUT')
            @endif

            <div class="px-4 py-6 sm:p-8">
                <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    
                    <div class="col-span-full">
                        <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Asset Name</label>
                        <div class="mt-2">
                            <input type="text" name="name" id="name" value="{{ old('name', $asset->name ?? '') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="id_gedung" class="block text-sm font-medium leading-6 text-gray-900">Asset ID (Kode Gedung)</label>
                        <div class="mt-2">
                            <input type="text" name="id_gedung" id="id_gedung" value="{{ old('id_gedung', $asset->id_gedung ?? '') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="e.g. 77-GKP-01">
                            @error('id_gedung') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="area_sqm" class="block text-sm font-medium leading-6 text-gray-900">Area (m²)</label>
                        <div class="mt-2">
                            <input type="number" step="0.01" name="area_sqm" id="area_sqm" value="{{ old('area_sqm', $asset->area_sqm ?? '') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            @error('area_sqm') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="col-span-full">
                        <label for="building_condition" class="block text-sm font-medium leading-6 text-gray-900">Condition</label>
                        <div class="mt-2">
                            <select id="building_condition" name="building_condition" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option value="baik" {{ (old('building_condition', $asset->building_condition ?? '') == 'baik') ? 'selected' : '' }}>Baik</option>
                                <option value="cukup" {{ (old('building_condition', $asset->building_condition ?? '') == 'cukup') ? 'selected' : '' }}>Cukup</option>
                                <option value="rusak_ringan" {{ (old('building_condition', $asset->building_condition ?? '') == 'rusak_ringan') ? 'selected' : '' }}>Rusak Ringan</option>
                                <option value="rusak_berat" {{ (old('building_condition', $asset->building_condition ?? '') == 'rusak_berat') ? 'selected' : '' }}>Rusak Berat</option>
                                <option value="perlu_renovasi" {{ (old('building_condition', $asset->building_condition ?? '') == 'perlu_renovasi') ? 'selected' : '' }}>Perlu Renovasi</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>
            
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
                <a href="{{ route('assets.index') }}" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
