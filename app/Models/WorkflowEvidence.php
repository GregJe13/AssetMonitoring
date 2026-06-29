<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowEvidence extends Model
{
    use \App\Traits\LogsActivity;

    protected $table = 'workflow_evidence';

    protected $fillable = [
        'workflow_id',
        'step',
        'file_path',
        'original_name',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(ContractWorkflow::class, 'workflow_id');
    }

    /**
     * Override activity log description.
     */
    protected static function buildDescription($model, string $action): string
    {
        if ($action === 'created') {
            $stepKey = $model->step;
            $stepLabel = ContractWorkflow::STEPS[$stepKey]['label'] ?? $stepKey;
            return "Mengupload dokumen {$stepLabel}";
        }
        
        return "{$action} dokumen bukti";
    }
}
