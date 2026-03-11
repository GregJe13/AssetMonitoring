@extends('layouts.app')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-2xl font-semibold leading-6 text-gray-900">Tenants</h1>
        <p class="mt-2 text-sm text-gray-700">A list of all tenants including their name, contact details, and active contract status.</p>
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <a href="{{ route('tenants.create') }}" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            Add Tenant
        </a>
    </div>
</div>

<div class="mt-8 flow-root">
    <!-- Search -->
    <div class="mb-4 max-w-sm">
        <form id="tenantSearchForm" action="{{ route('tenants.index') }}" method="GET">
            <div class="relative rounded-md shadow-sm">
                <input type="text" name="search" id="tenantSearchInput" value="{{ request('search') }}"
                    class="block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    placeholder="Search name, pic, or email..." autocomplete="off">
                <div id="tenantSearchLoading" class="absolute inset-y-0 right-0 flex items-center pr-3 hidden">
                    <svg class="animate-spin h-4 w-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <div id="tenantSearchIcon" class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </form>
        <div id="tenantSearchResultCount" class="mt-2 text-xs text-gray-500 hidden"></div>
    </div>

    <!-- Table -->
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Contact Person</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">ID Tenant</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tenantTableBody" class="divide-y divide-gray-200 bg-white">
                        @include('tenants._row', ['tenants' => $tenants])
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div id="tenantPagination" class="mt-4">
        {{ $tenants->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput   = document.getElementById('tenantSearchInput');
    const searchLoading = document.getElementById('tenantSearchLoading');
    const searchIcon    = document.getElementById('tenantSearchIcon');
    const resultCount   = document.getElementById('tenantSearchResultCount');
    const tableBody     = document.getElementById('tenantTableBody');
    const pagination    = document.getElementById('tenantPagination');

    let debounceTimer;
    const DEBOUNCE_DELAY = 300;

    function performSearch(query) {
        searchLoading.classList.remove('hidden');
        searchIcon.classList.add('hidden');

        fetch(`{{ route('tenants.search') }}?search=${encodeURIComponent(query)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            tableBody.innerHTML = data.html;

            if (query.length > 0) {
                resultCount.textContent = `Found ${data.count} tenant(s)`;
                resultCount.classList.remove('hidden');
                pagination.classList.add('hidden');
            } else {
                resultCount.classList.add('hidden');
                pagination.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Search error:', error);
        })
        .finally(() => {
            searchLoading.classList.add('hidden');
            searchIcon.classList.remove('hidden');
        });
    }

    searchInput.addEventListener('input', function(e) {
        const query = e.target.value;
        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(() => {
            performSearch(query);
        }, DEBOUNCE_DELAY);
    });

    // Prevent normal form submit (use AJAX instead)
    document.getElementById('tenantSearchForm').addEventListener('submit', function(e) {
        e.preventDefault();
        clearTimeout(debounceTimer);
        performSearch(searchInput.value);
    });
});
</script>
@endpush
