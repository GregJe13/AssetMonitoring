@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                {{ isset($tenant) ? 'Edit Tenant' : 'New Tenant' }}
            </h2>
        </div>
    </div>

    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
        <form action="{{ isset($tenant) ? route('tenants.update', $tenant) : route('tenants.store') }}" method="POST">
            @csrf
            @if(isset($tenant))
                @method('PUT')
            @endif

            <div class="px-4 py-6 sm:p-8">
                <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    
                    <!-- Company Info -->
                    <div class="col-span-full">
                        <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Company / Tenant Name</label>
                        <div class="mt-2">
                            <input type="text" name="name" id="name" value="{{ old('name', $tenant->name ?? '') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="id_tenant" class="block text-sm font-medium leading-6 text-gray-900">Tenant ID (Optional)</label>
                        <div class="mt-2">
                            <input type="number" name="id_tenant" id="id_tenant" value="{{ old('id_tenant', $tenant->id_tenant ?? '') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="npwp" class="block text-sm font-medium leading-6 text-gray-900">NPWP</label>
                        <div class="mt-2">
                            <input type="text" name="npwp" id="npwp" value="{{ old('npwp', $tenant->npwp ?? '') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="col-span-full border-t border-gray-100 pt-6 mt-2">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Contact Person Details</h3>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="pic" class="block text-sm font-medium leading-6 text-gray-900">PIC Name</label>
                        <div class="mt-2">
                            <input type="text" name="pic" id="pic" value="{{ old('pic', $tenant->pic ?? '') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="pic_phone" class="block text-sm font-medium leading-6 text-gray-900">PIC Phone</label>
                        <div class="mt-2">
                            <input type="text" name="pic_phone" id="pic_phone" value="{{ old('pic_phone', $tenant->pic_phone ?? '') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email Address</label>
                        <div class="mt-2">
                            <input type="email" name="email" id="email" value="{{ old('email', $tenant->email ?? '') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                     <div class="sm:col-span-3">
                        <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Office Phone</label>
                        <div class="mt-2">
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $tenant->phone ?? '') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                </div>
            </div>
            
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
                <a href="{{ route('tenants.index') }}" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
