<?php

namespace App\Http\Controllers\Admin;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

trait AuditLogHelper
{
    public static function log(string $event, Model $model, ?array $old, ?array $new): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'event' => $event,
            'auditable_type' => get_class($model),
            'auditable_id' => $model->id,
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => request()->ip(),
        ]);
    }

    public static function logGlobal(string $event, ?array $old, ?array $new): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'event' => $event,
            'auditable_type' => null,
            'auditable_id' => null,
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => request()->ip(),
        ]);
    }
}
