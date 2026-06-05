@extends('layouts.app')

@section('page_title', 'Staff Directory')
@section('page_subtitle', 'Complete staff list for ' . $office)

@section('content')
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold uppercase">{{ $office }}</h1>
            <p class="text-sm font-semibold text-[#64748b]">Total Employees: {{ $employees->count() }}</p>
        </div>
        <a href="{{ route('user.offices') }}" class="btn-secondary flex items-center gap-2">
            <svg viewBox="0 0 16 16" class="h-3.5 w-3.5 fill-none stroke-current" aria-hidden="true">
                <path d="M10 13l-5-5 5-5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Back to Offices
        </a>
    </div>

    <div class="panel">
        <div class="panel-heading">Employee List</div>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-sm">
                <thead class="bg-[#f8fafc]">
                    <tr>
                        <th class="border-b border-[#e8edf2] px-4 py-3 text-left uppercase">Name</th>
                        <th class="border-b border-[#e8edf2] px-4 py-3 text-left uppercase">Position</th>
                        <th class="border-b border-[#e8edf2] px-4 py-3 text-left uppercase text-center">Sex</th>
                        <th class="border-b border-[#e8edf2] px-4 py-3 text-left uppercase text-center">Employment</th>
                        <th class="border-b border-[#e8edf2] px-4 py-3 text-left uppercase">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $emp)
                        <tr class="hover:bg-[#f8fafc] transition-colors">
                            <td class="border-b border-[#e8edf2] px-4 py-4 font-semibold text-[#0f172a]">{{ $emp->full_name }}</td>
                            <td class="border-b border-[#e8edf2] px-4 py-4 text-[#64748b]">{{ $emp->position_title ?: 'N/A' }}</td>
                            <td class="border-b border-[#e8edf2] px-4 py-4 text-center">
                                @if ($emp->sex_at_birth === 'Male')
                                    <span class="inline-flex rounded-full bg-[#dbeafe] px-2.5 py-0.5 text-[11px] font-bold text-[#1d4ed8]">M</span>
                                @elseif ($emp->sex_at_birth === 'Female')
                                    <span class="inline-flex rounded-full bg-[#fce7f3] px-2.5 py-0.5 text-[11px] font-bold text-[#be185d]">F</span>
                                @else
                                    <span class="text-[#94a3b8]">-</span>
                                @endif
                            </td>
                            <td class="border-b border-[#e8edf2] px-4 py-4 text-center">
                                <span class="text-[11px] font-medium text-[#64748b]">{{ $emp->job_order ?: 'Permanent' }}</span>
                            </td>
                            <td class="border-b border-[#e8edf2] px-4 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @if ((int)$emp->created_by === (int)auth()->id())
                                        @if ((int)$emp->user_id === (int)auth()->id())
                                            <a href="{{ route('user.pds.form') }}" class="btn-secondary px-2.5 py-1 text-xs">Edit</a>
                                        @else
                                            <a href="{{ route('user.records.edit', $emp) }}" class="btn-secondary px-2.5 py-1 text-xs">Edit</a>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-[#64748b]">No active employee records found for this office.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
