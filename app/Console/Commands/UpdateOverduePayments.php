<?php

namespace App\Console\Commands;

use App\Models\Payment;
use Illuminate\Console\Command;

class UpdateOverduePayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:update-overdue 
                            {--dry-run : Show which payments would be updated without actually updating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update payment status to overdue for payments past their due date';

    /**
     * Execute the console command.
     * 
     * Command ini harus dijalankan daily via Laravel Scheduler.
     * Menandai semua pembayaran yang sudah jatuh tempo tapi masih pending sebagai overdue.
     */
    public function handle(): int
    {
        $this->info('Checking for overdue payments...');

        // Ambil semua payments yang seharusnya overdue
        $overduePayments = Payment::shouldBeOverdue()->get();

        if ($overduePayments->isEmpty()) {
            $this->info('No overdue payments found.');
            return Command::SUCCESS;
        }

        $this->info("Found {$overduePayments->count()} payment(s) to mark as overdue.");

        if ($this->option('dry-run')) {
            $this->warn('DRY RUN - No changes will be made.');
            $this->table(
                ['ID', 'Contract #', 'Tenant', 'Due Date', 'Amount Due'],
                $overduePayments->map(function ($payment) {
                    return [
                        $payment->id,
                        $payment->contract->no_pks ?? $payment->contract->no_bak ?? 'N/A',
                        $payment->contract->tenant->name,
                        $payment->due_date->format('Y-m-d'),
                        number_format($payment->amount_due, 2),
                    ];
                })
            );
            return Command::SUCCESS;
        }

        // Update status ke overdue
        $updatedCount = Payment::shouldBeOverdue()
            ->update(['payment_status' => 'overdue']);

        $this->info("Successfully marked {$updatedCount} payment(s) as overdue.");

        // Tampilkan detail
        $this->table(
            ['ID', 'Contract #', 'Tenant', 'Due Date', 'Amount Due'],
            $overduePayments->map(function ($payment) {
                return [
                    $payment->id,
                    $payment->contract->no_pks ?? $payment->contract->no_bak ?? 'N/A',
                    $payment->contract->tenant->name,
                    $payment->due_date->format('Y-m-d'),
                    number_format($payment->amount_due, 2),
                ];
            })
        );

        return Command::SUCCESS;
    }
}
