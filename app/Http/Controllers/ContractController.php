<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Contract;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        $query = Contract::with(['tenant', 'assets']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_pks', 'like', "%{$search}%")
                  ->orWhere('no_bak', 'like', "%{$search}%")
                  ->orWhereHas('tenant', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $activeContracts = (clone $query)->where('end_date', '>=', now()->startOfDay())
            ->orderBy('end_date')
            ->paginate(10, ['*'], 'active_page');

        $expiredContracts = (clone $query)->where('end_date', '<', now()->startOfDay())
            ->orderBy('end_date', 'desc')
            ->paginate(10, ['*'], 'log_page');

        $tab = $request->get('tab', 'active');

        return view('contracts.index', compact('activeContracts', 'expiredContracts', 'tab'));
    }

    public function create(Request $request)
    {
        $selectedTenantId = $request->query('tenant_id');
        $tenants = Tenant::orderBy('name')->get();
        
        // Show ALL assets - partial rental allows any asset with available space
        $assets = Asset::orderBy('name')->get();

        return view('contracts.create', compact('tenants', 'assets', 'selectedTenantId'));
    }

    public function store(Request $request)
    {
        // First, filter out empty or zero asset areas
        $assetAreas = collect($request->asset_areas ?? [])
            ->filter(fn($area) => $area !== null && $area !== '' && floatval($area) > 0)
            ->toArray();

        // Merge filtered asset_areas back to request for validation
        $request->merge(['asset_areas' => $assetAreas]);

        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            // PKS fields - if no_pks filled, date_pks and file_pks required
            'no_pks' => 'nullable|string|unique:contracts,no_pks',
            'date_pks' => 'required_with:no_pks|nullable|date',
            'file_pks' => 'required_with:no_pks|nullable|file|mimes:pdf|max:10240',
            // BAK fields - if no_bak filled, date_bak and file_bak required
            'no_bak' => 'nullable|string|unique:contracts,no_bak',
            'date_bak' => 'required_with:no_bak|nullable|date',
            'file_bak' => 'required_with:no_bak|nullable|file|mimes:pdf|max:10240',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_rental_value' => 'required|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'payment_interval_value' => 'required_if:is_upfront,0|integer|min:1',
            'payment_interval_unit' => 'required_if:is_upfront,0|in:month,year',
            'is_upfront' => 'boolean',
            'payment_start_date' => 'nullable|date|after_or_equal:start_date|before_or_equal:end_date',
            'pihak_pertama' => 'required|string',
            'pihak_kedua' => 'required|string',
            'asset_areas' => 'required|array|min:1',
            'asset_areas.*' => 'numeric|min:0.01',
        ], [
            'asset_areas.required' => 'Please select at least one asset with a non-zero area.',
            'asset_areas.min' => 'Please select at least one asset with a non-zero area.',
            'payment_start_date.after_or_equal' => 'Tanggal mulai pembayaran harus sama atau setelah tanggal mulai kontrak.',
            'payment_start_date.before_or_equal' => 'Tanggal mulai pembayaran tidak boleh melebihi tanggal akhir kontrak.',
            'date_pks.required_with' => 'Tanggal PKS wajib diisi jika No. PKS diisi.',
            'file_pks.required_with' => 'File PKS wajib diupload jika No. PKS diisi.',
            'file_pks.mimes' => 'File PKS harus berformat PDF.',
            'file_pks.max' => 'File PKS maksimal 10MB.',
            'date_bak.required_with' => 'Tanggal BAK wajib diisi jika No. BAK diisi.',
            'file_bak.required_with' => 'File BAK wajib diupload jika No. BAK diisi.',
            'file_bak.mimes' => 'File BAK harus berformat PDF.',
            'file_bak.max' => 'File BAK maksimal 10MB.',
        ]);

        // Custom validation: at least one of BAK or PKS must be filled
        if (empty($request->no_pks) && empty($request->no_bak)) {
            return back()->withErrors([
                'no_pks' => 'Harus mengisi minimal salah satu: No. PKS atau No. BAK.',
                'no_bak' => 'Harus mengisi minimal salah satu: No. PKS atau No. BAK.',
            ])->withInput();
        }

        // Validate each asset's available space for the contract period
        $errors = [];
        foreach ($assetAreas as $assetId => $requestedArea) {
            $asset = Asset::find($assetId);
            if (!$asset) {
                $errors["asset_areas.{$assetId}"] = "Asset not found.";
                continue;
            }
            $availableForPeriod = $asset->getAvailableAreaForPeriod($validated['start_date'], $validated['end_date']);
            if ($requestedArea > $availableForPeriod) {
                $errors["asset_areas.{$assetId}"] = "Luas yang diminta ({$requestedArea} m²) melebihi area tersedia ({$availableForPeriod} m²) untuk {$asset->name} pada periode kontrak ini.";
            }
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        // Handle file uploads
        if ($request->hasFile('file_pks')) {
            $validated['file_pks'] = $request->file('file_pks')->store('contracts', 'public');
        }
        if ($request->hasFile('file_bak')) {
            $validated['file_bak'] = $request->file('file_bak')->store('contracts', 'public');
        }

        // Create contract
        $contract = Contract::create($validated);

        // Attach assets with their rented areas
        $attachData = [];
        foreach ($assetAreas as $assetId => $rentedArea) {
            $attachData[$assetId] = ['rented_area_sqm' => $rentedArea];
        }
        $contract->assets()->attach($attachData);

        return redirect()->route('contracts.index')->with('success', 'Contract created successfully. Payment schedule generated.');
    }

    public function show(Contract $contract)
    {
        $contract->load(['tenant', 'assets', 'payments', 'amendments.assets']);
        return view('contracts.show', compact('contract'));
    }

    public function print(Contract $contract)
    {
        $contract->load(['tenant', 'assets', 'payments', 'amendments.assets']);
        return view('contracts.print', compact('contract'));
    }

    public function edit(Contract $contract)
    {
        $tenants = Tenant::orderBy('name')->get();
        // Show all assets for partial rental support
        $assets = Asset::orderBy('name')->get();
        // Get currently attached assets with their rented areas
        $attachedAssets = $contract->assets->pluck('pivot.rented_area_sqm', 'id')->toArray();

        return view('contracts.edit', compact('contract', 'tenants', 'assets', 'attachedAssets'));
    }

    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            // PKS fields - if no_pks filled, date_pks required (file only if new upload)
            'no_pks' => 'nullable|string|unique:contracts,no_pks,' . $contract->id,
            'date_pks' => 'required_with:no_pks|nullable|date',
            'file_pks' => 'nullable|file|mimes:pdf|max:10240',
            // BAK fields - if no_bak filled, date_bak required (file only if new upload)
            'no_bak' => 'nullable|string|unique:contracts,no_bak,' . $contract->id,
            'date_bak' => 'required_with:no_bak|nullable|date',
            'file_bak' => 'nullable|file|mimes:pdf|max:10240',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_rental_value' => 'required|numeric|min:0',
            'payment_start_date' => 'nullable|date|after_or_equal:start_date|before_or_equal:end_date',
            'pihak_pertama' => 'required|string',
            'pihak_kedua' => 'required|string',
            'asset_areas' => 'array',
        ], [
            'date_pks.required_with' => 'Tanggal PKS wajib diisi jika No. PKS diisi.',
            'file_pks.mimes' => 'File PKS harus berformat PDF.',
            'file_pks.max' => 'File PKS maksimal 10MB.',
            'date_bak.required_with' => 'Tanggal BAK wajib diisi jika No. BAK diisi.',
            'file_bak.mimes' => 'File BAK harus berformat PDF.',
            'file_bak.max' => 'File BAK maksimal 10MB.',
            'payment_start_date.after_or_equal' => 'Tanggal mulai pembayaran harus sama atau setelah tanggal mulai kontrak.',
            'payment_start_date.before_or_equal' => 'Tanggal mulai pembayaran tidak boleh melebihi tanggal akhir kontrak.',
        ]);

        // Custom validation: at least one of BAK or PKS must be filled
        if (empty($request->no_pks) && empty($request->no_bak)) {
            return back()->withErrors([
                'no_pks' => 'Harus mengisi minimal salah satu: No. PKS atau No. BAK.',
                'no_bak' => 'Harus mengisi minimal salah satu: No. PKS atau No. BAK.',
            ])->withInput();
        }

        // Custom validation: if no_pks filled and no existing file, require file upload
        if (!empty($request->no_pks) && !$contract->file_pks && !$request->hasFile('file_pks')) {
            return back()->withErrors([
                'file_pks' => 'File PKS wajib diupload jika No. PKS diisi.',
            ])->withInput();
        }

        // Custom validation: if no_bak filled and no existing file, require file upload
        if (!empty($request->no_bak) && !$contract->file_bak && !$request->hasFile('file_bak')) {
            return back()->withErrors([
                'file_bak' => 'File BAK wajib diupload jika No. BAK diisi.',
            ])->withInput();
        }

        // Handle file uploads
        if ($request->hasFile('file_pks')) {
            // Delete old file if exists
            if ($contract->file_pks) {
                \Storage::disk('public')->delete($contract->file_pks);
            }
            $validated['file_pks'] = $request->file('file_pks')->store('contracts', 'public');
        }
        if ($request->hasFile('file_bak')) {
            // Delete old file if exists
            if ($contract->file_bak) {
                \Storage::disk('public')->delete($contract->file_bak);
            }
            $validated['file_bak'] = $request->file('file_bak')->store('contracts', 'public');
        }

        $contract->update($validated);

        // Update asset attachments if provided
        if ($request->has('asset_areas') && !empty($request->asset_areas)) {
            // Validate available space for the contract period (excluding this contract)
            $errors = [];
            foreach ($request->asset_areas as $assetId => $requestedArea) {
                $asset = Asset::find($assetId);
                if (!$asset) continue;
                
                $availableForPeriod = $asset->getAvailableAreaForPeriod(
                    $validated['start_date'], $validated['end_date'], $contract->id
                );
                
                if ($requestedArea > $availableForPeriod) {
                    $errors["asset_areas.{$assetId}"] = "Luas yang diminta ({$requestedArea} m²) melebihi area tersedia ({$availableForPeriod} m²) untuk {$asset->name} pada periode kontrak ini.";
                }
            }

            if (!empty($errors)) {
                return back()->withErrors($errors)->withInput();
            }

            // Sync with new areas - filter out null/empty values
            $syncData = [];
            foreach ($request->asset_areas as $assetId => $rentedArea) {
                // Only include if rented area is a valid positive number
                if ($rentedArea !== null && $rentedArea !== '' && floatval($rentedArea) > 0) {
                    $syncData[$assetId] = ['rented_area_sqm' => floatval($rentedArea)];
                }
            }
            $contract->assets()->sync($syncData);
        }

        return redirect()->route('contracts.show', $contract)->with('success', 'Contract updated successfully.');
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();
        return redirect()->route('contracts.index')->with('success', 'Contract terminated/deleted successfully.');
    }

    /**
     * View/Download contract PDF file (BAK or PKS)
     */
    public function viewFile(Contract $contract, string $type)
    {
        $fileField = 'file_' . $type;
        
        if (!in_array($type, ['bak', 'pks'])) {
            abort(404, 'Invalid file type.');
        }

        if (!$contract->$fileField) {
            abort(404, 'File not found.');
        }

        $path = storage_path('app/public/' . $contract->$fileField);
        
        if (!file_exists($path)) {
            abort(404, 'File not found on disk.');
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Update renewal notes for a contract (AJAX endpoint for dashboard)
     */
    public function updateRenewalNotes(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'renewal_notes' => 'nullable|string|max:1000',
        ]);

        $contract->update([
            'renewal_notes' => $validated['renewal_notes'] ?? '',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notes updated successfully.',
            'notes' => $contract->renewal_notes,
        ]);
    }

    public function search(Request $request)
    {
        $query = Contract::with(['tenant', 'assets']);
        $tab = $request->get('tab', 'active');

        // Filter by tab
        if ($tab === 'log') {
            $query->where('end_date', '<', now()->startOfDay());
        } else {
            $query->where('end_date', '>=', now()->startOfDay());
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_pks', 'like', "%{$search}%")
                  ->orWhere('no_bak', 'like', "%{$search}%")
                  ->orWhereHas('tenant', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($tab === 'log') {
            $allContracts = $query->orderBy('end_date', 'desc')->get();
        } else {
            $allContracts = $query->orderBy('end_date')->get();
        }

        $html = view('contracts._grid', ['contracts' => $allContracts])->render();
        
        return response()->json([
            'html' => $html,
            'count' => $allContracts->count()
        ]);
    }

    /**
     * Return all assets with their available area for a specific date range.
     * Used by the create contract form to dynamically show asset availability.
     */
    public function assetsForPeriod(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'exclude_contract' => 'nullable|integer|exists:contracts,id',
        ]);

        $assets = Asset::orderBy('name')->get()->map(function ($asset) use ($request) {
            $available = $asset->getAvailableAreaForPeriod(
                $request->start_date,
                $request->end_date,
                $request->exclude_contract
            );

            return [
                'id' => $asset->id,
                'name' => $asset->name,
                'id_gedung' => $asset->id_gedung,
                'area_sqm' => (float) $asset->area_sqm,
                'available_area' => $available,
                'is_full' => $available <= 0,
            ];
        });

        return response()->json($assets);
    }
}
