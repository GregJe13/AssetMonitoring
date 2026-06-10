<?php

namespace App\Http\Controllers;

use App\Models\Amendment;
use App\Models\Contract;
use Illuminate\Http\Request;

class ExpiringContractController extends Controller
{
    public function index()
    {
        // Count "Expiring Soon" — within 60-day window but NOT yet expired
        $expiringSoonCount = Contract::where('end_date', '>=', now()->startOfDay())
            ->where('end_date', '<=', now()->addDays(60))
            ->count();

        // Count "Already Expired" — past expiry but workflow not completed
        $alreadyExpiredCount = Contract::where('end_date', '<', now()->startOfDay())
            ->where(function ($q) {
                $q->whereDoesntHave('workflow')
                    ->orWhereHas('workflow', fn($wq) => $wq->whereNull('completed_at'));
            })
            ->count();

        // Paginated list (both cases combined)
        $expiringContracts = Contract::with(['tenant', 'workflow'])
            ->where(function ($q) {
                $q->where('end_date', '>=', now()->startOfDay())
                    ->where('end_date', '<=', now()->addDays(60));
            })
            ->orWhere(function ($q) {
                $q->where('end_date', '<', now()->startOfDay())
                    ->where(function ($q2) {
                    $q2->whereDoesntHave('workflow')
                        ->orWhereHas('workflow', fn($wq) => $wq->whereNull('completed_at'));
                });
            })
            ->orderBy('end_date', 'asc')
            ->paginate(15);

        $expiringAmendments = Amendment::with(['contract.tenant'])
            ->where('status', 'active')
            ->where('new_end_date', '>=', now()->startOfDay())
            ->where('new_end_date', '<=', now()->addDays(60))
            ->orderBy('new_end_date', 'asc')
            ->get();

        return view('expiring-contracts.index', compact(
            'expiringContracts',
            'expiringAmendments',
            'expiringSoonCount',
            'alreadyExpiredCount'
        ));
    }
}
