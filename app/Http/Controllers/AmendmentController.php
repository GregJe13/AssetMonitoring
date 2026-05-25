<?php

namespace App\Http\Controllers;

use App\Models\Amendment;
use App\Models\Asset;
use App\Models\Contract;
use App\Models\ContractWorkflow;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AmendmentController extends Controller
{
    /**
     * List all amendments.
     */
    public function index(Request $request)
    {
        $query = Amendment::with(['contract.tenant']);

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_amendment', 'like', "%{$search}%")
                    ->orWhereHas('contract', function ($q2) use ($search) {
                        $q2->where('no_pks', 'like', "%{$search}%")
                            ->orWhere('no_bak', 'like', "%{$search}%");
                    })
                    ->orWhereHas('contract.tenant', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $amendments = $query->orderBy('date_amendment', 'desc')->get();

        return view('amendments.index', compact('amendments'));
    }

    /**
     * Show create form.
     */
    public function create(Request $request)
    {
        $tenants = Tenant::orderBy('name')->get();
        $selectedTenantId = $request->query('tenant_id');
        $selectedContractId = $request->query('contract_id');
        return view('amendments.create', compact('tenants', 'selectedTenantId', 'selectedContractId'));
    }

    /**
     * AJAX: get last 5 contracts for a tenant.
     */
    public function contractsForTenant(Tenant $tenant)
    {
        $contracts = $tenant->contracts()
            ->with('assets')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($contract) {
                return [
                    'id' => $contract->id,
                    'no_pks' => $contract->no_pks,
                    'no_bak' => $contract->no_bak,
                    'start_date' => $contract->start_date->format('Y-m-d'),
                    'end_date' => $contract->end_date->format('Y-m-d'),
                    'start_date_formatted' => $contract->start_date->format('d M Y'),
                    'end_date_formatted' => $contract->end_date->format('d M Y'),
                    'status' => $contract->status,
                    'total_rental_value' => (float) $contract->total_rental_value,
                    'amendment_count' => $contract->amendments()->count(),
                    'assets' => $contract->assets->map(fn($a) => [
                        'name' => $a->name,
                        'rented_area' => $a->pivot->rented_area_sqm,
                    ]),
                ];
            });

        return response()->json($contracts);
    }

    /**
     * Store a new amendment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'amendment_number' => 'required|integer|min:1',
            'no_amendment' => 'required|string|unique:amendments,no_amendment',
            'date_amendment' => 'required|date',
            'new_start_date' => 'required|date',
            'new_end_date' => 'required|date|after_or_equal:new_start_date',
            'total_rental_value' => 'required|numeric|min:0',
            'payment_type' => 'required|in:upfront,interval,termin',
            'payment_start_date' => 'nullable|date',
            'payment_interval_value' => 'required_if:payment_type,interval|nullable|integer|min:1',
            'payment_interval_unit' => 'required_if:payment_type,interval|nullable|in:month,year',
            // Termin fields
            'termins' => 'required_if:payment_type,termin|nullable|array|min:1',
            'termins.*.due_date' => 'required_with:termins|date',
            'termins.*.amount_due' => 'required_with:termins|numeric|min:1',
            'no_bak' => 'nullable|string',
            'date_bak' => 'nullable|date',
            'file_bak' => 'nullable|file|mimes:pdf|max:10240',
            'no_pks' => 'nullable|string',
            'date_pks' => 'nullable|date',
            'file_pks' => 'nullable|file|mimes:pdf|max:10240',
            'pihak_pertama' => 'required|string',
            'pihak_kedua' => 'required|string',
            'notes' => 'nullable|string',
            'asset_areas' => 'required|array|min:1',
            'asset_areas.*' => 'nullable|numeric|min:0',
        ], [
            'no_amendment.unique' => 'Nomor amandemen sudah digunakan.',
            'asset_areas.required' => 'Pilih minimal satu asset.',
            'new_end_date.after_or_equal' => 'Tanggal akhir harus sama atau setelah tanggal mulai.',
            'termins.required_if' => 'Termin data harus diisi jika tipe pembayaran adalah Termin.',
        ]);

        // If payment_type is termin, compute total from termins
        $termins = [];
        if ($validated['payment_type'] === 'termin' && !empty($validated['termins'])) {
            $termins = $validated['termins'];
            $validated['total_rental_value'] = collect($termins)->sum('amount_due');
        }
        unset($validated['termins']);

        // Filter out zero/null asset areas
        $assetAreas = collect($request->asset_areas)->filter(fn($v) => $v > 0);
        if ($assetAreas->isEmpty()) {
            return back()->withErrors(['asset_areas' => 'Pilih minimal satu asset dengan area > 0.'])->withInput();
        }

        // Validate asset availability for the period
        foreach ($assetAreas as $assetId => $area) {
            $asset = Asset::findOrFail($assetId);
            $available = $asset->getAvailableAreaForPeriod($request->new_start_date, $request->new_end_date);
            if ($area > $available) {
                return back()->withErrors([
                    "asset_areas.{$assetId}" => "Area untuk {$asset->name} melebihi ketersediaan ({$available} m² tersedia).",
                ])->withInput();
            }
        }

        $contract = Contract::findOrFail($request->contract_id);

        // Handle file uploads
        if ($request->hasFile('file_bak')) {
            $validated['file_bak'] = $request->file('file_bak')->store('amendment-files', 'public');
        }
        if ($request->hasFile('file_pks')) {
            $validated['file_pks'] = $request->file('file_pks')->store('amendment-files', 'public');
        }

        DB::transaction(function () use ($validated, $contract, $assetAreas, $termins) {
            // Set old dates from parent contract (or latest amendment)
            $latestAmendment = $contract->amendments()->orderBy('amendment_number', 'desc')->first();
            $validated['old_start_date'] = $latestAmendment ? $latestAmendment->new_start_date : $contract->start_date;
            $validated['old_end_date'] = $latestAmendment ? $latestAmendment->new_end_date : $contract->end_date;

            $amendment = Amendment::create($validated);

            // Attach assets
            foreach ($assetAreas as $assetId => $area) {
                $amendment->assets()->attach($assetId, ['rented_area_sqm' => $area]);
            }

            // Generate payment schedule
            $amendment->generatePaymentSchedule($termins);

            // Mark workflow as followed-up
            $this->markWorkflowRenewal($validated['contract_id']);
        });

        return redirect()->route('amendments.index')
            ->with('success', 'Amandemen berhasil dibuat.');
    }

    /**
     * Mark workflow renewal_action as 'amendment' when amendment is stored for that contract.
     */
    private function markWorkflowRenewal(int $contractId): void
    {
        ContractWorkflow::where('contract_id', $contractId)
            ->where('branch', 'B')
            ->where('renewal_action', 'pending')
            ->update(['renewal_action' => 'amendment']);
    }

    /**
     * Show amendment detail.
     */
    public function show(Amendment $amendment)
    {
        $amendment->load(['contract.tenant', 'assets', 'payments']);
        return view('amendments.show', compact('amendment'));
    }

    /**
     * View/Download amendment file.
     */
    public function viewFile(Amendment $amendment, string $type)
    {
        $fileField = 'file_' . $type;

        if (!in_array($type, ['bak', 'pks'])) {
            abort(404, 'Invalid file type.');
        }

        if (!$amendment->$fileField) {
            abort(404, 'File not found.');
        }

        $path = storage_path('app/public/' . $amendment->$fileField);

        if (!file_exists($path)) {
            abort(404, 'File not found on disk.');
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
