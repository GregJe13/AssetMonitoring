@forelse($tenants as $tenant)
<tr>
    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
        <div class="font-bold text-indigo-600">
            <a href="{{ route('tenants.show', $tenant) }}" class="hover:underline">{{ $tenant->name }}</a>
        </div>
        <div class="text-gray-500 text-xs">{{ $tenant->email ?? '-' }}</div>
    </td>
    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
        <div class="font-medium text-gray-900">{{ $tenant->pic ?? '-' }}</div>
        <div class="text-gray-500 text-xs">{{ $tenant->pic_phone ?? $tenant->phone ?? '-' }}</div>
    </td>
    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
        {{ $tenant->id_tenant ?? '-' }}
    </td>
    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
        @if($tenant->active_contracts_count > 0)
            <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Active ({{ $tenant->active_contracts_count }})</span>
        @else
            <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Inactive</span>
        @endif
    </td>
    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
        <a href="{{ route('tenants.show', $tenant) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
        <a href="{{ route('tenants.edit', $tenant) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="px-3 py-4 text-sm text-gray-500 text-center">No tenants found.</td>
</tr>
@endforelse
