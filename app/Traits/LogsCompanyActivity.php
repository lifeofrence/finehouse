<?php

namespace App\Traits;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;

trait LogsCompanyActivity
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function tapActivity($activity, string $eventName)
    {
        $user = Auth::user();
        if ($user && $user->company_id) {
            $activity->company_id = $user->company_id;
        } elseif (isset($this->company_id)) {
            $activity->company_id = $this->company_id;
        }
    }
}
