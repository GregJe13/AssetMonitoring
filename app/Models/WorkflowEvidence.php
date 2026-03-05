<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowEvidence extends Model
{
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
}
