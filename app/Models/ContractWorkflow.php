<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\LogsActivity;

class ContractWorkflow extends Model
{
    use LogsActivity;
    /**
     * All possible workflow steps.
     * Steps with branch = null are common (before decision).
     * Steps with branch = 'A' or 'B' are branch-specific.
     */
    const STEPS = [
        'confirmation_sent' => ['order' => 1, 'label' => 'Surat Konfirmasi', 'branch' => null, 'file_required' => true],
        'waiting_response' => ['order' => 2, 'label' => 'Menunggu Jawaban', 'branch' => null, 'file_required' => true],
        // Branch A — Akhir Kontrak
        'response_end' => ['order' => 3, 'label' => 'Jawaban: Akhir', 'branch' => 'A', 'file_required' => false],
        'closed' => ['order' => 4, 'label' => 'Closed Contract', 'branch' => 'A', 'file_required' => false],
        // Branch B — Perpanjangan
        'response_renew' => ['order' => 3, 'label' => 'Jawaban: Perpanjangan', 'branch' => 'B', 'file_required' => false],
        'draft_bak' => ['order' => 4, 'label' => 'Draft BAK', 'branch' => 'B', 'file_required' => true],
        'sign_bak' => ['order' => 5, 'label' => 'Sign BAK', 'branch' => 'B', 'file_required' => true],
        'renewed' => ['order' => 6, 'label' => 'Perpanjangan Kontrak', 'branch' => 'B', 'file_required' => false],
    ];

    protected $fillable = [
        'contract_id',
        'current_step',
        'branch',
        'notes',
        'started_at',
        'decided_at',
        'completed_at',
        'renewal_action',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'decided_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // ── Relationships ──

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function evidence(): HasMany
    {
        return $this->hasMany(WorkflowEvidence::class, 'workflow_id');
    }

    // ── Helpers ──

    /**
     * Get the ordered step sequence based on the current branch.
     * Before branch is decided, returns common steps + placeholder.
     */
    public function getSequence(): array
    {
        $steps = [];

        foreach (self::STEPS as $key => $step) {
            // Include common steps (branch = null)
            if ($step['branch'] === null) {
                $steps[] = array_merge($step, ['key' => $key]);
            }
            // Include branch-specific steps if branch is decided
            elseif ($this->branch !== null && $step['branch'] === $this->branch) {
                $steps[] = array_merge($step, ['key' => $key]);
            }
        }

        // Sort by order
        usort($steps, fn($a, $b) => $a['order'] <=> $b['order']);

        return $steps;
    }

    /**
     * Get progress info: which steps are completed, current, and upcoming.
     */
    public function getProgress(): array
    {
        $sequence = $this->getSequence();
        $currentFound = false;
        $result = [];

        foreach ($sequence as $step) {
            if ($step['key'] === $this->current_step) {
                $step['status'] = 'current';
                $currentFound = true;
            } elseif (!$currentFound) {
                $step['status'] = 'completed';
            } else {
                $step['status'] = 'upcoming';
            }

            // Attach evidence if any
            $step['evidence'] = $this->evidence->where('step', $step['key'])->first();

            $result[] = $step;
        }

        return $result;
    }

    /**
     * Check if the current step has required evidence uploaded.
     */
    public function canAdvance(): bool
    {
        $currentStepDef = self::STEPS[$this->current_step] ?? null;

        if (!$currentStepDef) {
            return false;
        }

        // If file not required, can always advance
        if (!$currentStepDef['file_required']) {
            return true;
        }

        // Check if evidence exists for current step
        return $this->evidence()->where('step', $this->current_step)->exists();
    }

    /**
     * Get the next step key in the sequence.
     */
    public function getNextStepKey(): ?string
    {
        $sequence = $this->getSequence();

        for ($i = 0; $i < count($sequence); $i++) {
            if ($sequence[$i]['key'] === $this->current_step && isset($sequence[$i + 1])) {
                return $sequence[$i + 1]['key'];
            }
        }

        return null;
    }

    /**
     * Check if the workflow is at the decision point (step 2, no branch yet).
     */
    public function isAtDecisionPoint(): bool
    {
        return $this->current_step === 'waiting_response' && $this->branch === null;
    }

    /**
     * Check if this is the last step.
     */
    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    /**
     * Check if current step is the final step in the sequence.
     */
    public function isLastStep(): bool
    {
        return $this->getNextStepKey() === null;
    }

    /**
     * Get current step label.
     */
    public function getCurrentStepLabel(): string
    {
        return self::STEPS[$this->current_step]['label'] ?? $this->current_step;
    }

    /**
     * Override activity log description.
     */
    protected static function buildDescription($model, string $action): string
    {
        $tenantName = $model->contract->tenant->name ?? 'Unknown Tenant';

        if ($action === 'created') {
            return "Memulai sop akhir kontrak milik '{$tenantName}'";
        }

        if ($action === 'updated') {
            $changes = $model->getChanges();

            // Prioritaskan cek branch jika ditekan tombol akhir/perpanjangan
            if (isset($changes['branch'])) {
                if ($changes['branch'] === 'A') {
                    return "Memutuskan untuk akhir kontrak pada {$tenantName}";
                } elseif ($changes['branch'] === 'B') {
                    return "Memutuskan untuk memperpanjang kontrak {$tenantName}";
                }
            }

            // Jika hanya step yang berubah
            if (isset($changes['current_step'])) {
                $stepKey = $changes['current_step'];
                $stepLabel = self::STEPS[$stepKey]['label'] ?? $stepKey;
                return "Melanjutkan ke step {$stepLabel}";
            }

            return "Memperbarui workflow kontrak {$tenantName}";
        }

        if ($action === 'deleted') {
            return "Menghapus workflow kontrak {$tenantName}";
        }

        return "{$action} workflow kontrak {$tenantName}";
    }
}
