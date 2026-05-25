<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * Trait LogsActivity
 *
 * Automatically logs create, update, and delete actions to the activity_logs table.
 * Only logs actions performed by users with the 'worker' role.
 *
 * Usage: Add `use LogsActivity;` to any Eloquent model.
 */
trait LogsActivity
{
    /**
     * Boot the trait — register model event listeners.
     */
    public static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            static::logAction($model, 'created');
        });

        static::updated(function ($model) {
            static::logAction($model, 'updated');
        });

        static::deleted(function ($model) {
            static::logAction($model, 'deleted');
        });
    }

    /**
     * Log the action to the activity_logs table.
     */
    protected static function logAction($model, string $action): void
    {
        $user = Auth::user();

        // Only log actions from authenticated worker users
        if (!$user || $user->role !== 'worker') {
            return;
        }

        $description = static::buildDescription($model, $action);
        $properties = static::buildProperties($model, $action);

        ActivityLog::create([
            'user_id'    => $user->id,
            'action'     => $action,
            'model_type' => get_class($model),
            'model_id'   => $model->getKey(),
            'description' => $description,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'created_at' => now(),
        ]);
    }

    /**
     * Build a human-readable description of the action.
     */
    protected static function buildDescription($model, string $action): string
    {
        $modelName = class_basename($model);
        $identifier = static::getModelIdentifier($model);

        return match ($action) {
            'created' => "Membuat {$modelName} baru: {$identifier}",
            'updated' => "Mengubah {$modelName}: {$identifier}",
            'deleted' => "Menghapus {$modelName}: {$identifier}",
            default   => "{$action} {$modelName}: {$identifier}",
        };
    }

    /**
     * Get a human-readable identifier for the model.
     * Override this method in your model for custom identifiers.
     */
    protected static function getModelIdentifier($model): string
    {
        // Try common identifier fields
        if (isset($model->name)) {
            return $model->name;
        }
        if (isset($model->no_pks)) {
            return $model->no_pks;
        }
        if (isset($model->no_bak)) {
            return $model->no_bak;
        }
        if (isset($model->invoice_number)) {
            return $model->invoice_number;
        }
        if (isset($model->no_amendment)) {
            return $model->no_amendment;
        }
        if (isset($model->id_gedung)) {
            return $model->id_gedung;
        }

        return "#{$model->getKey()}";
    }

    /**
     * Build the properties (old/new values) for the log entry.
     */
    protected static function buildProperties($model, string $action): ?array
    {
        return match ($action) {
            'created' => [
                'new' => $model->getAttributes(),
            ],
            'updated' => [
                'old' => $model->getOriginal(),
                'new' => $model->getChanges(),
            ],
            'deleted' => [
                'old' => $model->getOriginal(),
            ],
            default => null,
        };
    }
}
