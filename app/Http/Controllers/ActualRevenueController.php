<?php

namespace App\Http\Controllers;

use App\Models\ActualRevenue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActualRevenueController extends Controller
{
    /**
     * Store or update actual revenue for a given month/year (upsert).
     * 
     * Jika record untuk bulan+tahun sudah ada, akan di-update.
     * Jika belum ada, akan dibuat baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'year'   => 'required|integer|min:2000|max:2099',
            'month'  => 'required|integer|min:1|max:12',
            'amount' => 'required|numeric|min:0',
            'notes'  => 'nullable|string|max:500',
        ]);

        $actualRevenue = ActualRevenue::updateOrCreate(
            [
                'year'  => $validated['year'],
                'month' => $validated['month'],
            ],
            [
                'amount'     => $validated['amount'],
                'notes'      => $validated['notes'] ?? null,
                'created_by' => Auth::id(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Nilai aktual berhasil disimpan.',
            'data'    => $actualRevenue,
        ]);
    }
}
