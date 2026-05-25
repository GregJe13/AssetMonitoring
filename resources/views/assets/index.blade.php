@extends('layouts.app')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-2xl font-semibold leading-6 text-gray-900">Assets</h1>
        <p class="mt-2 text-sm text-gray-700">Manage building assets and view their current space utilization.</p>
    </div>
    @unless(Auth::user()->isGuest())
    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <a href="{{ route('assets.create') }}" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
            Add Asset
        </a>
    </div>
    @endunless
</div>

<div class="mt-8">
    <!-- Search -->
    <div class="mb-6 max-w-sm">
        <form action="{{ route('assets.index') }}" method="GET" id="searchForm">
             <div class="relative rounded-md shadow-sm">
                <input type="text" name="search" id="searchInput" value="{{ request('search') }}" 
                    class="block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" 
                    placeholder="Search asset name or ID..." autocomplete="off">
                <!-- Loading indicator -->
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

    <!-- Assets Grid -->
    <div id="assetsGrid" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @include('assets._grid', ['assets' => $assets])
    </div>

    <div id="paginationContainer" class="mt-6">
        {{ $assets->links() }}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    const assetsGrid = document.getElementById('assetsGrid');
    const searchLoading = document.getElementById('searchLoading');
    const searchResultCount = document.getElementById('searchResultCount');
    const paginationContainer = document.getElementById('paginationContainer');
    
    let debounceTimer;
    const DEBOUNCE_DELAY = 300; // milliseconds

    // Debounced search function
    function performSearch(query) {
        // Show loading
        searchLoading.classList.remove('hidden');
        
        fetch(`{{ route('assets.search') }}?search=${encodeURIComponent(query)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update grid with new content
            assetsGrid.innerHTML = data.html;
            
            // Show result count
            searchResultCount.textContent = `Found ${data.count} asset(s)`;
            searchResultCount.classList.remove('hidden');
            
            // Hide pagination for AJAX results (since we're showing all matching)
            paginationContainer.classList.add('hidden');
        })
        .catch(error => {
            console.error('Search error:', error);
        })
        .finally(() => {
            // Hide loading
            searchLoading.classList.add('hidden');
        });
    }

    // Listen for input changes with debounce
    searchInput.addEventListener('input', function(e) {
        const query = e.target.value;
        
        // Clear previous timer
        clearTimeout(debounceTimer);
        
        // Set new timer
        debounceTimer = setTimeout(() => {
            if (query.length >= 1 || query.length === 0) {
                performSearch(query);
            }
        }, DEBOUNCE_DELAY);
    });

    // Allow Enter to submit form normally (page reload with URL update)
    searchForm.addEventListener('submit', function(e) {
        // Clear debounce timer to prevent AJAX call
        clearTimeout(debounceTimer);
        // Form will submit normally and reload page
    });
});
</script>
@endsection

