@extends('layouts.app')

@section('page_title', 'Import History')
@section('page_subtitle', 'Uploaded PDS files, row outcomes, and downloadable error reports')

@section('content')
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold uppercase">Import History</h1>
            <p class="text-sm font-semibold text-[#64748b]">Review upload status, row counts, and parsing issues from imported PDS files.</p>
        </div>
        <a href="{{ route('pds.upload') }}" class="btn-primary">Upload PDS File</a>
    </div>

    <section class="panel">
        <div class="panel-heading">Bulk Import History</div>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-sm">
                <thead class="bg-[#f8fafc]">
                    <tr>
                        <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">File</th>
                        <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">Status</th>
                        <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">Rows</th>
                        <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">Saved Record</th>
                        <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">Uploaded By</th>
                        <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">Date</th>
                        <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($imports as $import)
                        <tr>
                            <td class="border-b border-[#e8edf2] px-3 py-3">
                                <div class="font-semibold text-[#0f172a]">{{ $import->original_filename }}</div>
                                @if ($import->notes)
                                    <div class="mt-1 text-xs text-[#64748b]">{{ $import->notes }}</div>
                                @endif
                            </td>
                            <td class="border-b border-[#e8edf2] px-3 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $import->status === 'completed' ? 'bg-[#dcfce7] text-[#166534]' : ($import->status === 'failed' ? 'bg-red-50 text-red-700' : 'bg-[#ecfccb] text-[#3f6212]') }}">
                                    {{ ucfirst($import->status) }}
                                </span>
                            </td>
                            <td class="border-b border-[#e8edf2] px-3 py-3 text-sm text-[#334155]">
                                <div>Total: {{ $import->total_rows }}</div>
                                <div class="text-[#166534]">Success: {{ $import->success_rows }}</div>
                                <div class="text-red-600">Failed: {{ $import->failed_rows }}</div>
                            </td>
                            <td class="border-b border-[#e8edf2] px-3 py-3">
                                @if ($import->employee)
                                    <a href="{{ route('profile.show', $import->employee) }}" class="font-semibold text-[#15803d] underline underline-offset-4">
                                        {{ $import->employee->full_name }}
                                    </a>
                                @else
                                    <span class="text-[#94a3b8]">Not saved</span>
                                @endif
                            </td>
                            <td class="border-b border-[#e8edf2] px-3 py-3">{{ $import->creator?->name ?: $import->creator?->username ?: 'System' }}</td>
                            <td class="border-b border-[#e8edf2] px-3 py-3">{{ $import->created_at?->format('M d, Y h:i A') }}</td>
                            <td class="border-b border-[#e8edf2] px-3 py-3">
                                @if ($import->error_report_path)
                                    <a href="{{ route('admin.import-history.error-report', $import) }}" class="btn-secondary px-3 py-1">Download Error Report</a>
                                @else
                                    <span class="text-xs text-[#94a3b8]">No error report</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3 py-8 text-center font-semibold text-[#64748b]">No import history yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $imports->links() }}
        </div>
    </section>
@endsection
