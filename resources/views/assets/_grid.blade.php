@forelse($assets as $asset)
    @php
        $rentedArea = $asset->rented_area;
        $availableArea = $asset->available_area;
        $totalArea = $asset->area_sqm;
        $companyUsed = $asset->company_used_area_sqm;
        $totalOccupied = $rentedArea + $companyUsed;
        $usagePercent = $totalArea > 0 ? ($totalOccupied / $totalArea) * 100 : 0;
        $isFullyRented = $availableArea <= 0;
        $activeContracts = $asset->activeContracts()->with('tenant')->get();
        $unusedArea = $asset->unused_area;
    @endphp
    <div class="relative flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm hover:shadow-md transition-shadow" data-asset-card="{{ $asset->id }}">
        <!-- Status Badge -->
        <div class="absolute top-3 right-3 asset-badge">
            @if($isFullyRented)
                <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">Full</span>
            @elseif($totalOccupied > 0)
                <span class="inline-flex items-center rounded-full bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">Partial</span>
            @else
                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Available</span>
            @endif
        </div>

        <div class="p-5 flex-1">
            <h3 class="text-lg font-semibold text-gray-900 truncate pr-16" title="{{ $asset->name }}">
                {{ $asset->name }}
            </h3>
            <p class="text-xs text-gray-500 font-mono mt-1">{{ $asset->id_gedung }}</p>
            
            <!-- Space Utilization -->
            <div class="mt-4">
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-500">Space Usage</span>
                    <span class="asset-usage-pct font-medium text-gray-900">{{ number_format($usagePercent, 0) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="asset-progress-bar h-2.5 rounded-full transition-all duration-300 
                        {{ $isFullyRented ? 'bg-red-500' : ($usagePercent > 0 ? 'bg-indigo-500' : 'bg-emerald-500') }}" 
                         style="width: {{ min($usagePercent, 100) }}%"></div>
                </div>
                <div class="flex justify-between text-xs mt-1.5">
                    <span class="text-gray-500">Rented: <span class="font-medium text-gray-700">{{ number_format($rentedArea, 0) }} m²</span></span>
                    <span class="asset-free-text text-emerald-600 font-medium">{{ number_format($availableArea, 0) }} m² free</span>
                </div>
            </div>

            <dl class="mt-4 space-y-2 text-sm border-t border-gray-100 pt-4" data-asset-row="{{ $asset->id }}">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Total Area</dt>
                    <dd class="font-medium text-gray-900">{{ number_format($totalArea, 0) }} m²</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Dipakai Perusahaan</dt>
                    @unless(Auth::user()->isGuest())
                        <dd class="company-area-value font-medium text-indigo-600 cursor-pointer hover:text-indigo-500 transition-colors"
                            data-asset-id="{{ $asset->id }}"
                            data-asset-name="{{ $asset->name }}"
                            data-company-used="{{ $companyUsed }}"
                            data-max-area="{{ max(0, $totalArea - $rentedArea) }}"
                            data-total-area="{{ $totalArea }}"
                            data-rented-area="{{ $rentedArea }}"
                            data-url="{{ route('assets.updateCompanyArea', $asset) }}"
                            title="Klik untuk mengubah">
                            {{ number_format($companyUsed, 0) }} m²
                        </dd>
                    @else
                        <dd class="font-medium text-gray-900">{{ number_format($companyUsed, 0) }} m²</dd>
                    @endunless
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Belum Dipakai</dt>
                    <dd class="company-unused font-medium text-gray-900">{{ number_format($unusedArea, 0) }} m²</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Condition</dt>
                    <dd class="font-medium {{ $asset->building_condition === 'baik' ? 'text-green-600' : 'text-red-600' }}">
                        {{ ucfirst($asset->building_condition) }}
                    </dd>
                </div>
            </dl>

            <!-- Active Tenants -->
            @if($activeContracts->isNotEmpty())
            <div class="mt-4 pt-3 border-t border-gray-100">
                <dt class="text-xs text-gray-500 mb-2">Current Tenants ({{ $activeContracts->count() }})</dt>
                <div class="space-y-1">
                    @foreach($activeContracts->take(3) as $contract)
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-indigo-600 truncate font-medium" title="{{ $contract->tenant->name }}">
                            {{ Str::limit($contract->tenant->name, 20) }}
                        </span>
                        <span class="text-gray-500">{{ number_format($contract->pivot->rented_area_sqm, 0) }} m²</span>
                    </div>
                    @endforeach
                    @if($activeContracts->count() > 3)
                    <div class="text-xs text-gray-400">+{{ $activeContracts->count() - 3 }} more...</div>
                    @endif
                </div>
            </div>
            @endif
        </div>
        
        <div class="bg-gray-50 px-5 py-3 flex items-center justify-between border-t border-gray-100">
            @unless(Auth::user()->isGuest())
            <a href="{{ route('assets.edit', $asset) }}" class="text-xs font-medium text-gray-600 hover:text-indigo-600">Edit</a>
            @else
            <span></span>
            @endunless
            <a href="{{ route('assets.show', $asset) }}" class="text-xs font-medium text-gray-600 hover:text-indigo-600">History</a>
        </div>
    </div>
@empty
    <div class="col-span-full text-center py-10 text-gray-500 bg-white rounded-lg border border-dashed border-gray-300">
        No assets found.
    </div>
@endforelse
