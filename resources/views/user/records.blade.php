@extends('layouts.app')

@section('page_title', 'My Records')
@section('page_subtitle', 'PDS records you have created for other employees')

@section('page_actions')
    <a href="{{ route('user.records.create') }}" class="btn-primary">Add New PDS</a>
@endsection

@section('content')
    <div class="panel">
        <div class="panel-heading">PDS Records</div>
        <div class="p-4">
            <form method="GET" action="{{ route('user.records') }}" class="mb-4 flex flex-wrap items-center gap-3">
                <input type="text" name="q" value="{{ $query }}" placeholder="Search by name, office, or position..." class="form-input flex-1">
                <button type="submit" class="btn-primary px-5 py-2">Search</button>
                @if ($query !== '')
                    <a href="{{ route('user.records') }}" class="btn-secondary px-3 py-2">Clear</a>
                @endif
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-sm">
                <thead class="bg-[#F3F4F6]">
                    <tr>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Full Name</th>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Job Order</th>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Position Title</th>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Office</th>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Sex</th>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Status</th>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Created</th>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr class="{{ !$record->is_active ? 'opacity-60' : '' }}">
                            <td class="border-b border-[#1E3A8A] px-3 py-2 font-semibold">
                                {{ $record->full_name }}
                            </td>
                            <td class="border-b border-[#1E3A8A] px-3 py-2">{{ $record->job_order ?: 'N/A' }}</td>
                            <td class="border-b border-[#1E3A8A] px-3 py-2">{{ $record->position_title ?: 'N/A' }}</td>
                            <td class="border-b border-[#1E3A8A] px-3 py-2">{{ $record->office ?: 'N/A' }}</td>
                            <td class="border-b border-[#1E3A8A] px-3 py-2">{{ $record->sex_at_birth ?: 'N/A' }}</td>
                            <td class="border-b border-[#1E3A8A] px-3 py-2">
                                @if ($record->is_active)
                                    <span class="inline-flex rounded-full bg-[#dcfce7] px-2.5 py-0.5 text-[11px] font-semibold uppercase text-[#166534]">Active</span>
                                @else
                                    <span class="inline-flex rounded-full bg-[#fee2e2] px-2.5 py-0.5 text-[11px] font-semibold uppercase text-[#991b1b]">Inactive</span>
                                @endif
                            </td>
                            <td class="border-b border-[#1E3A8A] px-3 py-2 text-xs text-[#64748b]">{{ $record->created_at?->format('M d, Y') }}</td>
                            <td class="border-b border-[#1E3A8A] px-3 py-2">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('user.records.show', $record) }}" class="btn-primary px-3 py-1">View</a>
                                    <a href="{{ route('user.records.edit', $record) }}" class="btn-secondary px-3 py-1">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-3 py-8 text-center">
                                <div class="text-sm font-semibold text-[#64748b]">No records yet.</div>
                                <p class="mt-1 text-xs text-[#94a3b8]">Click "Add New PDS" to create your first record.</p>
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
