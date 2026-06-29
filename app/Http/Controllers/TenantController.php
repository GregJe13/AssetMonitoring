<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tenant::withCount(['contracts as active_contracts_count' => function ($query) {
            $query->where('status', 'active');
        }]);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('pic', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $tenants = $query->paginate(10);

        return view('tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tenants.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id_tenant' => 'nullable|integer',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'npwp' => 'nullable|string|max:30',
            'pic' => 'nullable|string|max:255',
            'pic_phone' => 'nullable|string|max:20',
        ]);

        Tenant::create($validated);

        return redirect()->route('tenants.index')
            ->with('success', 'Tenant created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        // Eager load history sorted by date
        $contractHistory = $tenant->contractHistory()
            ->with(['assets', 'amendments', 'payments'])
            ->orderBy('start_date', 'desc')
            ->get();
            
        $partnershipSummary = $tenant->getPartnershipSummary();

        // Invoice history for this tenant
        $invoiceHistory = $tenant->invoices()
            ->with('assets')
            ->orderBy('payment_date', 'desc')
            ->get();

        return view('tenants.show', compact('tenant', 'contractHistory', 'partnershipSummary', 'invoiceHistory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        return view('tenants.edit', compact('tenant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id_tenant' => 'nullable|integer',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'npwp' => 'nullable|string|max:30',
            'pic' => 'nullable|string|max:255',
            'pic_phone' => 'nullable|string|max:20',
        ]);

        $tenant->update($validated);

        return redirect()->route('tenants.index')
            ->with('success', 'Tenant updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        
        return redirect()->route('tenants.index')
            ->with('success', 'Tenant deleted successfully.');
    }

    /**
     * AJAX search endpoint for tenants.
     */
    public function search(Request $request)
    {
        $search = $request->get('search', '');

        $tenants = Tenant::withCount(['contracts as active_contracts_count' => function ($query) {
                $query->where('status', 'active');
            }])
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('pic', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('id_tenant', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->get();

        $html = view('tenants._row', compact('tenants'))->render();

        return response()->json([
            'html'  => $html,
            'count' => $tenants->count(),
        ]);
    }
}
