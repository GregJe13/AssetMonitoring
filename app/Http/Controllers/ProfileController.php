<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $role = strtolower((string) $user->role);

        $roleMeta = match ($role) {
            'admin' => [
                'label' => 'Administrator',
                'description' => 'Full access. Mengelola user, role, dan seluruh modul sistem.',
                'badge' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
                'panel' => 'from-indigo-600 via-indigo-500 to-sky-500',
                'tone' => 'admin',
            ],
            'manager' => [
                'label' => 'Manager',
                'description' => 'Akses operasional penuh, activity log, dan assign/remove role worker.',
                'badge' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                'panel' => 'from-emerald-600 via-teal-500 to-cyan-500',
                'tone' => 'manager',
            ],
            'worker' => [
                'label' => 'Worker',
                'description' => 'Akses operasional penuh untuk monitoring harian dan proses kontrak.',
                'badge' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                'panel' => 'from-amber-500 via-orange-500 to-rose-500',
                'tone' => 'worker',
            ],
            default => [
                'label' => 'Guest',
                'description' => 'Akses terbatas. Hanya dapat melihat dashboard dan daftar aset.',
                'badge' => 'bg-gray-100 text-gray-600 ring-gray-500/20',
                'panel' => 'from-gray-500 via-gray-400 to-slate-400',
                'tone' => 'guest',
            ],
        };

        $workspaceStats = [
            [
                'label' => 'Tenant',
                'value' => Tenant::count(),
                'caption' => 'mitra aktif dan historis',
            ],
            [
                'label' => 'Aset',
                'value' => Asset::count(),
                'caption' => 'aset dalam monitoring',
            ],
            [
                'label' => 'Kontrak aktif',
                'value' => Contract::where('status', 'active')->count(),
                'caption' => 'kerja sama yang berjalan',
            ],
            [
                'label' => 'Invoice unpaid',
                'value' => Invoice::where('status', 'unpaid')->count(),
                'caption' => 'tagihan belum lunas',
            ],
        ];

        $attentionCards = [
            [
                'label' => 'Pembayaran overdue',
                'value' => Payment::where('payment_status', 'overdue')->count(),
                'caption' => 'perlu follow up segera',
                'href' => route('payments.index'),
                'classes' => 'bg-red-50 text-red-700 ring-red-600/20',
                'tone' => 'danger',
            ],
            [
                'label' => 'Kontrak akan berakhir',
                'value' => Contract::whereBetween('end_date', [now()->startOfDay(), now()->addDays(30)])->count(),
                'caption' => 'dalam 30 hari ke depan',
                'href' => route('contracts.index'),
                'classes' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                'tone' => 'warning',
            ],
            [
                'label' => 'Invoice unpaid',
                'value' => Invoice::where('status', 'unpaid')->count(),
                'caption' => 'perlu penagihan / konfirmasi',
                'href' => route('invoices.index'),
                'classes' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
                'tone' => 'info',
            ],
        ];

        $quickLinks = [
            [
                'label' => 'Lihat Dashboard',
                'description' => 'Ringkasan performa dan alert utama.',
                'href' => route('dashboard'),
            ],
            [
                'label' => 'Kelola Kontrak',
                'description' => 'Pantau kontrak aktif dan masa berakhirnya.',
                'href' => route('contracts.index'),
            ],
            [
                'label' => 'Cek Invoice',
                'description' => 'Review tagihan unpaid dan status pembayaran.',
                'href' => route('invoices.index'),
            ],
        ];

        $accessModules = match ($role) {
            'admin' => ['Dashboard', 'Tenants', 'Assets', 'Contracts', 'Amendments', 'Payments', 'Invoice', 'Activity Log', 'User Management'],
            'manager' => ['Dashboard', 'Tenants', 'Assets', 'Contracts', 'Amendments', 'Payments', 'Invoice', 'Activity Log', 'User Management'],
            'worker' => ['Dashboard', 'Tenants', 'Assets', 'Contracts', 'Amendments', 'Payments', 'Invoice'],
            default => ['Dashboard', 'Assets'],
        };

        return view('profile.show', compact(
            'user',
            'roleMeta',
            'workspaceStats',
            'attentionCards',
            'quickLinks',
            'accessModules'
        ));
    }
}
