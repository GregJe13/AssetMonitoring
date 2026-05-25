@extends('layouts.app')

@section('content')
<div class="md:flex md:items-center md:justify-between mb-8">
    <div class="min-w-0 flex-1">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
            {{ $asset->name }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">{{ $asset->id_gedung }}</p>
    </div>
    @unless(Auth::user()->isGuest())
    <div class="mt-4 flex md:ml-4 md:mt-0 gap-3">
        <form action="{{ route('assets.destroy', $asset) }}" method="POST">
            @csrf @method('DELETE')
            <button type="submit" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Delete</button>
        </form>
        <a href="{{ route('assets.edit', $asset) }}" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Edit</a>
    </div>
    @endunless
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Asset Details -->
    <div>
        <div class="overflow-hidden bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Asset Details</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                 <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Asset ID (Kode Gedung)</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $asset->id_gedung }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Area</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $asset->area_sqm }} m²</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Condition</dt>
                        <dd class="mt-1 text-sm font-medium {{ $asset->building_condition === 'baik' ? 'text-green-600' : 'text-red-600' }}">
                            {{ ucfirst($asset->building_condition) }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Current Status</dt>
                         <dd class="mt-1">
                            @if($asset->contracts()->where('status', 'active')->exists())
                                <span class="inline-flex items-center rounded-full bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">Rented</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Available</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Rental History -->
    <div class="lg:col-span-2">
        <div class="overflow-hidden bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Rental History</h3>
                <p class="mt-1 text-sm text-gray-500">Previous and current contracts involving this asset.</p>
            </div>
            <ul role="list" class="divide-y divide-gray-100">
                @forelse($history as $contract)
                <li class="p-4 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                @unless(Auth::user()->isGuest())
                                <a href="{{ route('tenants.show', $contract->tenant) }}" class="hover:underline">{{ $contract->tenant->name }}</a>
                                @else
                                {{ $contract->tenant->name }}
                                @endunless
                            </p>
                            <p class="text-xs text-gray-500">{{ $contract->start_date->format('M Y') }} - {{ $contract->end_date->format('M Y') }}</p>
                        </div>
                        <div class="flex items-center gap-4">
                             <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset 
                                {{ $contract->status === 'active' ? 'text-green-700 bg-green-50 ring-green-600/20' : 'text-gray-600 bg-gray-100 ring-gray-500/10' }}">
                                {{ ucfirst($contract->status) }}
                            </span>
                            @unless(Auth::user()->isGuest())
                            <a href="{{ route('contracts.show', $contract) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">View Contract</a>
                            @endunless
                        </div>
                    </div>
                </li>
                @empty
                <li class="p-6 text-center text-sm text-gray-500">No rental history found.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
