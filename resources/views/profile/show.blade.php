@extends('layouts.app')

@section('title', 'Profile Saya - INTI Asset Monitoring')

@section('content')
    @php
        $initials = collect(explode(' ', trim($user->name)))
            ->filter()
            ->take(2)
            ->map(fn($part) => strtoupper(substr($part, 0, 1)))
            ->implode('');
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-medium text-indigo-600">User Profile</p>
                <h1 class="mt-1 text-3xl font-bold tracking-tight text-gray-900">Profile Saya</h1>
                <p class="mt-2 max-w-2xl text-sm text-gray-500">
                    Ringkasan akun, hak akses, dan snapshot data monitoring yang paling relevan untuk aktivitas harian.
                </p>
            </div>
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 transition hover:bg-gray-50">
                Kembali ke Dashboard
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <div class="space-y-6 xl:col-span-2">


                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-semibold text-gray-900">Informasi Akun</h3>
                                <p class="mt-1 text-sm text-gray-500">Data dasar akun yang sedang login.</p>
                            </div>
                            <span
                                class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset {{ $roleMeta['badge'] }}">
                                {{ $roleMeta['label'] }}
                            </span>
                        </div>

                        <dl class="mt-6 space-y-4">
                            <div class="rounded-2xl bg-gray-50 px-4 py-3">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Nama Lengkap</dt>
                                <dd class="mt-1 text-sm font-medium text-gray-900">{{ $user->name }}</dd>
                            </div>
                            <div class="rounded-2xl bg-gray-50 px-4 py-3">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm font-medium text-gray-900">{{ $user->email }}</dd>
                            </div>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div class="rounded-2xl bg-gray-50 px-4 py-3">
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Role</dt>
                                    <dd class="mt-1 text-sm font-medium capitalize text-gray-900">{{ $user->role }}</dd>
                                </div>
                                <div class="rounded-2xl bg-gray-50 px-4 py-3">
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Member Since
                                    </dt>
                                    <dd class="mt-1 text-sm font-medium text-gray-900">
                                        {{ $user->created_at?->diffForHumans() ?? '-' }}
                                    </dd>
                                </div>
                            </div>
                        </dl>
                    </div>

                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                        <h3 class="text-base font-semibold text-gray-900">Hak Akses</h3>
                        <p class="mt-1 text-sm text-gray-500">Modul utama yang tersedia untuk role Anda saat ini.</p>

                        <div class="mt-6 flex flex-wrap gap-2">
                            @foreach($accessModules as $module)
                                <span
                                    class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-700 ring-1 ring-inset ring-indigo-600/20">
                                    {{ $module }}
                                </span>
                            @endforeach
                        </div>

                        <div class="mt-6 rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-4">
                            <p class="text-sm font-semibold text-gray-900">Catatan akses</p>
                            <p class="mt-1 text-sm leading-6 text-gray-600">
                                Jika ada menu atau izin yang belum sesuai kebutuhan operasional, halaman ini bisa jadi titik
                                referensi untuk pengembangan berikutnya.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">Quick Actions</h3>
                            <p class="mt-1 text-sm text-gray-500">Shortcut ke area yang paling sering dipakai dari profile
                                Anda.</p>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                        @foreach($quickLinks as $link)
                            <a href="{{ $link['href'] }}"
                                class="group rounded-2xl border border-gray-200 p-4 transition hover:-translate-y-0.5 hover:border-indigo-300 hover:bg-indigo-50/60">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-semibold text-gray-900">{{ $link['label'] }}</h4>
                                    <svg class="h-4 w-4 text-gray-400 transition group-hover:text-indigo-600" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                </div>
                                <p class="mt-2 text-sm leading-6 text-gray-500">{{ $link['description'] }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                    <h3 class="text-base font-semibold text-gray-900">Snapshot Monitoring</h3>
                    <p class="mt-1 text-sm text-gray-500">Ringkasan singkat workspace yang sedang Anda kelola.</p>

                    <div class="mt-6 space-y-4">
                        @foreach($workspaceStats as $stat)
                            <div class="rounded-2xl bg-gray-50 px-4 py-4">
                                <p class="text-sm font-medium text-gray-500">{{ $stat['label'] }}</p>
                                <p class="mt-1 text-2xl font-bold tracking-tight text-gray-900">
                                    {{ number_format($stat['value']) }}
                                </p>
                                <p class="mt-1 text-xs text-gray-500">{{ $stat['caption'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                    <h3 class="text-base font-semibold text-gray-900">Perlu Perhatian</h3>
                    <p class="mt-1 text-sm text-gray-500">Item operasional yang layak dicek lebih dulu hari ini.</p>

                    <div class="mt-6 space-y-3">
                        @foreach($attentionCards as $item)
                            <a href="{{ $item['href'] }}"
                                class="block rounded-2xl px-4 py-4 ring-1 ring-inset transition hover:scale-[1.01] {{ $item['classes'] }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold">{{ $item['label'] }}</p>
                                        <p class="mt-1 text-xs opacity-80">{{ $item['caption'] }}</p>
                                    </div>
                                    <span class="text-2xl font-bold">{{ number_format($item['value']) }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection