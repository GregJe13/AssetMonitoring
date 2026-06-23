<?php

namespace App\Http\Controllers;

use App\Models\ActualRevenue;
use App\Models\Amendment;
use App\Models\Asset;
use App\Models\Contract;
use App\Models\ContractWorkflow;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $currentYear = now()->year;
        $selectedYear = (int) $request->input('accrual_year', $currentYear);

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
            ->whereYear('paid_at', $selectedYear)
            ->sum('amount_paid');

        $invoiceRevenue = Invoice::where('status', 'paid')
            ->whereYear('invoice_date', $selectedYear)
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

        // Active Tenant counts by contract type
        $activeSewaTenantsCount = Tenant::whereHas(
            'contractHistory',
            fn ($q) => $q->where('status', 'active')
                ->where('end_date', '>=', now()->startOfDay())
                ->where('contract_type', 'sewa')
        )->count();

        $activeKsuTenantsCount = Tenant::whereHas(
            'contractHistory',
            fn ($q) => $q->where('status', 'active')
                ->where('end_date', '>=', now()->startOfDay())
                ->where('contract_type', 'ksu')
        )->count();

        $totalActiveTenantsCount = $activeSewaTenantsCount + $activeKsuTenantsCount;

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
                            ->orWhereHas('workflow', fn ($wq) => $wq->whereNull('completed_at'));
                    });
            })
            ->count();

        // Also count amendments expiring soon
        $amendmentsExpiringSoon = Amendment::where('status', 'active')
            ->where('new_end_date', '>=', now()->startOfDay())
            ->where('new_end_date', '<=', now()->addDays(60))
            ->count();

        $contractsExpiringSoon += $amendmentsExpiringSoon;

        // 2. Revenue Trend (Current Year: Jan - Dec)
        $currentYear = now()->year;

        $paymentTrend = Payment::select(
            DB::raw('year(paid_at) as year'),
            DB::raw('month(paid_at) as month'),
            DB::raw('sum(amount_paid) as total')
        )
            ->where('payment_status', 'paid')
            ->whereYear('paid_at', $selectedYear)
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
            ->whereYear('invoice_date', $selectedYear)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $months = [];
        $revenueData = [];

        // Fill properly for chart (Jan - Dec of selected year)
        for ($month = 1; $month <= 12; $month++) {
            $date = Carbon::createFromDate($selectedYear, $month, 1);
            $months[] = $date->format('M Y');

            $paymentAmount = $paymentTrend->first(function ($item) use ($selectedYear, $month) {
                return $item->year == $selectedYear && $item->month == $month;
            });

            $invoiceAmount = $invoiceTrend->first(function ($item) use ($selectedYear, $month) {
                return $item->year == $selectedYear && $item->month == $month;
            });

            $revenueData[] = ($paymentAmount ? $paymentAmount->total : 0) + ($invoiceAmount ? $invoiceAmount->total : 0);
        }

        // ============================================================
        // ACCRUAL vs ACTUAL REVENUE COMPARISON
        // ============================================================
        $accrualYear = $selectedYear;
        $yearStart = Carbon::create($accrualYear, 1, 1)->startOfDay();
        $yearEnd = Carbon::create($accrualYear, 12, 31)->endOfDay();

        $accrualData = array_fill(0, 12, 0);
        $accrualMonths = [];
        for ($m = 1; $m <= 12; $m++) {
            $accrualMonths[] = Carbon::createFromDate($accrualYear, $m, 1)->format('M Y');
        }

        // --- A. Kontrak Sewa: distribusi merata total_rental_value / jumlah bulan ---
        $sewaContracts = Contract::where('contract_type', 'sewa')
            ->whereNotNull('total_rental_value')
            ->where('total_rental_value', '>', 0)
            ->where('start_date', '<=', $yearEnd)
            ->where('end_date', '>=', $yearStart)
            ->get();

        foreach ($sewaContracts as $contract) {
            $start = Carbon::parse($contract->start_date);
            $end = Carbon::parse($contract->end_date);
            $totalMonths = max(1, $start->diffInMonths($end));
            $monthlyAccrual = $contract->total_rental_value / $totalMonths;

            for ($m = 1; $m <= 12; $m++) {
                $monthStart = Carbon::create($accrualYear, $m, 1)->startOfDay();
                $monthEnd = $monthStart->copy()->endOfMonth()->endOfDay();

                if ($start <= $monthEnd && $end >= $monthStart) {
                    $accrualData[$m - 1] += $monthlyAccrual;
                }
            }
        }

        // --- B. Amendments (Sewa): perlakukan sebagai entitas terpisah ---
        $sewaAmendments = Amendment::whereHas('contract', function ($q) {
            $q->where('contract_type', 'sewa');
        })
            ->whereNotNull('total_rental_value')
            ->where('total_rental_value', '>', 0)
            ->where('new_start_date', '<=', $yearEnd)
            ->where('new_end_date', '>=', $yearStart)
            ->get();

        foreach ($sewaAmendments as $amendment) {
            $start = Carbon::parse($amendment->new_start_date);
            $end = Carbon::parse($amendment->new_end_date);
            $totalMonths = max(1, $start->diffInMonths($end));
            $monthlyAccrual = $amendment->total_rental_value / $totalMonths;

            for ($m = 1; $m <= 12; $m++) {
                $monthStart = Carbon::create($accrualYear, $m, 1)->startOfDay();
                $monthEnd = $monthStart->copy()->endOfMonth()->endOfDay();

                if ($start <= $monthEnd && $end >= $monthStart) {
                    $accrualData[$m - 1] += $monthlyAccrual;
                }
            }
        }

        // --- C. Kontrak KSU: revenue dari Invoice, langsung masuk ke bulan invoice_date ---
        $ksuInvoiceTrend = Invoice::select(
            DB::raw('MONTH(invoice_date) as month'),
            DB::raw('SUM(amount) as total')
        )
            ->whereHas('tenant', function ($q) {
                $q->whereHas('contractHistory', function ($q2) {
                    $q2->where('contract_type', 'ksu');
                });
            })
            ->whereYear('invoice_date', $accrualYear)
            ->groupBy('month')
            ->pluck('total', 'month');

        for ($m = 1; $m <= 12; $m++) {
            $accrualData[$m - 1] += (float) ($ksuInvoiceTrend[$m] ?? 0);
        }

        // Round accrual data
        $accrualData = array_map(fn ($v) => round($v, 2), $accrualData);

        // --- Calculate Total Accrual for the Year ---
        $totalAccrualYtd = array_sum($accrualData);

        // --- D. Actual Revenue (manual input) ---
        $actualRevenues = ActualRevenue::where('year', $accrualYear)
            ->pluck('amount', 'month');

        $actualData = [];
        for ($m = 1; $m <= 12; $m++) {
            $actualData[] = (float) ($actualRevenues[$m] ?? 0);
        }

        // Available years for filter dropdown
        $availableYears = range($currentYear - 3, $currentYear + 1);

        // 3. Asset Status
        $availableAssets = $totalAssets - $rentedAssetIds;

        // 3b. Komposisi luas aset untuk pie chart (dipakai perusahaan / tenant / belum dipakai)
        $today = now()->startOfDay();
        $totalAreaSqm = (float) Asset::sum('area_sqm');
        $companyUsedArea = (float) Asset::sum('company_used_area_sqm');

        // Luas yang sedang disewa tenant — mirror logika Asset::getRentedAreaAttribute()
        $contractTenantArea = (float) DB::table('contract_assets')
            ->join('contracts', 'contracts.id', '=', 'contract_assets.contract_id')
            ->where('contracts.status', 'active')
            ->where('contracts.start_date', '<=', $today)
            ->where('contracts.end_date', '>=', $today)
            ->sum('contract_assets.rented_area_sqm');

        $amendmentTenantArea = (float) DB::table('amendment_assets')
            ->join('amendments', 'amendments.id', '=', 'amendment_assets.amendment_id')
            ->where('amendments.status', 'active')
            ->where('amendments.new_start_date', '<=', $today)
            ->where('amendments.new_end_date', '>=', $today)
            ->sum('amendment_assets.rented_area_sqm');

        $tenantUsedArea = $contractTenantArea + $amendmentTenantArea;
        $unusedArea = max(0, $totalAreaSqm - $companyUsedArea - $tenantUsedArea);

        $areaUsageData = [round($companyUsedArea, 2), round($tenantUsedArea, 2), round($unusedArea, 2)];

        // 4. Upcoming Contract Expirations (List)
        // Case 1: still within 60-day window → always show
        // Case 2: past expiry but workflow not done → still show until workflow completed
        $expiringContractsQuery = Contract::with(['tenant', 'workflow'])
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
                            ->orWhereHas('workflow', fn ($wq) => $wq->whereNull('completed_at'));
                    });
            })
            ->orderBy('end_date', 'asc');

        $totalExpiringContracts = $expiringContractsQuery->count();
        $expiringContracts = $expiringContractsQuery->limit(3)->get();

        $expiringAmendmentsQuery = Amendment::with(['contract.tenant'])
            ->where('status', 'active')
            ->where('new_end_date', '>=', now()->startOfDay())
            ->where('new_end_date', '<=', now()->addDays(60))
            ->orderBy('new_end_date', 'asc');

        $totalExpiringAmendments = $expiringAmendmentsQuery->count();
        $expiringAmendments = $expiringAmendmentsQuery->limit(3)->get();

        // Combined total for "See More" link (contracts + amendments)
        $totalExpiringItems = $totalExpiringContracts + $totalExpiringAmendments;

        // 4b. Pending Renewal Follow-ups (workflow selesai tapi belum buat kontrak/amandemen)
        $pendingRenewals = ContractWorkflow::with(['contract.tenant'])
            ->where('renewal_action', 'pending')
            ->orderBy('completed_at', 'asc')
            ->get();

        // 5. Recent Overdue Payments
        $totalOverduePayments = $overduePayments; // Already counted above (line 69)
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
            'activeSewaTenantsCount',
            'activeKsuTenantsCount',
            'totalActiveTenantsCount',
            'months',
            'revenueData',
            'rentedAssetIds',
            'availableAssets',
            'areaUsageData',
            'expiringContracts',
            'expiringAmendments',
            'pendingRenewals',
            'recentOverdue',
            'totalExpiringContracts',
            'totalExpiringItems',
            'totalOverduePayments',
            'unpaidInvoices',
            'accrualData',
            'actualData',
            'accrualMonths',
            'accrualYear',
            'availableYears',
            'totalAccrualYtd'
        ));
    }

    public function accrualDetails(Request $request)
    {
        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);

        $monthStart = Carbon::create($year, $month, 1)->startOfDay();
        $monthEnd = $monthStart->copy()->endOfMonth()->endOfDay();

        $details = [];

        // --- A. Kontrak Sewa ---
        $sewaContracts = Contract::with('tenant')
            ->where('contract_type', 'sewa')
            ->whereNotNull('total_rental_value')
            ->where('total_rental_value', '>', 0)
            ->where('start_date', '<=', $monthEnd)
            ->where('end_date', '>=', $monthStart)
            ->get();

        foreach ($sewaContracts as $contract) {
            $start = Carbon::parse($contract->start_date);
            $end = Carbon::parse($contract->end_date);
            $totalMonths = max(1, $start->diffInMonths($end));
            $monthlyAccrual = $contract->total_rental_value / $totalMonths;

            $details[] = [
                'type' => 'Kontrak Sewa',
                'tenant_name' => $contract->tenant->name ?? 'Unknown',
                'contract_number' => $contract->no_pks ?? $contract->no_bak ?? '-',
                'amount' => round($monthlyAccrual, 2),
            ];
        }

        // --- B. Amendments (Sewa) ---
        $sewaAmendments = Amendment::with('contract.tenant')
            ->whereHas('contract', function ($q) {
                $q->where('contract_type', 'sewa');
            })
            ->whereNotNull('total_rental_value')
            ->where('total_rental_value', '>', 0)
            ->where('new_start_date', '<=', $monthEnd)
            ->where('new_end_date', '>=', $monthStart)
            ->get();

        foreach ($sewaAmendments as $amendment) {
            $start = Carbon::parse($amendment->new_start_date);
            $end = Carbon::parse($amendment->new_end_date);
            $totalMonths = max(1, $start->diffInMonths($end));
            $monthlyAccrual = $amendment->total_rental_value / $totalMonths;

            $details[] = [
                'type' => 'Amandemen Sewa',
                'tenant_name' => $amendment->contract->tenant->name ?? 'Unknown',
                'contract_number' => $amendment->contract->no_pks ?? $amendment->contract->no_bak ?? '-',
                'amount' => round($monthlyAccrual, 2),
            ];
        }

        // --- C. Kontrak KSU (Invoices) ---
        $ksuInvoices = Invoice::with(['tenant'])
            ->whereHas('tenant', function ($q) {
                $q->whereHas('contractHistory', function ($q2) {
                    $q2->where('contract_type', 'ksu');
                });
            })
            ->whereYear('invoice_date', $year)
            ->whereMonth('invoice_date', $month)
            ->get();

        foreach ($ksuInvoices as $invoice) {
            $details[] = [
                'type' => 'Invoice KSU',
                'tenant_name' => $invoice->tenant->name ?? 'Unknown',
                'contract_number' => $invoice->invoice_number,
                'amount' => (float) $invoice->amount,
            ];
        }

        $total = array_sum(array_column($details, 'amount'));

        return response()->json([
            'year' => $year,
            'month' => $month,
            'month_name' => $monthStart->translatedFormat('F Y'),
            'details' => $details,
            'total' => $total,
        ]);
    }
}
