@forelse($contracts as $contract)
<tr>
    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
        <div class="font-medium text-gray-900">{{ $contract->start_date->format('d M Y') }}</div>
        <div class="text-gray-500 text-xs">to {{ $contract->end_date->format('d M Y') }}</div>
        @if($contract->is_expired)
            <div class="mt-1 text-xs text-red-600 font-medium">Expired {{ $contract->days_expired }} hari lalu</div>
        @else
            <div class="mt-1 text-xs text-gray-400">{{ $contract->remaining_days }} days left</div>
        @endif
    </td>
    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
        <div class="font-bold text-gray-900 mb-1">{{ $contract->tenant->name }}</div>
        @if($contract->no_pks)
            <div class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">PKS: {{ $contract->no_pks }}</div>
        @endif
        @if($contract->no_bak)
            <div class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">BAK: {{ $contract->no_bak }}</div>
        @endif
    </td>
    <td class="px-3 py-4 text-sm text-gray-500 max-w-xs truncate">
        {{ $contract->assets->count() }} Units
        <div class="text-xs text-gray-400 truncate" title="{{ $contract->assets->pluck('name')->join(', ') }}">
            {{ $contract->assets->pluck('name')->join(', ') }}
        </div>
    </td>
    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 font-medium">
        Rp {{ number_format($contract->total_rental_value) }}
    </td>
    <td class="whitespace-nowrap px-3 py-4 text-sm">
        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset 
            {{ $contract->is_expired ? 'bg-red-50 text-red-700 ring-red-600/10' : ($contract->status == 'active' ? 'bg-green-50 text-green-700 ring-green-600/20' : 'bg-gray-50 text-gray-600 ring-gray-500/10') }}">
            {{ $contract->is_expired ? 'Expired' : ucfirst($contract->status) }}
        </span>
        @if($contract->is_expired)
            <div class="text-xs text-red-500 mt-1">{{ $contract->days_expired }} hari</div>
        @endif
    </td>
    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
        <a href="{{ route('contracts.show', $contract) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="px-3 py-4 text-center text-sm text-gray-500">No contracts found.</td>
</tr>
@endforelse