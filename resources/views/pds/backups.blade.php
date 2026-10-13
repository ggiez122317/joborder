@extends('layouts.app')

@section('content')
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold uppercase">Backup / Trash</h1>
            <p class="text-sm font-semibold text-[#64748b]">
                Deleted PDS records are kept here. Restore them or delete permanently.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('records.index') }}" class="btn-secondary">Back to View Records</a>
        </div>
    </div>

    <section class="panel">
        <form method="GET" action="{{ route('backups.index') }}" class="flex flex-col gap-3 border-b border-[#1E3A8A] bg-white p-4 md:flex-row">
            <input name="q" value="{{ $query }}" class="form-input mt-0" placeholder="Search deleted records">
            <button type="submit" class="btn-primary md:w-40">Search</button>
            <a href="{{ route('backups.index') }}" class="btn-secondary md:w-32">Clear</a>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-sm">
                <thead class="bg-[#F3F4F6]">
                    <tr>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Full Name</th>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Job Order</th>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Position Title</th>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Office</th>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Deleted At</th>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr>
                            <td class="border-b border-[#1E3A8A] px-3 py-2 font-semibold text-[#003000]">
                                {{ $employee->full_name }}
                            </td>
                            <td class="border-b border-[#1E3A8A] px-3 py-2">{{ $employee->job_order ?: 'N/A' }}</td>
                            <td class="border-b border-[#1E3A8A] px-3 py-2">{{ $employee->position_title ?: 'N/A' }}</td>
                            <td class="border-b border-[#1E3A8A] px-3 py-2">{{ $employee->office ?: 'N/A' }}</td>
                            <td class="border-b border-[#1E3A8A] px-3 py-2 text-xs text-[#64748b]">{{ $employee->deleted_at?->format('M d, Y h:i A') }}</td>
                            <td class="border-b border-[#1E3A8A] px-3 py-2">
                                <div class="flex flex-wrap gap-2">
                                    <form method="POST" action="{{ route('backups.restore', $employee->id) }}">
                                        @csrf
                                        <button type="submit" class="btn-primary px-3 py-1">Restore</button>
                                    </form>
                                    <form method="POST" action="{{ route('backups.destroy', $employee->id) }}" onsubmit="return confirm('Permanently delete this record? This cannot be undone.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="rounded-lg bg-[#fef2f2] px-3 py-1 text-xs font-semibold text-[#dc2626] hover:bg-[#fecaca]">Delete Forever</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-6 text-center font-semibold">No deleted records. Trash is empty.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $employees->links() }}
        </div>
    </section>
@endsection
