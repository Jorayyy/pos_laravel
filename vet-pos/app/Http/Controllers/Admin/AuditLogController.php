<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = AuditLog::query()
            ->when($request->event, fn ($q) => $q->where('event', $request->event))
            ->when($request->user_id, fn ($q) => $q->where('user_id', $request->user_id))
            ->when($request->auditable_type, fn ($q) => $q->where('auditable_type', $request->auditable_type))
            ->with('user')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $events = AuditLog::distinct()->pluck('event')->filter()->values();

        return view('admin.audit-logs.index', compact('logs', 'events'));
    }
}
