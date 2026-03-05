<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'INTI Asset Monitoring')</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Flatpickr for Date Picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style> 
        [x-cloak] { display: none !important; } 
        body { font-family: 'Instrument Sans', sans-serif; }
        /* Flatpickr styling to match Tailwind */
        .flatpickr-input {
            background-color: white !important;
        }
    </style>
</head>
<body class="h-full" x-data="{ sidebarOpen: false }">
    
    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-gray-900/80 lg:hidden" role="dialog" aria-modal="true" @click="sidebarOpen = false"></div>

    <!-- Mobile Sidebar -->
    <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-0 z-40 flex lg:hidden" role="dialog" aria-modal="true">
        <div class="relative flex w-full max-w-xs flex-1 flex-col bg-white pt-5 pb-4">
            <div class="flex shrink-0 items-center px-4">
                <div class="h-8 w-auto flex items-center gap-2 font-bold text-xl text-indigo-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Asset
                </div>
            </div>
            <div class="mt-5 h-0 flex-1 overflow-y-auto">
                <nav class="space-y-1 px-2">
                    @include('layouts.navigation-links')
                </nav>
            </div>
        </div>
        <div class="w-14 flex-shrink-0"></div>
    </div>

    <!-- Desktop Sidebar -->
    <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-72 lg:flex-col">
        <div class="flex min-h-0 flex-1 flex-col border-r border-gray-200 bg-white shadow-sm z-10">
            <div class="flex flex-1 flex-col overflow-y-auto pt-5 pb-4">
                <div class="flex shrink-0 items-center px-6 mb-6">
                     <div class="h-8 w-auto flex items-center gap-2 font-bold text-2xl text-indigo-600 tracking-tight">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Asset
                    </div>
                </div>
                <nav class="mt-2 flex-1 space-y-1 px-3">
                    @include('layouts.navigation-links')
                </nav>
            </div>
            <div class="flex shrink-0 border-t border-gray-200 p-4">
                <div class="group block w-full shrink-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-indigo-500 font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                                <p class="text-xs font-medium text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="ml-2">
                            @csrf
                            <button type="submit" title="Logout" class="p-2 rounded-md text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-1 flex-col lg:pl-72 min-h-screen transition-all duration-300">
        <div class="sticky top-0 z-10 flex h-16 flex-shrink-0 bg-white/80 backdrop-blur-md border-b border-gray-200 lg:hidden">
            <button type="button" class="px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 lg:hidden" @click="sidebarOpen = true">
                <span class="sr-only">Open sidebar</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
            </button>
        </div>

        <main class="flex-1 py-8">
            <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- SweetAlert2 Logic -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Flash Messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                toast: true,
                position: 'top-end'
            });
        @endif

        // Delete Confirmation Global Handler
        document.addEventListener('DOMContentLoaded', function () {
            // Intercept all forms with method DELETE
            const deleteForms = document.querySelectorAll('form[method="POST"]');
            deleteForms.forEach(form => {
                const methodInput = form.querySelector('input[name="_method"]');
                if (methodInput && methodInput.value === 'DELETE') {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#4f46e5', // Indigo 600
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                }
            });
            
             // Specific Mark as Paid Confirmation
             const markPaidForms = document.querySelectorAll('form input[value="mark_as_paid"]');
             markPaidForms.forEach(input => {
                 const form = input.closest('form');
                 form.addEventListener('submit', function(e) {
                     e.preventDefault();
                     Swal.fire({
                        title: 'Confirm Payment?',
                        text: "Mark this invoice as PAID? Ensure funds are received.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981', // Emerald 500
                        confirmButtonText: 'Yes, Mark Paid'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                 });
             });

            // Initialize Flatpickr on all date inputs
            document.querySelectorAll('input[type="date"]').forEach(function(el) {
                // Get current value if any (in Y-m-d format)
                const currentValue = el.value;
                
                // Change type to text for Flatpickr
                el.type = 'text';
                el.classList.add('flatpickr-input');
                
                // Initialize Flatpickr
                flatpickr(el, {
                    dateFormat: 'Y-m-d',      // Value sent to server: yyyy-mm-dd
                    altInput: true,            // Use alternative input for display
                    altFormat: 'd/m/Y',        // Display format: dd/mm/yyyy  
                    allowInput: true,
                    defaultDate: currentValue ? currentValue : null
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
