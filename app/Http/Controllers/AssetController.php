<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('id_gedung', 'like', "%{$search}%");
        }

        // Fetch all assets first (sorting needs to be done in PHP because rented_area is calculated)
        $allAssets = $query->orderBy('id')->get();

        // Calculate total usage percentage (rented + company) and sort by it (highest usage first)
        $sortedAssets = $allAssets->sortByDesc(function ($asset) {
            if ($asset->area_sqm <= 0) {
                return 0;
            }

            return (($asset->rented_area + (float) $asset->company_used_area_sqm) / $asset->area_sqm) * 100;
        })->values();

        // Manual pagination
        $perPage = 15;
        $currentPage = $request->get('page', 1);
        $pagedAssets = $sortedAssets->forPage($currentPage, $perPage);

        $assets = new \Illuminate\Pagination\LengthAwarePaginator(
            $pagedAssets,
            $sortedAssets->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('assets.index', compact('assets'));
    }

    public function create()
    {
        return view('assets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id_gedung' => 'required|string|unique:assets,id_gedung',
            'area_sqm' => 'required|numeric|min:0',
            'company_used_area_sqm' => 'nullable|numeric|min:0',
            'building_condition' => 'required|in:baik,cukup,rusak_ringan,rusak_berat,perlu_renovasi',
        ]);

        Asset::create($validated);

        return redirect()->route('assets.index')->with('success', 'Asset created successfully.');
    }

    public function show(Asset $asset)
    {
        // Show history of who rented this asset
        $history = $asset->contracts()
            ->with('tenant')
            ->orderBy('start_date', 'desc')
            ->get();

        return view('assets.show', compact('asset', 'history'));
    }

    public function edit(Asset $asset)
    {
        return view('assets.edit', compact('asset'));
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id_gedung' => 'required|string|unique:assets,id_gedung,'.$asset->id,
            'area_sqm' => 'required|numeric|min:0',
            'company_used_area_sqm' => 'nullable|numeric|min:0',
            'building_condition' => 'required|in:baik,cukup,rusak_ringan,rusak_berat,perlu_renovasi',
        ]);

        $asset->update($validated);

        return redirect()->route('assets.index')->with('success', 'Asset updated successfully.');
    }

    /**
     * Update hanya kolom luas yang dipakai perusahaan (inline edit dari halaman index, via AJAX).
     */
    public function updateCompanyArea(Request $request, Asset $asset)
    {
        // Max allowed = total area minus area rented by tenants
        $maxAllowed = max(0, (float) $asset->area_sqm - $asset->rented_area);

        $validated = $request->validate([
            'company_used_area_sqm' => ['required', 'numeric', 'min:0', 'max:'.$maxAllowed],
        ], [
            'company_used_area_sqm.max' => "Luas maksimal yang bisa dipakai perusahaan adalah {$maxAllowed} m² (total {$asset->area_sqm} m² dikurangi {$asset->rented_area} m² yang disewa tenant).",
        ]);

        $asset->update($validated);

        // Recalculate all values after update
        $asset->refresh();
        $companyUsed = (float) $asset->company_used_area_sqm;
        $rentedArea = $asset->rented_area;
        $totalArea = (float) $asset->area_sqm;
        $totalOccupied = $rentedArea + $companyUsed;
        $usagePercent = $totalArea > 0 ? round(($totalOccupied / $totalArea) * 100, 0) : 0;
        $availableArea = $asset->available_area;
        $unusedArea = $asset->unused_area;
        $maxCompanyArea = max(0, $totalArea - $rentedArea);

        return response()->json([
            'success'              => true,
            'company_used_area_sqm'=> $companyUsed,
            'unused_area'          => number_format($unusedArea, 0),
            'available_area'       => number_format($availableArea, 0),
            'usage_percent'        => $usagePercent,
            'total_occupied'       => $totalOccupied,
            'is_full'              => $availableArea <= 0,
            'rented_area'          => $rentedArea,
            'total_area'           => $totalArea,
            'max_company_area'     => $maxCompanyArea,
        ]);
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();

        return redirect()->route('assets.index')->with('success', 'Asset deleted successfully.');
    }

    /**
     * AJAX search endpoint for live search functionality.
     * Returns HTML partial of asset cards.
     */
    public function search(Request $request)
    {
        $query = Asset::query();

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('id_gedung', 'like', "%{$search}%");
        }

        // Fetch and sort by total usage percentage (rented + company)
        $allAssets = $query->orderBy('id')->get();
        $sortedAssets = $allAssets->sortByDesc(function ($asset) {
            if ($asset->area_sqm <= 0) {
                return 0;
            }

            return (($asset->rented_area + (float) $asset->company_used_area_sqm) / $asset->area_sqm) * 100;
        })->values();

        // Return rendered HTML partial
        $html = view('assets._grid', ['assets' => $sortedAssets])->render();

        return response()->json([
            'html' => $html,
            'count' => $sortedAssets->count(),
        ]);
    }
}
