@extends('layouts.app')
@section('title', 'Audit Logs - VetPOS')
@section('content')
<h1 class="text-2xl font-bold mb-6">Audit Logs</h1>
<div class="bg-white rounded-lg border border-gray-200">
    <div class="p-4 border-b border-gray-200">
        <form method="GET" class="flex gap-2 flex-wrap">
            <select name="event" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">All Events</option>
                @foreach($events as $ev)
                    <option value="{{ $ev }}" {{ request('event') === $ev ? 'selected' : '' }}>{{ $ev }}</option>
                @endforeach
            </select>
            <button class="bg-gray-100 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">Filter</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="text-left px-4 py-2">Date</th>
                    <th class="text-left px-4 py-2">User</th>
                    <th class="text-left px-4 py-2">Event</th>
                    <th class="text-left px-4 py-2">Type</th>
                    <th class="text-left px-4 py-2">IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-gray-500">{{ $log->created_at->format('M d, g:i A') }}</td>
                    <td class="px-4 py-2">{{ $log->user->name ?? '-' }}</td>
                    <td class="px-4 py-2"><span class="text-xs px-2 py-0.5 rounded-full bg-gray-100">{{ $log->event }}</span></td>
                    <td class="px-4 py-2 text-gray-500 font-mono text-xs">{{ $log->auditable_type ? class_basename($log->auditable_type) . '#' . $log->auditable_id : '-' }}</td>
                    <td class="px-4 py-2 text-gray-400 text-xs">{{ $log->ip_address }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No logs.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $logs->links() }}</div>
</div>
@endsection
