@extends('layouts.app')

@section('page_title', 'System Audit Logs')
@section('page_subtitle', 'Important auth, CRUD, and error activity with IP address tracking')

@section('page_actions')
    <form method="POST" action="{{ route('admin.audit-logs.clear') }}" onsubmit="return confirm('Delete all audit logs?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="rounded-[8px] border border-red-200 px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-50">Delete All Logs</button>
    </form>
@endsection

@section('content')
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold uppercase">System Audit Logs</h1>
            <p class="text-sm font-semibold text-[#64748b]">Monitor logins, signups, CRUD actions, failed requests, and IP addresses across the system.</p>
        </div>
    </div>

    <section class="panel">
        <form method="GET" action="{{ route('admin.audit-logs') }}" class="flex flex-col gap-3 border-b border-[#e8edf2] p-4 md:flex-row">
            <input type="text" name="q" value="{{ $query }}" class="form-input mt-0" placeholder="Search action, route, or IP">
            <select name="event_type" class="form-input mt-0 md:w-48">
                <option value="">All event types</option>
                <option value="auth" @selected($eventType === 'auth')>Auth</option>
                <option value="crud" @selected($eventType === 'crud')>CRUD</option>
                <option value="error" @selected($eventType === 'error')>Errors</option>
            </select>
            <button type="submit" class="btn-primary md:w-36">Filter</button>
        </form>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-sm">
                <thead class="bg-[#f8fafc]">
                    <tr>
                        <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">When</th>
                        <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">User</th>
                        <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">Role</th>
                        <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">Action</th>
                        <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">Route</th>
                        <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td class="border-b border-[#e8edf2] px-3 py-3">{{ $log->created_at?->format('M d, Y h:i:s A') }}</td>
                            <td class="border-b border-[#e8edf2] px-3 py-3">{{ $log->user?->name ?: $log->user?->email ?: $log->user?->username ?: 'Guest/System' }}</td>
                            <td class="border-b border-[#e8edf2] px-3 py-3 uppercase">{{ $log->user_role ?: 'guest' }}</td>
                            <td class="border-b border-[#e8edf2] px-3 py-3">
                                <div class="font-semibold text-[#0f172a]">{{ $log->action }}</div>
                                @if ($log->description)
                                    <div class="mt-1 text-xs text-[#64748b]">{{ $log->description }}</div>
                                @endif
                            </td>
                            <td class="border-b border-[#e8edf2] px-3 py-3">
                                <div>{{ $log->route_name ?: 'N/A' }}</div>
                                <div class="mt-1 text-xs text-[#64748b]">{{ $log->http_method }} / {{ $log->path }}</div>
                            </td>
                            <td class="border-b border-[#e8edf2] px-3 py-3">
                                <div>{{ $log->ip_address ?: 'N/A' }}</div>
                                <div class="mt-1 max-w-[260px] truncate text-xs text-[#64748b]">{{ $log->user_agent ?: 'N/A' }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-8 text-center font-semibold text-[#64748b]">No audit logs found yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $logs->links() }}
        </div>
    </section>
@endsection
