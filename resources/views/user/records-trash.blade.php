@extends('layouts.app')

@section('page_title', 'Trash / Backup')
@section('page_subtitle', 'Deleted records you can restore or permanently remove')

@section('page_actions')
    <a href="{{ route('user.records') }}" class="btn-secondary">Back to My Records</a>
@endsection

@section('content')
    <div class="panel">
        <div class="panel-heading">Backup / Trash</div>
        <div class="p-4">
            <form method="GET" action="{{ route('user.records.trash') }}" class="mb-4 flex flex-wrap items-center gap-3">
                <input type="text" name="q" value="{{ $query }}" placeholder="Search deleted records..." class="form-input flex-1">
                <button type="submit" class="btn-primary px-5 py-2">Search</button>
                @if ($query !== '')
                    <a href="{{ route('user.records.trash') }}" class="btn-secondary px-3 py-2">Clear</a>
                @endif
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-sm">
                <thead class="bg-[#F3F4F6]">
                    <tr>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Full Name</th>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Position Title</th>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Office</th>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Deleted At</th>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td class="border-b border-[#1E3A8A] px-3 py-2 font-semibold text-[#14532d]">
                                {{ $record->full_name }}
                            </td>
                            <td class="border-b border-[#1E3A8A] px-3 py-2">{{ $record->position_title ?: 'N/A' }}</td>
                            <td class="border-b border-[#1E3A8A] px-3 py-2">{{ $record->office ?: 'N/A' }}</td>
                            <td class="border-b border-[#1E3A8A] px-3 py-2 text-xs text-[#64748b]">{{ $record->deleted_at?->format('M d, Y h:i A') }}</td>
                            <td class="border-b border-[#1E3A8A] px-3 py-2">
                                <div class="flex flex-wrap gap-2">
                                    <form method="POST" action="{{ route('user.records.restore', $record->id) }}">
                                        @csrf
                                        <button type="submit" class="btn-primary px-3 py-1">Restore</button>
                                    </form>
                                    <form method="POST" action="{{ route('user.records.force-destroy', $record->id) }}" onsubmit="return confirm('Permanently delete this record? This cannot be undone.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="rounded-lg bg-[#fef2f2] px-3 py-1 text-xs font-semibold text-[#dc2626] hover:bg-[#fecaca]">Delete Forever</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-8 text-center">
                                <div class="text-sm font-semibold text-[#64748b]">Trash is empty.</div>
                                <p class="mt-1 text-xs text-[#94a3b8]">Deleted records will appear here for restore.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($records->hasPages())
            <div class="border-t border-[#e8edf2] px-4 py-3">
                {{ $records->links() }}
            </div>
        @endif
    </div>
@endsection
