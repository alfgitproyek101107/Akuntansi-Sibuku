<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogObserver
{
    /**
     * Track original values for updates
     */
    private static array $originalValues = [];

    /**
     * Handle the Model "creating" event.
     */
    public function creating(Model $model): void
    {
        // Store original values for comparison
        self::$originalValues[$model->getKey()] = $model->getOriginal();
    }

    /**
     * Handle the Model "created" event.
     */
    public function created(Model $model): void
    {
        // Skip logging for ActivityLog itself to prevent infinite loops
        if ($model instanceof ActivityLog) {
            return;
        }

        // Skip logging for certain models in demo mode
        if ($this->shouldSkipLogging($model)) {
            return;
        }

        ActivityLog::logModelChange($model, 'created', [], $model->toArray());
    }

    /**
     * Handle the Model "updating" event.
     */
    public function updating(Model $model): void
    {
        // Store original values before update
        self::$originalValues[$model->getKey()] = $model->getOriginal();
    }

    /**
     * Handle the Model "updated" event.
     */
    public function updated(Model $model): void
    {
        // Skip logging for ActivityLog itself to prevent infinite loops
        if ($model instanceof ActivityLog) {
            return;
        }

        // Skip logging for certain models in demo mode
        if ($this->shouldSkipLogging($model)) {
            return;
        }

        $originalValues = self::$originalValues[$model->getKey()] ?? [];
        $newValues = $model->toArray();

        // Only log if there are actual changes
        if ($this->hasChanges($originalValues, $newValues)) {
            ActivityLog::logModelChange($model, 'updated', $originalValues, $newValues);
        }

        // Clean up stored original values
        unset(self::$originalValues[$model->getKey()]);
    }

    /**
     * Handle the Model "deleting" event.
     */
    public function deleting(Model $model): void
    {
        // Store original values before deletion
        self::$originalValues[$model->getKey()] = $model->toArray();
    }

    /**
     * Handle the Model "deleted" event.
     */
    public function deleted(Model $model): void
    {
        // Skip logging for ActivityLog itself to prevent infinite loops
        if ($model instanceof ActivityLog) {
            return;
        }

        // Skip logging for certain models in demo mode
        if ($this->shouldSkipLogging($model)) {
            return;
        }

        $originalValues = self::$originalValues[$model->getKey()] ?? [];
        ActivityLog::logModelChange($model, 'deleted', $originalValues, []);

        // Clean up stored original values
        unset(self::$originalValues[$model->getKey()]);
    }

    /**
     * Handle the Model "restoring" event.
     */
    public function restoring(Model $model): void
    {
        // Log restoration if model supports soft deletes
        if (method_exists($model, 'trashed') && $model->trashed()) {
            ActivityLog::logModelChange($model, 'restored', [], $model->toArray());
        }
    }

    /**
     * Handle the Model "forceDeleted" event.
     */
    public function forceDeleted(Model $model): void
    {
        // Log permanent deletion
        ActivityLog::logModelChange($model, 'force_deleted', $model->toArray(), []);
    }

    /**
     * Check if logging should be skipped for this model
     */
    private function shouldSkipLogging(Model $model): bool
    {
        // Skip logging in demo mode for certain sensitive operations
        if (auth()->check() && auth()->user()->demo_mode) {
            // Skip logging for frequent operations in demo mode to reduce noise
            $skipModels = [
                \App\Models\ActivityLog::class,
                // Add other models to skip if needed
            ];

            if (in_array(get_class($model), $skipModels)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if there are actual changes between old and new values
     */
    private function hasChanges(array $oldValues, array $newValues): bool
    {
        // Remove timestamps and other auto-updated fields from comparison
        $excludeFields = ['updated_at', 'created_at'];

        $filteredOld = array_diff_key($oldValues, array_flip($excludeFields));
        $filteredNew = array_diff_key($newValues, array_flip($excludeFields));

        return $filteredOld != $filteredNew;
    }
}