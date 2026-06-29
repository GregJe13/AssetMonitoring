<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class OverduePaymentController extends Controller
{
    public function index()
    {
        $overduePayments = Payment::with(['contract.tenant'])
            ->where('payment_status', 'overdue')
            ->orderBy('due_date', 'asc')
            ->paginate(15);

        $totalOutstanding = Payment::where('payment_status', 'overdue')->sum('amount_due');

        return view('overdue-payments.index', compact('overduePayments', 'totalOutstanding'));
    }
}
