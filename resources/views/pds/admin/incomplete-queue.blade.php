@extends('layouts.app')

@section('page_title', 'Incomplete PDS Queue')
@section('page_subtitle', 'Records missing office, photo, work data, or other key fields')

@section('content')
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold uppercase">Incomplete PDS Queue</h1>
            <p class="text-sm font-semibold text-[#64748b]">Finish records quickly by focusing only on missing office, profile photo, and work data gaps.</p>
        </div>
        <a href="{{ route('records.index', ['scope' => 'incomplete']) }}" class="btn-secondary">Open in Records</a>
    </div>

    <section class="panel">
        <div class="panel-heading">Records Requiring Follow-up</div>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-sm">
                <thead class="bg-[#f8fafc]">
                    <tr>
                        <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">Employee</th>
                        <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">Job Order</th>
                        <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">Office</th>
                        <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">Position</th>
                        <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">Contact</th>
                        <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">Missing Fields</th>
                        <th class="border-b border-[#e8edf2] px-3 py-3 text-left uppercase">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $item)
                        <tr>
                            <td class="border-b border-[#e8edf2] px-3 py-3 font-semibold text-[#0f172a]">{{ $item['employee']->full_name }}</td>
                            <td class="border-b border-[#e8edf2] px-3 py-3">{{ $item['employee']->job_order ?: 'N/A' }}</td>
                            <td class="border-b border-[#e8edf2] px-3 py-3">{{ $item['employee']->office ?: 'Missing office' }}</td>
                            <td class="border-b border-[#e8edf2] px-3 py-3">{{ $item['employee']->position_title ?: 'Missing position' }}</td>
                            <td class="border-b border-[#e8edf2] px-3 py-3 text-xs">
                                {{ optional($item['employee']->personalInformation)->mobile_no ?: 'No mobile' }}<br>
                                <span class="text-[#64748b]">{{ optional($item['employee']->personalInformation)->email_address ?: 'No email' }}</span>
                            </td>
                            <td class="border-b border-[#e8edf2] px-3 py-3">
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($item['missing'] as $missing)
                                        <span class="inline-flex rounded-full bg-[#fef3c7] px-2.5 py-1 text-xs font-semibold text-[#92400e]">{{ ucfirst($missing) }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="border-b border-[#e8edf2] px-3 py-3">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('pds.edit', $item['employee']) }}" class="btn-primary px-3 py-1">Complete Record</a>
                                    @if ($item['employee']->user_id || $item['employee']->created_by)
                                        <form method="POST" action="{{ route('admin.incomplete-queue.notify', $item['employee']) }}">
                                            @csrf
                                            <button type="submit" class="btn-secondary px-3 py-1">Notify User</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('profile.show', $item['employee']) }}" class="btn-secondary px-3 py-1">View Profile</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-8 text-center font-semibold text-[#166534]">All employee records are currently complete.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
