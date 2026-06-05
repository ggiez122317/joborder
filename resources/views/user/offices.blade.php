@extends('layouts.app')

@section('page_title', 'Offices')
@section('page_subtitle', 'Active employee headcount per office with gender breakdown')

@section('content')
    <div class="mb-4 grid gap-3 md:grid-cols-3">
        <div class="panel p-4">
            <div class="text-[10px] font-medium uppercase tracking-[0.08em] text-[#94a3b8]">Total Offices</div>
            <div class="mt-2 text-2xl font-bold text-[#0f172a]">{{ number_format($totalOffices) }}</div>
            <div class="mt-1 text-sm text-[#64748b]">Offices with active employee records.</div>
        </div>
        <div class="panel p-4">
            <div class="text-[10px] font-medium uppercase tracking-[0.08em] text-[#94a3b8]">Active Employees</div>
            <div class="mt-2 text-2xl font-bold text-[#0f172a]">{{ number_format($totalEmployees) }}</div>
            <div class="mt-1 text-sm text-[#64748b]">Total active employees across all offices.</div>
        </div>
        <div class="panel p-4">
            <div class="text-[10px] font-medium uppercase tracking-[0.08em] text-[#94a3b8]">Gender Breakdown</div>
            <div class="mt-2 flex items-baseline gap-3">
                <span class="text-2xl font-bold text-[#1d4ed8]">{{ $totalMale }}</span>
                <span class="text-xs font-semibold uppercase text-[#64748b]">Male</span>
                <span class="text-[#cbd5e1]">|</span>
                <span class="text-2xl font-bold text-[#be185d]">{{ $totalFemale }}</span>
                <span class="text-xs font-semibold uppercase text-[#64748b]">Female</span>
            </div>
            <div class="mt-1 text-sm text-[#64748b]">Active employees by gender.</div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-heading">Office List With Employee Count</div>
        <div class="p-4">
            @if ($officeDirectory->isEmpty())
                <div class="rounded-[10px] border border-dashed border-[#e8edf2] px-4 py-6 text-sm text-[#64748b]">
                    No office data available yet.
                </div>
            @else
                <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($officeDirectory as $entry)
                        <div class="rounded-[10px] border border-[#e8edf2] bg-white px-4 py-3 transition hover:border-[#16a34a] hover:shadow-sm">
                            <div class="flex items-center justify-between gap-3">
                                <div class="text-sm font-semibold uppercase text-[#0f172a]">{{ $entry['office'] }}</div>
                                <span class="inline-flex min-w-9 items-center justify-center rounded-full bg-[#f0fdf4] px-2.5 py-1 text-xs font-bold text-[#15803d]">
                                    {{ $entry['total'] }}
                                </span>
                            </div>
                            <div class="mt-2 flex items-center gap-3">
                                <span class="inline-flex items-center gap-1 rounded-full bg-[#dbeafe] px-2 py-0.5 text-[11px] font-bold text-[#1d4ed8]">
                                    <svg viewBox="0 0 10 10" class="h-2.5 w-2.5 fill-current" aria-hidden="true"><circle cx="5" cy="3.5" r="2"/><path d="M1 9.5a4 4 0 0 1 8 0"/></svg>
                                    {{ $entry['male'] }} Male
                                </span>
                                <span class="inline-flex items-center gap-1 rounded-full bg-[#fce7f3] px-2 py-0.5 text-[11px] font-bold text-[#be185d]">
                                    <svg viewBox="0 0 10 10" class="h-2.5 w-2.5 fill-current" aria-hidden="true"><circle cx="5" cy="3.5" r="2"/><path d="M1 9.5a4 4 0 0 1 8 0"/></svg>
                                    {{ $entry['female'] }} Female
                                </span>
                            </div>
                            <div class="mt-2 text-xs text-[#64748b]">
                                {{ $entry['total'] === 1 ? '1 employee in this office' : number_format($entry['total']) . ' employees in this office' }}
                            </div>

                            <div class="mt-4 border-t border-[#e8edf2] pt-3">
                                <a href="{{ route('user.offices.staff', ['office' => $entry['office']]) }}" class="group flex items-center justify-between">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#94a3b8] group-hover:text-[#16a34a]">View Staff Directory</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-[#94a3b8] group-hover:text-[#16a34a]">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
