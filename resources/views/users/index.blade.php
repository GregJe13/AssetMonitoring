@extends('layouts.app')

@section('title', 'User Management - INTI Asset Monitoring')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-indigo-600">Administration</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">User Management</h1>
            <p class="mt-1 text-sm text-gray-500">
                @if($currentUser->isAdmin())
                    Kelola semua user dan atur role mereka.
                @else
                    Kelola role Guest dan Worker untuk user terdaftar.
                @endif
            </p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 p-5">
        <form action="{{ route('users.index') }}" method="GET" class="flex flex-col gap-4 sm:flex-row sm:items-end">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari User</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    class="block w-full rounded-md border-0 py-2 pl-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                    placeholder="Cari nama atau email...">
            </div>
            <div class="sm:w-40">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Filter Role</label>
                <select name="role" id="role"
                    class="block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                    <option value="">Semua Role</option>
                    @foreach($manageableRoles as $role)
                        <option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                    <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    Cari
                </button>
                @if(request('search') || request('role'))
                <a href="{{ route('users.index') }}"
                    class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <div class="w-full">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 rounded-tl-xl whitespace-nowrap">User</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 whitespace-nowrap">Email</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 whitespace-nowrap">Role</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 whitespace-nowrap">Bergabung</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-6 rounded-tr-xl">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="whitespace-nowrap py-4 pl-6 pr-3">
                            <div class="flex items-center gap-3">
                                <div class="inline-flex h-9 w-9 items-center justify-center rounded-full text-sm font-bold
                                    {{ match($user->role) {
                                        'admin' => 'bg-indigo-100 text-indigo-600',
                                        'manager' => 'bg-emerald-100 text-emerald-600',
                                        'worker' => 'bg-amber-100 text-amber-600',
                                        default => 'bg-gray-100 text-gray-500',
                                    } }}">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $user->email }}</td>
                        <td class="whitespace-nowrap px-3 py-4">
                            @php
                                $roleBadge = match($user->role) {
                                    'admin' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
                                    'manager' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                    'worker' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                    default => 'bg-gray-100 text-gray-600 ring-gray-500/20',
                                };
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $roleBadge }}">
                                {{ $user->role_label }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                            {{ $user->created_at?->format('d M Y') ?? '-' }}
                        </td>
                        <td class="whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm">
                            @if(!$user->isAdmin() || $currentUser->isAdmin())
                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                <button @click="open = !open" type="button"
                                    class="inline-flex items-center rounded-md bg-white px-3 py-1.5 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                                    Ubah Role
                                    <svg class="ml-1.5 h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-transition
                                    class="absolute right-0 z-10 mt-2 w-44 origin-top-right rounded-lg bg-white shadow-lg ring-1 ring-black/5 focus:outline-none">
                                    <div class="py-1">
                                        @foreach($manageableRoles as $role)
                                            @if($role !== $user->role)
                                            <form action="{{ route('users.updateRole', $user) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="role" value="{{ $role }}">
                                                <button type="submit"
                                                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 transition-colors flex items-center gap-2
                                                        {{ match($role) {
                                                            'manager' => 'text-emerald-700',
                                                            'worker' => 'text-amber-700',
                                                            default => 'text-gray-600',
                                                        } }}">
                                                    @if($user->role === 'guest' && $role === 'worker')
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                        </svg>
                                                        Jadikan Worker
                                                    @elseif($user->role === 'worker' && $role === 'guest')
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                                        </svg>
                                                        Jadikan Guest
                                                    @elseif($role === 'manager')
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                                        </svg>
                                                        Jadikan Manager
                                                    @else
                                                        Set {{ ucfirst($role) }}
                                                    @endif
                                                </button>
                                            </form>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @else
                                <span class="text-xs text-gray-400 italic">Protected</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-10 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                </svg>
                                <p class="text-sm text-gray-500">Tidak ada user ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="border-t border-gray-200 px-6 py-4">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
