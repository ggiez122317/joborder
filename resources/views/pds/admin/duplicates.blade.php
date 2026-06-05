@extends('layouts.app')

@section('page_title', 'Duplicate Detection')
@section('page_subtitle', 'Quick checks for same employee code or same full name plus birthdate')

@section('content')
    <div class="mb-5">
        <h1 class="text-xl font-bold uppercase">Duplicate Detection</h1>
        <p class="text-sm font-semibold text-[#64748b]">Review possible duplicate records before they spread into offices, profiles, and reports.</p>
    </div>

    <div class="space-y-4">
        @forelse ($groups as $group)
            <section class="panel">
                <div class="panel-heading">{{ $group['reason'] }}</div>
                <div class="border-b border-[#e8edf2] px-4 py-3 text-sm">
                    <span class="font-semibold text-[#0f172a]">Match key:</span>
                    <span class="text-[#475569]">{{ $group['match_key'] }}</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-sm">
                        <thead class="bg-[#f8fafc]">
                            <tr>
                                <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">Employee</th>
                                <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">Employee Code</th>
                                <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">Birthdate</th>
                                <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">Office</th>
                                <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($group['employees'] as $employee)
                                @php
                                    $compareEmployee = collect($group['employees'])->first(fn ($item) => $item->id !== $employee->id);
                                @endphp
                                <tr>
                                    <td class="border-b border-[#e8edf2] px-3 py-3 font-semibold">{{ $employee->full_name }}</td>
                                    <td class="border-b border-[#e8edf2] px-3 py-3">{{ $employee->employee_code ?: 'N/A' }}</td>
                                    <td class="border-b border-[#e8edf2] px-3 py-3">{{ optional($employee->personalInformation)->date_of_birth ?: 'N/A' }}</td>
                                    <td class="border-b border-[#e8edf2] px-3 py-3">{{ $employee->office ?: 'N/A' }}</td>
                                    <td class="border-b border-[#e8edf2] px-3 py-3">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('profile.show', $employee) }}" class="btn-secondary px-3 py-1">Profile</a>
                                            <a href="{{ route('pds.edit', ['employee' => $employee, 'compare_with' => $compareEmployee?->id, 'duplicate_reason' => $group['reason'], 'match_key' => $group['match_key']]) }}" class="btn-primary px-3 py-1">Review Record</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @empty
            <section class="panel">
                <div class="panel-heading">Duplicate Detection</div>
                <div class="px-4 py-8 text-center font-semibold text-[#166534]">No possible duplicates detected with the current employee code and birthdate checks.</div>
            </section>
        @endforelse
    </div>
@endsection
