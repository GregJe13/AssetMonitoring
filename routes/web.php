<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\WorkflowController;
use App\Http\Controllers\ActualRevenueController;
use App\Http\Controllers\AmendmentController;
use App\Http\Controllers\ExpiringContractController;
use App\Http\Controllers\OverduePaymentController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard or login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Guest routes (only accessible when NOT logged in)
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::post('login', [LoginController::class, 'authenticate'])->name('login.authenticate');
    Route::get('register', [RegisterController::class, 'index'])->name('register');
    Route::post('register', [RegisterController::class, 'store'])->name('register.store');

    // Password Reset Routes
    Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.forgot');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

// Logout (accessible when logged in)
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Protected routes (require login)
Route::middleware('auth')->group(function () {

    // ─── All authenticated users (including guest) ──────────────
    // Guest can view dashboard (read-only) and assets list/detail
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/accrual-details', [DashboardController::class, 'accrualDetails'])->name('dashboard.accrual-details');
    Route::get('expiring-contracts', [ExpiringContractController::class, 'index'])->name('expiring-contracts.index');
    Route::get('overdue-payments', [OverduePaymentController::class, 'index'])->name('overdue-payments.index');
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('assets', [AssetController::class, 'index'])->name('assets.index');
    Route::get('assets/search', [AssetController::class, 'search'])->name('assets.search');
    // Note: assets/create MUST come before assets/{asset} to avoid wildcard conflict
    Route::get('assets/create', [AssetController::class, 'create'])->name('assets.create')->middleware('role:worker');
    Route::get('assets/{asset}', [AssetController::class, 'show'])->name('assets.show');

    // ─── Worker and above (full operational access) ─────────────
    Route::middleware('role:worker')->group(function () {
        // Tenants
        Route::get('tenants/search', [TenantController::class, 'search'])->name('tenants.search');
        Route::resource('tenants', TenantController::class);

        // Assets (edit, update, delete — index/show above for guest, create above for route order)
        Route::get('assets/{asset}/edit', [AssetController::class, 'edit'])->name('assets.edit');
        Route::post('assets', [AssetController::class, 'store'])->name('assets.store');
        Route::put('assets/{asset}', [AssetController::class, 'update'])->name('assets.update');
        Route::patch('assets/{asset}', [AssetController::class, 'update']);
        Route::delete('assets/{asset}', [AssetController::class, 'destroy'])->name('assets.destroy');

        // Contracts
        Route::get('contracts/search', [ContractController::class, 'search'])->name('contracts.search');
        Route::get('contracts/assets-for-period', [ContractController::class, 'assetsForPeriod'])->name('contracts.assetsForPeriod');
        Route::resource('contracts', ContractController::class);
        Route::get('contracts/{contract}/file/{type}', [ContractController::class, 'viewFile'])->name('contracts.file');
        Route::get('contracts/{contract}/print', [ContractController::class, 'print'])->name('contracts.print');
        Route::patch('contracts/{contract}/renewal-notes', [ContractController::class, 'updateRenewalNotes'])->name('contracts.updateRenewalNotes');

        // Invoices
        Route::resource('invoices', InvoiceController::class);
        Route::post('invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.markPaid');

        // Payments
        Route::resource('payments', PaymentController::class);

        // Workflow
        Route::get('contracts/{contract}/workflow', [WorkflowController::class, 'show'])->name('workflow.show');
        Route::post('contracts/{contract}/workflow/start', [WorkflowController::class, 'start'])->name('workflow.start');
        Route::post('contracts/{contract}/workflow/advance', [WorkflowController::class, 'advance'])->name('workflow.advance');
        Route::post('contracts/{contract}/workflow/decide', [WorkflowController::class, 'decide'])->name('workflow.decide');
        Route::post('contracts/{contract}/workflow/upload', [WorkflowController::class, 'uploadEvidence'])->name('workflow.upload');
        Route::get('contracts/{contract}/workflow/renewal-choice', [WorkflowController::class, 'renewalChoice'])->name('workflow.renewalChoice');

        // Amendments
        Route::get('amendments/contracts-for-tenant/{tenant}', [AmendmentController::class, 'contractsForTenant'])->name('amendments.contractsForTenant');
        Route::get('amendments/{amendment}/file/{type}', [AmendmentController::class, 'viewFile'])->name('amendments.file');
        Route::resource('amendments', AmendmentController::class);

        // Actual Revenue (manual input for accrual comparison)
        Route::post('actual-revenue', [ActualRevenueController::class, 'store'])->name('actual-revenue.store');
    });

    // ─── Manager and above ──────────────────────────────────────
    Route::middleware('role:manager')->group(function () {
        // Activity Log
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

        // User Management (Manager: can assign/remove worker role)
        Route::get('users', [UserManagementController::class, 'index'])->name('users.index');
        Route::patch('users/{user}/role', [UserManagementController::class, 'updateRole'])->name('users.updateRole');
    });

    // ─── Admin only ─────────────────────────────────────────────
    // Admin gets all manager routes above + additional admin-specific routes
    // (Admin-specific routes can be added here later, e.g. system settings)
});
