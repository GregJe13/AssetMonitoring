@extends('layouts.app')

@section('title', 'Expiring Contracts - INTI Asset Monitoring')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Dashboard
                    </a>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Contracts Expiring Soon</h1>
                <p class="mt-1 text-sm text-gray-500">All contracts within 60 days of expiry or already expired with pending workflow.</p>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="p-4 px-6">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-yellow-50 text-yellow-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Expiring Soon</p>
                            <p class="text-2xl font-bold font-mono text-gray-900">{{ $expiringSoonCount }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="p-4 px-6">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-red-50 text-red-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Already Expired</p>
                            <p class="text-2xl font-bold font-mono text-gray-900">{{ $alreadyExpiredCount }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @if($expiringAmendments->count() > 0)
                <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                    <div class="p-4 px-6">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-indigo-50 text-indigo-600">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Amendments Expiring</p>
                                <p class="text-2xl font-bold font-mono text-gray-900">{{ $expiringAmendments->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Contracts List -->
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
            <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Expiring Contracts</h3>
                <p class="mt-1 text-xs text-gray-500">Track renewal/termination progress with notes</p>
            </div>
            <ul role="list" class="divide-y divide-gray-100">
                @forelse($expiringContracts as $contract)
                    <li class="py-4 px-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between gap-x-4">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start gap-x-3">
                                    <p class="text-sm font-semibold leading-6 text-gray-900">{{ $contract->tenant?->name ?? 'Unknown' }}
                                    </p>
                                    <p
                                        class="rounded-md whitespace-nowrap px-1.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $contract->is_expired ? 'text-red-700 bg-red-50 ring-red-600/10' : 'text-yellow-800 bg-yellow-50 ring-yellow-600/20' }}">
                                        {{ $contract->is_expired ? 'Expired' : 'Expiring' }}
                                    </p>
                                </div>
                                <div class="mt-1 flex items-center gap-x-3 text-xs leading-5 text-gray-500">
                                    <p class="whitespace-nowrap">{{ $contract->no_pks ?? $contract->no_bak ?? '-' }}</p>
                                    <span class="text-gray-300">•</span>
                                    <p class="whitespace-nowrap">{{ $contract->end_date->format('d M Y') }}</p>
                                    @if($contract->is_expired)
                                        <span class="text-red-600 font-medium">({{ $contract->days_expired }} hari lalu)</span>
                                    @else
                                        <span class="text-gray-400">({{ $contract->remaining_days }} days left)</span>
                                    @endif
                                </div>
                                <!-- Notes (editable for worker+, read-only for guest) -->
                                @if(Auth::user()->isGuest())
                                    @if($contract->renewal_notes)
                                        <div class="mt-3">
                                            <div
                                                class="text-xs text-gray-600 bg-gray-50 rounded-md px-2 py-1.5 border border-gray-200">
                                                <span class="font-medium text-gray-500">Notes:</span>
                                                {{ $contract->renewal_notes }}
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="mt-3" x-data="{ 
                                                                    editing: false, 
                                                                    notes: @js($contract->renewal_notes ?? ''),
                                                                    saving: false,
                                                                    saved: false,
                                                                    saveNotes() {
                                                                        this.saving = true;
                                                                        fetch('{{ route('contracts.updateRenewalNotes', $contract) }}', {
                                                                            method: 'PATCH',
                                                                            headers: {
                                                                                'Content-Type': 'application/json',
                                                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                                                'Accept': 'application/json'
                                                                            },
                                                                            body: JSON.stringify({ renewal_notes: this.notes })
                                                                        })
                                                                        .then(res => res.json())
                                                                        .then(data => {
                                                                            this.saving = false;
                                                                            this.editing = false;
                                                                            this.saved = true;
                                                                            setTimeout(() => this.saved = false, 2000);
                                                                        })
                                                                        .catch(err => {
                                                                            this.saving = false;
                                                                            alert('Failed to save notes');
                                                                        });
                                                                    }
                                                                }">
                                        <template x-if="!editing">
                                            <div @click="editing = true" class="cursor-pointer group">
                                                <template x-if="notes && notes.trim()">
                                                    <div
                                                        class="text-xs text-gray-600 bg-gray-50 rounded-md px-2 py-1.5 border border-gray-200 group-hover:border-indigo-300 group-hover:bg-indigo-50 transition-colors">
                                                        <span class="font-medium text-gray-500">Notes:</span>
                                                        <span x-text="notes"></span>
                                                        <span
                                                            class="text-gray-400 ml-1 opacity-0 group-hover:opacity-100">(click
                                                            to edit)</span>
                                                    </div>
                                                </template>
                                                <template x-if="!notes || !notes.trim()">
                                                    <div
                                                        class="text-xs text-gray-400 italic hover:text-indigo-600 flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                            </path>
                                                        </svg>
                                                        Click to add progress notes...
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="editing">
                                            <div class="space-y-2">
                                                <textarea x-model="notes" @keydown.escape="editing = false"
                                                    class="block w-full rounded-md border-0 py-1.5 px-2 text-xs text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600"
                                                    rows="2"
                                                    placeholder="e.g., Menunggu konfirmasi perpanjangan dari tenant, atau Proses terminasi sedang berjalan..."
                                                    x-ref="notesInput"
                                                    x-init="$nextTick(() => $refs.notesInput.focus())"></textarea>
                                                <div class="flex gap-2">
                                                    <button @click="saveNotes()" :disabled="saving"
                                                        class="inline-flex items-center rounded-md bg-indigo-600 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 disabled:opacity-50">
                                                        <span x-show="!saving">Save</span>
                                                        <span x-show="saving">Saving...</span>
                                                    </button>
                                                    <button @click="editing = false"
                                                        class="text-xs text-gray-500 hover:text-gray-700">Cancel</button>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="saved">
                                            <div class="text-xs text-green-600 mt-1">✓ Saved!</div>
                                        </template>
                                    </div>
                                @endif
                            </div>
                            @unless(Auth::user()->isGuest())
                                <div class="flex-shrink-0 flex gap-2">
                                    @if($contract->workflow)
                                                    @php
                                                        $finalSteps = ['closed', 'renewed'];
                                                        $isAtFinalStep = in_array($contract->workflow->current_step, $finalSteps);
                                                    @endphp
                                                    <a href="{{ route('workflow.show', $contract) }}" class="rounded-md px-2.5 py-1.5 text-sm font-semibold shadow-sm ring-1 ring-inset transition-colors
                                                                                                                                                       {{ $isAtFinalStep
                                        ? 'bg-green-50 text-green-700 ring-green-300 hover:bg-green-100'
                                        : 'bg-indigo-50 text-indigo-600 ring-indigo-200 hover:bg-indigo-100' }}">
                                                            {{ $isAtFinalStep ? '✓ ' : '' }}{{ $contract->workflow->getCurrentStepLabel() }}
                                                        </a>
                                    @else
                                        <form action="{{ route('workflow.start', $contract) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="rounded-md bg-yellow-50 px-2.5 py-1.5 text-sm font-semibold text-yellow-700 shadow-sm ring-1 ring-inset ring-yellow-300 hover:bg-yellow-100">SOP
                                                Akhir Kontrak</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('contracts.show', $contract) }}"
                                        class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">View</a>
                                </div>
                            @endunless
                        </div>
                    </li>
                @empty
                    <li class="py-5 px-6 text-center text-sm text-gray-500">No contracts expiring in next 60 days.</li>
                @endforelse
            </ul>

            <!-- Pagination -->
            @if($expiringContracts->hasPages())
                <div class="border-t border-gray-200 px-6 py-4">
                    {{ $expiringContracts->links() }}
                </div>
            @endif
        </div>

        <!-- Expiring Amendments -->
        @if($expiringAmendments->count() > 0)
            <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
                <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
                    <h3 class="text-base font-semibold leading-6 text-gray-900">Amendments Expiring Soon</h3>
                    <p class="mt-1 text-xs text-gray-500">Active amendments within 60 days of expiry</p>
                </div>
                <ul role="list" class="divide-y divide-gray-100">
                    @foreach($expiringAmendments as $amendment)
                        <li class="flex items-center justify-between gap-x-6 py-4 px-6 hover:bg-gray-50 transition-colors">
                            <div class="min-w-0">
                                <div class="flex items-start gap-x-3">
                                    <p class="text-sm font-semibold leading-6 text-gray-900">
                                        {{ $amendment->contract?->tenant?->name ?? '-' }}
                                    </p>
                                    <p class="rounded-md whitespace-nowrap px-1.5 py-0.5 text-xs font-medium ring-1 ring-inset text-indigo-700 bg-indigo-50 ring-indigo-600/10">
                                        Amendment
                                    </p>
                                </div>
                                <div class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                                    <p class="whitespace-nowrap">{{ $amendment->contract?->no_pks ?? $amendment->contract?->no_bak ?? '-' }}</p>
                                    <span class="text-gray-300">•</span>
                                    <p class="whitespace-nowrap">Ends {{ $amendment->new_end_date->format('d M Y') }}</p>
                                </div>
                            </div>
                            @unless(Auth::user()->isGuest())
                                <a href="{{ route('amendments.show', $amendment) }}"
                                    class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">View</a>
                            @endunless
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endsection
