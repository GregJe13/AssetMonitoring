@extends('layouts.app')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-2xl font-semibold leading-6 text-gray-900">Contracts</h1>
        <p class="mt-2 text-sm text-gray-700">Manage active rental agreements, track expirations, and monitor payment schedules.</p>
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <a href="{{ route('contracts.create') }}" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
            New Contract
        </a>
    </div>
</div>

<div class="mt-8 flow-root" x-data="{ activeTab: '{{ $tab }}' }">
    <!-- Tabs -->
    <div class="border-b border-gray-200 mb-4">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button @click="activeTab = 'active'" 
                :class="activeTab === 'active' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
                class="whitespace-nowrap border-b-2 py-3 px-1 text-sm font-medium transition-colors">
                Active
                <span :class="activeTab === 'active' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-900'"
                    class="ml-2 rounded-full py-0.5 px-2.5 text-xs font-medium inline-block">
                    {{ $activeContracts->total() }}
                </span>
            </button>
            <button @click="activeTab = 'log'" 
                :class="activeTab === 'log' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
                class="whitespace-nowrap border-b-2 py-3 px-1 text-sm font-medium transition-colors">
                Log
                <span :class="activeTab === 'log' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-900'"
                    class="ml-2 rounded-full py-0.5 px-2.5 text-xs font-medium inline-block">
                    {{ $expiredContracts->total() }}
                </span>
            </button>
        </nav>
    </div>

    <!-- Search -->
    <div class="mb-4 max-w-sm">
        <form id="searchForm" action="{{ route('contracts.index') }}" method="GET">
            <input type="hidden" name="tab" :value="activeTab">
            <div class="relative rounded-md shadow-sm">
                <input type="text" name="search" id="searchInput" value="{{ request('search') }}" 
                    class="block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" 
                    placeholder="By tenant, BAK or PKS..." autocomplete="off">
                <div id="searchLoading" class="absolute inset-y-0 right-0 flex items-center pr-3 hidden">
                    <svg class="animate-spin h-4 w-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </form>
        <div id="searchResultCount" class="mt-2 text-xs text-gray-500 hidden"></div>
    </div>

    <!-- Active Contracts Tab -->
    <div x-show="activeTab === 'active'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Period</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tenant & Reference</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Assets</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Value</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody id="activeContractsBody" class="divide-y divide-gray-200 bg-white">
                            @include('contracts._grid', ['contracts' => $activeContracts])
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="activePagination" class="mt-4">
            {{ $activeContracts->appends(['tab' => 'active', 'search' => request('search')])->links() }}
        </div>
    </div>

    <!-- Log (Expired) Contracts Tab -->
    <div x-show="activeTab === 'log'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Period</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tenant & Reference</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Assets</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Value</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody id="logContractsBody" class="divide-y divide-gray-200 bg-white">
                            @include('contracts._grid', ['contracts' => $expiredContracts])
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="logPagination" class="mt-4">
            {{ $expiredContracts->appends(['tab' => 'log', 'search' => request('search')])->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    const searchLoading = document.getElementById('searchLoading');
    const searchResultCount = document.getElementById('searchResultCount');
    
    let debounceTimer;
    const DEBOUNCE_DELAY = 300;

    function performSearch(query) {
        searchLoading.classList.remove('hidden');
        
        // Determine which tab is active
        const activeTab = document.querySelector('[x-data]').__x.$data.activeTab;
        const targetBody = activeTab === 'active' 
            ? document.getElementById('activeContractsBody') 
            : document.getElementById('logContractsBody');
        const paginationEl = activeTab === 'active'
            ? document.getElementById('activePagination')
            : document.getElementById('logPagination');
        
        fetch(`{{ route('contracts.search') }}?search=${encodeURIComponent(query)}&tab=${activeTab}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            targetBody.innerHTML = data.html;
            
            searchResultCount.textContent = `Found ${data.count} contract(s)`;
            searchResultCount.classList.remove('hidden');
            
            paginationEl.classList.add('hidden');
        })
        .catch(error => {
            console.error('Search error:', error);
        })
        .finally(() => {
            searchLoading.classList.add('hidden');
        });
    }

    searchInput.addEventListener('input', function(e) {
        const query = e.target.value;
        clearTimeout(debounceTimer);
        
        debounceTimer = setTimeout(() => {
            if (query.length >= 1 || query.length === 0) {
                performSearch(query);
            }
        }, DEBOUNCE_DELAY);
    });

    searchForm.addEventListener('submit', function(e) {
        clearTimeout(debounceTimer);
    });
});
</script>
@endpush
@endsection
