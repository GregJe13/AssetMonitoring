<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display the activity log page.
     * Accessible by Manager and Admin.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $userFilter = $request->input('user_id');
        $actionFilter = $request->input('action');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = ActivityLog::with('user')
            ->whereHas('user', function ($q) {
                $q->where('role', 'worker');
            })
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('model_type', 'like', "%{$search}%");
            });
        }

        if ($userFilter) {
            $query->where('user_id', $userFilter);
        }

        if ($actionFilter) {
            $query->where('action', $actionFilter);
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $logs = $query->paginate(20)->withQueryString();

        // For filter dropdowns — only show worker users since we only log worker actions
        $users = User::where('role', 'worker')->orderBy('name')->get(['id', 'name']);
        $actions = ActivityLog::distinct()->pluck('action')->sort()->values();

        return view('activity-logs.index', compact('logs', 'users', 'actions'));
    }
}
