<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Tenant;
use App\Models\Asset;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['tenant', 'assets']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('tenant_name_manual', 'like', "%{$search}%")
                  ->orWhereHas('tenant', fn($t) => $t->where('name', 'like', "%{$search}%"));
            });
        }

        $invoices = $query->orderBy('invoice_date', 'desc')->paginate(10)->withQueryString();

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $tenants = Tenant::orderBy('name')->get();
        $assets = Asset::orderBy('name')->get();

        return view('invoices.create', compact('tenants', 'assets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'tenant_id' => 'nullable|exists:tenants,id',
            'tenant_name_manual' => 'nullable|string|max:255',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'notes' => 'nullable|string',
            'asset_ids' => 'nullable|array',
            'asset_ids.*' => 'exists:assets,id',
            'invoice_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        // Handle file upload
        if ($request->hasFile('invoice_file')) {
            $file = $request->file('invoice_file');
            $validated['file_path'] = $file->store('invoices', 'public');
            $validated['file_original_name'] = $file->getClientOriginalName();
        }

        $data = collect($validated)->except('asset_ids', 'invoice_file')->toArray();
        $data['status'] = 'unpaid';

        $invoice = Invoice::create($data);

        // Attach assets
        if (!empty($validated['asset_ids'])) {
            $invoice->assets()->attach($validated['asset_ids']);
        }

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice berhasil dibuat.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['tenant', 'assets']);

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load(['tenant', 'assets']);
        $tenants = Tenant::orderBy('name')->get();
        $assets = Asset::orderBy('name')->get();

        return view('invoices.edit', compact('invoice', 'tenants', 'assets'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|unique:invoices,invoice_number,' . $invoice->id,
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'tenant_id' => 'nullable|exists:tenants,id',
            'tenant_name_manual' => 'nullable|string|max:255',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'notes' => 'nullable|string',
            'asset_ids' => 'nullable|array',
            'asset_ids.*' => 'exists:assets,id',
            'invoice_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        // Handle file upload
        if ($request->hasFile('invoice_file')) {
            $file = $request->file('invoice_file');
            $validated['file_path'] = $file->store('invoices', 'public');
            $validated['file_original_name'] = $file->getClientOriginalName();
        }

        $invoice->update(collect($validated)->except('asset_ids', 'invoice_file')->toArray());

        // Sync assets
        $invoice->assets()->sync($validated['asset_ids'] ?? []);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice berhasil diperbarui.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice berhasil dihapus.');
    }

    public function markPaid(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->with('info', 'Invoice sudah dibayar.');
        }

        $invoice->markAsPaid();

        return back()->with('success', 'Invoice berhasil ditandai sebagai Paid.');
    }
}
