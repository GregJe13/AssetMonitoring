<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\WorkflowController;
use App\Http\Controllers\AmendmentController;

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
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('tenants', TenantController::class);
    Route::get('assets/search', [AssetController::class, 'search'])->name('assets.search');
    Route::get('contracts/search', [ContractController::class, 'search'])->name('contracts.search');
    Route::get('contracts/assets-for-period', [ContractController::class, 'assetsForPeriod'])->name('contracts.assetsForPeriod');
    Route::resource('assets', AssetController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::post('invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.markPaid');
    Route::resource('contracts', ContractController::class);
    Route::get('contracts/{contract}/file/{type}', [ContractController::class, 'viewFile'])->name('contracts.file');
    Route::get('contracts/{contract}/print', [ContractController::class, 'print'])->name('contracts.print');
    Route::patch('contracts/{contract}/renewal-notes', [ContractController::class, 'updateRenewalNotes'])->name('contracts.updateRenewalNotes');
    Route::resource('payments', PaymentController::class);

    // Workflow routes
    Route::get('contracts/{contract}/workflow', [WorkflowController::class, 'show'])->name('workflow.show');
    Route::post('contracts/{contract}/workflow/start', [WorkflowController::class, 'start'])->name('workflow.start');
    Route::post('contracts/{contract}/workflow/advance', [WorkflowController::class, 'advance'])->name('workflow.advance');
    Route::post('contracts/{contract}/workflow/decide', [WorkflowController::class, 'decide'])->name('workflow.decide');
    Route::post('contracts/{contract}/workflow/upload', [WorkflowController::class, 'uploadEvidence'])->name('workflow.upload');
    Route::get('contracts/{contract}/workflow/renewal-choice', [WorkflowController::class, 'renewalChoice'])->name('workflow.renewalChoice');

    // Amendment routes
    Route::get('amendments/contracts-for-tenant/{tenant}', [AmendmentController::class, 'contractsForTenant'])->name('amendments.contractsForTenant');
    Route::get('amendments/{amendment}/file/{type}', [AmendmentController::class, 'viewFile'])->name('amendments.file');
    Route::resource('amendments', AmendmentController::class);

    // Example: Admin-only routes (uncomment when needed)
    // Route::middleware('role:admin')->group(function () {
    //     Route::resource('users', UserController::class);
    // });
});
