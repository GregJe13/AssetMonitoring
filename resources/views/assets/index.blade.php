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

{{-- Modal: Update Dipakai Perusahaan --}}
@unless(Auth::user()->isGuest())
<div id="companyAreaModal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    {{-- Backdrop --}}
    <div id="companyAreaBackdrop" class="fixed inset-0 bg-gray-500/75 transition-opacity"></div>

    {{-- Modal Panel --}}
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-md transform rounded-xl bg-white shadow-2xl transition-all">
                {{-- Header --}}
                <div class="border-b border-gray-100 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Update Luas Dipakai Perusahaan</h3>
                        <button type="button" id="companyAreaClose" class="rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <p id="companyAreaAssetName" class="mt-1 text-sm text-gray-500"></p>
                </div>

                {{-- Body --}}
                <div class="px-6 py-5">
                    <label for="companyAreaInput" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Luas yang Dipakai (m²)
                    </label>
                    <div class="relative rounded-md shadow-sm">
                        <input type="number" id="companyAreaInput" step="0.01" min="0"
                               class="block w-full rounded-md border-0 py-2.5 pl-4 pr-12 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                               placeholder="Masukkan luas...">
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                            <span class="text-gray-500 sm:text-sm">m²</span>
                        </div>
                    </div>
                    <p id="companyAreaMaxInfo" class="mt-2 text-xs text-gray-400"></p>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 border-t border-gray-100 px-6 py-4 bg-gray-50 rounded-b-xl">
                    <button type="button" id="companyAreaCancel" class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="button" id="companyAreaSave" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endunless

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

    // ── Modal: Update Dipakai Perusahaan ──
    const modal      = document.getElementById('companyAreaModal');
    if (!modal) return; // guest users – modal not rendered

    const backdrop   = document.getElementById('companyAreaBackdrop');
    const closeBtn   = document.getElementById('companyAreaClose');
    const cancelBtn  = document.getElementById('companyAreaCancel');
    const saveBtn    = document.getElementById('companyAreaSave');
    const input      = document.getElementById('companyAreaInput');
    const nameEl     = document.getElementById('companyAreaAssetName');
    const maxInfoEl  = document.getElementById('companyAreaMaxInfo');

    let activeValueEl = null; // the <dd> that was clicked
    let patchUrl      = '';

    function openModal(valueEl) {
        activeValueEl = valueEl;
        patchUrl = valueEl.dataset.url;

        const maxArea   = Number(valueEl.dataset.maxArea);
        const totalArea = Number(valueEl.dataset.totalArea);
        const rentedArea = Number(valueEl.dataset.rentedArea);

        nameEl.textContent  = valueEl.dataset.assetName;
        input.value         = valueEl.dataset.companyUsed;
        input.max           = maxArea;

        let maxText = `Maksimal: ${maxArea.toLocaleString('id-ID')} m²`;
        if (rentedArea > 0) {
            maxText += ` (Total: ${totalArea.toLocaleString('id-ID')} m² − Disewa tenant: ${rentedArea.toLocaleString('id-ID')} m²)`;
        }
        maxInfoEl.textContent = maxText;

        modal.classList.remove('hidden');
        // small delay so the browser paints first, then focus
        setTimeout(() => input.focus(), 50);
    }

    function closeModal() {
        modal.classList.add('hidden');
        activeValueEl = null;
        patchUrl = '';
    }

    // Open: click on indigo value
    document.addEventListener('click', function (e) {
        const el = e.target.closest('.company-area-value');
        if (el) openModal(el);
    });

    // Close triggers
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
    });

    // Save via AJAX
    saveBtn.addEventListener('click', function () {
        if (!activeValueEl) return;
        saveBtn.disabled = true;

        const card = activeValueEl.closest('[data-asset-card]');

        window.axios.patch(patchUrl,
            { company_used_area_sqm: input.value },
            { headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }
        ).then(function (response) {
            const data = response.data;
            const newVal = Number(data.company_used_area_sqm);

            // 1. Update "Dipakai Perusahaan" value + data attributes
            activeValueEl.textContent = newVal.toLocaleString('id-ID') + ' m²';
            activeValueEl.dataset.companyUsed = data.company_used_area_sqm;
            activeValueEl.dataset.maxArea = data.max_company_area;

            if (card) {
                // 2. Update "Belum Dipakai"
                const unused = card.querySelector('.company-unused');
                if (unused) unused.textContent = data.unused_area + ' m²';

                // 3. Update Space Usage percentage
                const usagePct = card.querySelector('.asset-usage-pct');
                if (usagePct) usagePct.textContent = data.usage_percent + '%';

                // 4. Update progress bar width + color
                const bar = card.querySelector('.asset-progress-bar');
                if (bar) {
                    bar.style.width = Math.min(data.usage_percent, 100) + '%';
                    bar.classList.remove('bg-red-500', 'bg-indigo-500', 'bg-emerald-500');
                    if (data.is_full) {
                        bar.classList.add('bg-red-500');
                    } else if (data.usage_percent > 0) {
                        bar.classList.add('bg-indigo-500');
                    } else {
                        bar.classList.add('bg-emerald-500');
                    }
                }

                // 5. Update "free" text
                const freeText = card.querySelector('.asset-free-text');
                if (freeText) freeText.textContent = data.available_area + ' m² free';

                // 6. Update status badge
                const badge = card.querySelector('.asset-badge');
                if (badge) {
                    if (data.is_full) {
                        badge.innerHTML = '<span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">Full</span>';
                    } else if (data.total_occupied > 0) {
                        badge.innerHTML = '<span class="inline-flex items-center rounded-full bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">Partial</span>';
                    } else {
                        badge.innerHTML = '<span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Available</span>';
                    }
                }
            }

            closeModal();

            Swal.fire({
                toast: true, position: 'top-end', icon: 'success',
                title: 'Luas perusahaan tersimpan', showConfirmButton: false, timer: 1500
            });
        }).catch(function (error) {
            const res = error.response;
            let msg = 'Gagal menyimpan';
            if (res && res.data) {
                if (res.data.errors && res.data.errors.company_used_area_sqm) {
                    msg = res.data.errors.company_used_area_sqm[0];
                } else if (res.data.message) {
                    msg = res.data.message;
                }
            }
            Swal.fire({ icon: 'error', title: 'Error', text: msg });
        }).finally(function () {
            saveBtn.disabled = false;
        });
    });

    // Allow Enter key in input to trigger save
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            saveBtn.click();
        }
    });
});
</script>
@endsection

