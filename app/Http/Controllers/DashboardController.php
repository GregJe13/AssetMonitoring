<?php

namespace App\Http\Controllers;

use App\Models\Amendment;
use App\Models\Asset;
use App\Models\Contract;
use App\Models\ContractWorkflow;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Auto-update expired contract statuses
        Contract::where('end_date', '<', now()->startOfDay())
            ->where('status', '!=', 'expired')
            ->update(['status' => 'expired']);

        // Auto-update expired amendment statuses
        Amendment::where('new_end_date', '<', now()->startOfDay())
            ->where('status', '!=', 'expired')
            ->update(['status' => 'expired']);
        // 1. Key Metrics
        $paymentRevenue = Payment::where('payment_status', 'paid')
            ->whereYear('paid_at', now()->year)
            ->sum('amount_paid');

        $invoiceRevenue = Invoice::where('status', 'paid')
            ->whereYear('invoice_date', now()->year)
            ->sum('amount');

        $totalRevenue = $paymentRevenue + $invoiceRevenue;

        $totalAssets = Asset::count();
        $rentedAssets = DB::table('contract_assets')
            ->join('contracts', 'contracts.id', '=', 'contract_assets.contract_id')
            ->where('contracts.status', 'active')
            ->count();
            
        // Rented asset IDs from contracts
        $contractRentedIds = DB::table('contract_assets')
            ->join('contracts', 'contracts.id', '=', 'contract_assets.contract_id')
            ->where('contracts.status', 'active')
            ->distinct()
            ->pluck('asset_id');

        // Rented asset IDs from amendments  
        $amendmentRentedIds = DB::table('amendment_assets')
            ->join('amendments', 'amendments.id', '=', 'amendment_assets.amendment_id')
            ->where('amendments.status', 'active')
            ->distinct()
            ->pluck('asset_id');

        // Merge unique asset IDs
        $rentedAssetIds = $contractRentedIds->merge($amendmentRentedIds)->unique()->count();
            
        $occupancyRate = $totalAssets > 0 ? round(($rentedAssetIds / $totalAssets) * 100, 1) : 0;

        $overduePayments = Payment::where('payment_status', 'overdue')->count();
        $overdueAmount = Payment::where('payment_status', 'overdue')->sum('amount_due');

        $contractsExpiringSoon = Contract::where(function ($q) {
                // Still within 60-day window
                $q->where('end_date', '>=', now()->startOfDay())
                  ->where('end_date', '<=', now()->addDays(60));
            })
            ->orWhere(function ($q) {
                // Past expiry but workflow not yet done
                $q->where('end_date', '<', now()->startOfDay())
                  ->where(function ($q2) {
                      $q2->whereDoesntHave('workflow')
                         ->orWhereHas('workflow', fn($wq) => $wq->whereNull('completed_at'));
                  });
            })
            ->count();

        // Also count amendments expiring soon
        $amendmentsExpiringSoon = Amendment::where('status', 'active')
            ->where('new_end_date', '>=', now()->startOfDay())
            ->where('new_end_date', '<=', now()->addDays(60))
            ->count();

        $contractsExpiringSoon += $amendmentsExpiringSoon;

        // 2. Revenue Trend (Last 12 Months) — Payments
        $paymentTrend = Payment::select(
            DB::raw('year(paid_at) as year'), 
            DB::raw('month(paid_at) as month'), 
            DB::raw('sum(amount_paid) as total')
        )
            ->where('payment_status', 'paid')
            ->where('paid_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Revenue Trend — Invoices
        $invoiceTrend = Invoice::select(
            DB::raw('year(invoice_date) as year'),
            DB::raw('month(invoice_date) as month'),
            DB::raw('sum(amount) as total')
        )
            ->where('status', 'paid')
            ->where('invoice_date', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
            
        $months = [];
        $revenueData = [];
        
        // Fill properly for chart (last 12 months including zero months)
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $paymentAmount = $paymentTrend->first(function($item) use ($date) {
                return $item->year == $date->year && $item->month == $date->month;
            });

            $invoiceAmount = $invoiceTrend->first(function($item) use ($date) {
                return $item->year == $date->year && $item->month == $date->month;
            });
            
            $revenueData[] = ($paymentAmount ? $paymentAmount->total : 0) + ($invoiceAmount ? $invoiceAmount->total : 0);
        }

        // 3. Asset Status
        $availableAssets = $totalAssets - $rentedAssetIds;
        
        // 4. Upcoming Contract Expirations (List)
        // Case 1: still within 60-day window → always show
        // Case 2: past expiry but workflow not done → still show until workflow completed
        $expiringContracts = Contract::with(['tenant', 'workflow'])
            ->where(function ($q) {
                // Case 1: within 60-day window
                $q->where('end_date', '>=', now()->startOfDay())
                  ->where('end_date', '<=', now()->addDays(60));
            })
            ->orWhere(function ($q) {
                // Case 2: already expired, but workflow not completed
                $q->where('end_date', '<', now()->startOfDay())
                  ->where(function ($q2) {
                      $q2->whereDoesntHave('workflow')
                         ->orWhereHas('workflow', fn($wq) => $wq->whereNull('completed_at'));
                  });
            })
            ->orderBy('end_date', 'asc')
            ->limit(10)
            ->get();

        $expiringAmendments = Amendment::with(['contract.tenant'])
            ->where('status', 'active')
            ->where('new_end_date', '>=', now()->startOfDay())
            ->where('new_end_date', '<=', now()->addDays(60))
            ->orderBy('new_end_date', 'asc')
            ->limit(5)
            ->get();

        // 4b. Pending Renewal Follow-ups (workflow selesai tapi belum buat kontrak/amandemen)
        $pendingRenewals = ContractWorkflow::with(['contract.tenant'])
            ->where('renewal_action', 'pending')
            ->orderBy('completed_at', 'asc')
            ->get();

        // 5. Recent Overdue Payments
        $recentOverdue = Payment::with(['contract.tenant'])
            ->where('payment_status', 'overdue')
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        // 6. Unpaid Invoices
        $unpaidInvoices = Invoice::with(['tenant', 'assets'])
            ->where('status', 'unpaid')
            ->orderBy('due_date', 'asc')
            ->orderBy('invoice_date', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalRevenue',
            'occupancyRate',
            'overduePayments',
            'overdueAmount',
            'contractsExpiringSoon',
            'months',
            'revenueData',
            'rentedAssetIds',
            'availableAssets',
            'expiringContracts',
            'expiringAmendments',
            'pendingRenewals',
            'recentOverdue',
            'unpaidInvoices'
        ));
    }
}
