@extends('layouts.app')

@section('content')
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold uppercase">PDS Records</h1>
            <p class="text-sm font-semibold">
                Search by name, Job Order, position title, or office.
                @if (!empty($office))
                    <span class="ml-1 text-[#15803d]">Filtered: {{ $office }}</span>
                @endif
                @if (($scope ?? '') === 'incomplete')
                    <span class="ml-1 text-[#b45309]">Incomplete queue only</span>
                @endif
            </p>
        </div>
        <a href="{{ route('pds.create') }}" class="btn-primary">Add New PDS</a>
    </div>

    <section class="panel">
        <form method="GET" action="{{ route('records.index') }}" class="flex flex-col gap-3 border-b border-[#1E3A8A] bg-white p-4 md:flex-row">
            @if (!empty($office))
                <input type="hidden" name="office" value="{{ $office }}">
            @endif
            @if (!empty($scope))
                <input type="hidden" name="scope" value="{{ $scope }}">
            @endif
            <input name="q" value="{{ $query }}" class="form-input mt-0" placeholder="Search records">
            <button type="submit" class="btn-primary md:w-40">Search</button>
            <a href="{{ route('records.index') }}" class="btn-secondary md:w-32">Clear</a>
            <a href="{{ route('records.index', ['scope' => 'incomplete']) }}" class="btn-secondary md:w-44">Incomplete Queue</a>
        </form>

        <div class="mb-3 hidden" id="batch-actions">
            <div class="flex flex-wrap items-center gap-3 rounded-lg bg-[#f0f9ff] p-3 border border-[#bae6fd]">
                <span class="text-sm font-bold text-[#0369a1]" id="selected-count">0 items selected</span>
                <button type="button" onclick="batchPrint()" class="btn-primary" style="background-color: #0284c7;">Print Selected IDs</button>
                <button type="button" onclick="batchPrintValid()" class="btn-primary bg-emerald-600 hover:bg-emerald-700 border-none">Print Selected Valid IDs</button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-sm">
                    <thead class="bg-[#F3F4F6]">
                    <tr>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left">
                            <input type="checkbox" id="select-all" class="h-4 w-4 rounded border-gray-300">
                        </th>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Full Name</th>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Job Order</th>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Position Title</th>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Office</th>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Sex</th>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Status</th>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Submitted By</th>
                        <th class="border-b border-[#1E3A8A] px-3 py-2 text-left uppercase">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr class="{{ !$employee->is_active ? 'opacity-60' : '' }} record-row">
                            <td class="border-b border-[#1E3A8A] px-3 py-2">
                                <input type="checkbox" class="record-checkbox h-4 w-4 rounded border-gray-300" value="{{ $employee->id }}">
                            </td>
                            <td class="border-b border-[#1E3A8A] px-3 py-2 font-semibold">
                                {{ $employee->full_name }}
                            </td>
                            <td class="border-b border-[#1E3A8A] px-3 py-2">{{ $employee->job_order ?: 'N/A' }}</td>
                            <td class="border-b border-[#1E3A8A] px-3 py-2">{{ $employee->position_title ?: 'N/A' }}</td>
                            <td class="border-b border-[#1E3A8A] px-3 py-2">{{ $employee->office ?: 'N/A' }}</td>
                            <td class="border-b border-[#1E3A8A] px-3 py-2">{{ $employee->sex_at_birth ?: 'N/A' }}</td>
                            <td class="border-b border-[#1E3A8A] px-3 py-2">
                                @if ($employee->is_active)
                                    <span class="inline-flex rounded-full bg-[#dcfce7] px-2.5 py-0.5 text-[11px] font-semibold uppercase text-[#166534]">Active</span>
                                @else
                                    <span class="inline-flex rounded-full bg-[#fee2e2] px-2.5 py-0.5 text-[11px] font-semibold uppercase text-[#991b1b]">Inactive</span>
                                @endif
                            </td>
                            <td class="border-b border-[#1E3A8A] px-3 py-2">
                                {{ $employee->user?->email ?: 'Admin / Office' }}
                            </td>
                            <td class="border-b border-[#1E3A8A] px-3 py-2">
                                @if (!empty($employee->incomplete_fields))
                                    <div class="mb-2 flex flex-wrap gap-1">
                                        @foreach ($employee->incomplete_fields as $missing)
                                            <span class="inline-flex rounded-full bg-[#fef3c7] px-2 py-0.5 text-[10px] font-semibold uppercase text-[#92400e]">{{ $missing }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="inline-block">
                                    <button type="button" onclick="openActionMenu(this)" data-employee-id="{{ $employee->id }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 shadow-sm hover:bg-slate-50 hover:border-slate-300 transition-all">
                                        Actions
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <template class="action-tpl">
                                        <div class="py-1">
                                            <div class="px-3 py-1"><p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">View</p></div>
                                            <a href="{{ route('profile.show', $employee) }}" class="flex items-center gap-2 px-4 py-1.5 text-xs text-slate-700 hover:bg-slate-50">
                                                <svg style="width:14px; height:14px; flex-shrink:0;" class="text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                Profile
                                            </a>
                                            <a href="{{ route('pds.edit', $employee) }}" class="flex items-center gap-2 px-4 py-1.5 text-xs text-slate-700 hover:bg-slate-50">
                                                <svg style="width:14px; height:14px; flex-shrink:0;" class="text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                Edit Record
                                            </a>
                                            <div class="my-1 border-t border-slate-100"></div>
                                            <div class="px-3 py-1"><p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Print & Export</p></div>
                                            <a href="{{ route('pds.records.id-card', $employee) }}" target="_blank" class="flex items-center gap-2 px-4 py-1.5 text-xs text-slate-700 hover:bg-sky-50">
                                                <svg style="width:14px; height:14px; flex-shrink:0;" class="text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg>
                                                ID Card
                                            </a>
                                            <a href="{{ route('pds.records.valid-id', $employee) }}" target="_blank" class="flex items-center gap-2 px-4 py-1.5 text-xs text-slate-700 hover:bg-emerald-50">
                                                <svg style="width:14px; height:14px; flex-shrink:0;" class="text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                                Valid ID
                                            </a>
                                            <a href="{{ route('profile.print', $employee) }}" target="_blank" class="flex items-center gap-2 px-4 py-1.5 text-xs text-slate-700 hover:bg-slate-50">
                                                <svg style="width:14px; height:14px; flex-shrink:0;" class="text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                                Print PDS
                                            </a>
                                            <a href="{{ route('profile.export-pdf', $employee) }}" class="flex items-center gap-2 px-4 py-1.5 text-xs text-slate-700 hover:bg-slate-50">
                                                <svg style="width:14px; height:14px; flex-shrink:0;" class="text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                Export PDF
                                            </a>
                                            <div class="my-1 border-t border-slate-100"></div>
                                            <form method="POST" action="{{ route('pds.toggle-active', $employee) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="flex w-full items-center gap-2 px-4 py-1.5 text-xs {{ $employee->is_active ? 'text-amber-700 hover:bg-amber-50' : 'text-emerald-700 hover:bg-emerald-50' }}">
                                                    @if($employee->is_active)
                                                        <svg style="width:14px; height:14px; flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                        Deactivate
                                                    @else
                                                        <svg style="width:14px; height:14px; flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        Activate
                                                    @endif
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('pds.destroy', $employee) }}" onsubmit="return confirm('Delete this PDS record? This cannot be undone.');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="flex w-full items-center gap-2 px-4 py-1.5 text-xs text-red-600 hover:bg-red-50">
                                                    <svg style="width:14px; height:14px; flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-3 py-6 text-center font-semibold">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $employees->links() }}
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.record-checkbox');
            const batchActions = document.getElementById('batch-actions');
            const selectedCountLabel = document.getElementById('selected-count');

            function updateBatchUI() {
                const checked = document.querySelectorAll('.record-checkbox:checked');
                if (checked.length > 0) {
                    batchActions.classList.remove('hidden');
                    selectedCountLabel.textContent = `${checked.length} record(s) selected`;
                } else {
                    batchActions.classList.add('hidden');
                }
            }

            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = selectAll.checked);
                    updateBatchUI();
                });
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateBatchUI);
            });
        });

        function batchPrint() {
            const checked = document.querySelectorAll('.record-checkbox:checked');
            if (checked.length === 0) return;

            const ids = Array.from(checked).map(cb => cb.value);
            const url = new URL('{{ route('pds.records.batch-id-cards') }}', window.location.origin);
            ids.forEach(id => url.searchParams.append('ids[]', id));

            window.open(url.toString(), '_blank');
        }

        function batchPrintValid() {
            const checked = document.querySelectorAll('.record-checkbox:checked');
            if (checked.length === 0) return;

            const ids = Array.from(checked).map(cb => cb.value);
            const url = new URL('{{ route('pds.records.batch-valid-ids') }}', window.location.origin);
            ids.forEach(id => url.searchParams.append('ids[]', id));

            window.open(url.toString(), '_blank');
        }
        /* ── Fixed-position Action Menu (portal to body) ── */
        let activePortal = null;

        function openActionMenu(btn) {
            // If clicking same button, toggle off
            if (activePortal && activePortal._triggerBtn === btn) {
                closeActionMenu();
                return;
            }
            closeActionMenu();

            const tpl = btn.parentElement.querySelector('.action-tpl');
            if (!tpl) return;

            // Create portal container
            const portal = document.createElement('div');
            portal.className = 'action-portal';
            portal.style.cssText = 'position:fixed; z-index:9999; width:176px; background:#fff; border:1px solid #e2e8f0; border-radius:8px; box-shadow:0 10px 25px -5px rgba(0,0,0,.1),0 8px 10px -6px rgba(0,0,0,.1); opacity:0; transform:scale(0.95); transition:opacity 100ms ease-out, transform 100ms ease-out;';
            portal.innerHTML = tpl.innerHTML;
            portal._triggerBtn = btn;
            document.body.appendChild(portal);

            // Position: to the LEFT of the button so it doesn't cover table data
            const rect = btn.getBoundingClientRect();
            let top = rect.bottom + 4;
            let left = rect.right - portal.offsetWidth;

            // If menu goes below viewport, open upward
            if (top + portal.offsetHeight > window.innerHeight - 8) {
                top = rect.top - portal.offsetHeight - 4;
            }
            // If menu goes off left edge, align to left of button
            if (left < 8) {
                left = rect.left;
            }

            portal.style.top = top + 'px';
            portal.style.left = left + 'px';

            // Animate in
            requestAnimationFrame(() => {
                portal.style.opacity = '1';
                portal.style.transform = 'scale(1)';
            });

            activePortal = portal;
        }

        function closeActionMenu() {
            if (activePortal) {
                activePortal.remove();
                activePortal = null;
            }
        }

        // Close on click outside
        document.addEventListener('click', function(e) {
            if (activePortal && !activePortal.contains(e.target) && !e.target.closest('button[onclick="openActionMenu(this)"]')) {
                closeActionMenu();
            }
        });

        // Close on scroll so it doesn't float away
        document.addEventListener('scroll', closeActionMenu, true);
        window.addEventListener('resize', closeActionMenu);
    </script>
@endsection
