<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['contract.tenant']);

        // Filter by status (default: show all, or prioritize overdue/pending)
        if ($request->has('status') && $request->status != '') {
             $query->where('payment_status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('contract.tenant', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Sort: Overdue first, then Pending (by due date asc), then Paid (by date desc)
        $payments = $query->orderByRaw("FIELD(payment_status, 'overdue', 'pending', 'paid')")
            ->orderBy('due_date')
            ->paginate(15);

        // Also fetch invoices for the invoice section
        $invoiceQuery = Invoice::with(['tenant', 'assets']);

        if ($request->has('status') && $request->status != '') {
            $invoiceQuery->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $invoiceQuery->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('tenant_name_manual', 'like', "%{$search}%")
                  ->orWhereHas('tenant', fn($t) => $t->where('name', 'like', "%{$search}%"));
            });
        }

        $invoices = $invoiceQuery->orderByRaw("FIELD(status, 'unpaid', 'draft', 'paid', 'cancelled')")
            ->orderBy('invoice_date', 'desc')
            ->paginate(10, ['*'], 'inv_page');

        return view('payments.index', compact('payments', 'invoices'));
    }

    public function show(Payment $payment)
    {
        return redirect()->route('contracts.show', $payment->contract_id);
    }

    public function update(Request $request, Payment $payment)
    {
        if ($request->input('action') === 'mark_as_paid') {
            $payment->markAsPaid();
            return back()->with('success', 'Payment marked as paid successfully.');
        }

        return back()->with('error', 'Invalid action.');
    }
}
