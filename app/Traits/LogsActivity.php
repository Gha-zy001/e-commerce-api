<?php

namespace App\Traits;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity as LogsActivityTrait;

/**
 * Trait to enable activity logging for models.
 *
 * Usage:
 * 1. Use this trait in your model
 * 2. Implement getActivitylogOptions() method to customize logging
 */
trait LogsActivity
{
    use LogsActivityTrait;

    /**
     * Get the activity log options for the model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty()
            ->logOnly(['*'])
            ->dontSubmitEmptyLogs()
            ->useLogName($this->getLogName())
            ->setDescriptionForEvent(
                fn ($eventName) => $this->getDescriptionForEvent($eventName)
            );
    }

    /**
     * Get the log name for this model.
     */
    protected function getLogName(): string
    {
        return class_basename($this);
    }

    /**
     * Get a custom description for the event.
     */
    protected function getDescriptionForEvent(string $eventName): string
    {
        $user = Auth::guard('admin')->user() ?? Auth::guard('customer')->user();
        $userName = $user ? ($user->name ?? $user->full_name ?? $user->email) : 'System';
        $modelName = class_basename($this);
        $modelId = $this->id ?? 'new';

        return match ($eventName) {
            'created' => "{$userName} created {$modelName} #{$modelId}",
            'updated' => "{$userName} updated {$modelName} #{$modelId}",
            'deleted' => "{$userName} deleted {$modelName} #{$modelId}",
            'restored' => "{$userName} restored {$modelName} #{$modelId}",
            default => "{$userName} performed {$eventName} on {$modelName} #{$modelId}",
        };
    }

    /**
     * Get the causer (who performed the action).
     */
    public function getCauser(): ?Authenticatable
    {
        return Auth::guard('admin')->user() ?? Auth::guard('customer')->user();
    }
}
