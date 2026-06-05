@extends('layouts.app')

@section('page_title', 'Offices Framework')
@section('page_subtitle', 'Manage LGU Trento offices for PDS categorization.')

@section('page_actions')
@endsection

@section('content')
<!-- Add error and success messages display -->
@if (session('success'))
    <div class="mb-4 rounded-md bg-green-50 p-4 text-green-700 border border-green-100 shadow-sm">
        {{ session('success') }}
    </div>
@endif
@if ($errors->any())
    <div class="mb-4 rounded-md bg-red-50 p-4 text-red-700 border border-red-100 shadow-sm">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="mb-4 grid gap-3 md:grid-cols-2">
        <div class="panel p-4">
            <div class="text-[10px] font-medium uppercase tracking-[0.08em] text-[#94a3b8]">Total Offices</div>
            <div class="mt-2 text-2xl font-bold text-[#0f172a]">{{ number_format($totalOffices) }}</div>
            <div class="mt-1 text-sm text-[#64748b]">Configured office choices for PDS encoding.</div>
        </div>
        <div class="panel p-4">
            <div class="text-[10px] font-medium uppercase tracking-[0.08em] text-[#94a3b8]">Tagged Records</div>
            <div class="mt-2 text-2xl font-bold text-[#0f172a]">{{ number_format($taggedEmployees) }}</div>
            <div class="mt-1 text-sm text-[#64748b]">Employee records currently saved with an office.</div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-heading" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
            <span>Manage Offices</span>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <form method="GET" action="{{ route('offices.index') }}" style="display: flex; align-items: center;">
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                        class="w-48 rounded-[8px] border border-[#d7e2ec] px-2.5 py-1.5 text-xs text-[#0f172a] placeholder-[#94a3b8] outline-none transition focus:border-[#16a34a] focus:ring-1 focus:ring-[#16a34a]/20"
                        placeholder="Search offices...">
                    <button type="submit" class="ml-1.5 inline-flex items-center rounded-[6px] bg-[#f1f5f9] px-2 py-1.5 text-xs text-[#475569] hover:bg-[#e2e8f0] transition">
                        <svg viewBox="0 0 16 16" class="h-3.5 w-3.5 stroke-current" fill="none"><path d="M7.5 13.5a6 6 0 1 0 0-12 6 6 0 0 0 0 12zM11 11l3 3" stroke-width="1.5" stroke-linecap="round"/></svg>
                    </button>
                    @if (($search ?? '') !== '')
                        <a href="{{ route('offices.index') }}" class="ml-1 inline-flex items-center rounded-[6px] bg-[#fee2e2] px-2 py-1.5 text-xs text-[#dc2626] hover:bg-[#fecaca] transition">
                            <svg viewBox="0 0 16 16" class="h-3.5 w-3.5 stroke-current" fill="none"><path d="M3 3l10 10M13 3L3 13" stroke-width="1.5" stroke-linecap="round"/></svg>
                        </a>
                    @endif
                </form>
                <button type="button" class="inline-flex items-center rounded-md bg-[#16a34a] px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-[#15803d] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#16a34a]" onclick="openAddModal()">
                    <svg viewBox="0 0 24 24" class="h-3.5 w-3.5 stroke-current mr-1.5" fill="none"><path d="M12 5v14m-7-7h14" stroke-width="2" stroke-linecap="round"/></svg>
                    Add Office
                </button>
            </div>
        </div>
        <div class="p-4">
            @if ($dbOffices->isEmpty())
                <div class="rounded-[10px] border border-dashed border-[#e8edf2] px-4 py-6 text-sm text-[#64748b]">
                    @if (($search ?? '') !== '')
                        No offices matching "<strong>{{ $search }}</strong>". Try a different search term.
                    @else
                        No offices available. Please add an office above.
                    @endif
                </div>
            @else
                <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($dbOffices as $office)
                        @php
                            $empCount = $officeCounts[$office->name]['count'] ?? 0;
                        @endphp
                        <div class="rounded-[10px] border border-[#e8edf2] bg-white px-4 py-3 transition hover:border-[#16a34a] flex flex-col justify-between shadow-sm">
                            <div>
                                <div class="flex items-center justify-between gap-3">
                                    <div class="text-sm font-medium text-[#0f172a] truncate">{{ $office->name }}</div>
                                    <span class="inline-flex min-w-9 items-center justify-center rounded-full bg-[#f0fdf4] px-2.5 py-1 text-xs font-bold text-[#15803d]" title="Tagged Employees">
                                        {{ $empCount }}
                                    </span>
                                </div>
                                <div class="mt-2 text-xs text-[#64748b]">
                                    {{ $empCount === 1 ? '1 record tagged' : number_format($empCount) . ' records tagged' }}
                                </div>
                            </div>
                            <!-- Actions -->
                            <div class="mt-3 flex gap-2 border-t border-[#f1f5f9] pt-3">
                                <!-- View Employees -->
                                <a href="{{ route('records.index', ['office' => $office->name]) }}" class="text-xs font-medium text-[#2563eb] hover:text-[#1d4ed8]">
                                    View Related
                                </a>
                                <!-- Edit Button -->
                                <button type="button" class="text-xs font-medium text-[#d97706] hover:text-[#b45309] ml-3"
                                        onclick="openEditModal({{ $office->id }}, '{{ addslashes($office->name) }}')">
                                    Rename
                                </button>
                                <!-- Delete Button -->
                                <form action="{{ route('admin.offices.destroy', $office) }}" method="POST" class="ml-auto" onsubmit="return confirm('Are you sure you want to delete this office?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-medium text-[#dc2626] hover:text-[#b91c1c] {{ $empCount > 0 ? 'opacity-40 cursor-not-allowed' : '' }}" {{ $empCount > 0 ? 'disabled' : '' }} title="{{ $empCount > 0 ? 'Cannot delete office with tagged employees' : 'Delete Office' }}">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Modals -->
    <!-- Add Office Overlay Modal -->
    <div id="add-office-modal" class="fixed inset-0 z-50 hidden items-center justify-center" aria-modal="true" role="dialog">
        <div class="absolute inset-0 bg-[#0f172a]/60" onclick="closeAddModal()"></div>
        <div class="relative w-full max-w-md mx-4 bg-white rounded-2xl shadow-2xl overflow-hidden animate-in">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-[#f1f5f9]">
                <div>
                    <h3 class="text-base font-bold text-[#0f172a]">Add New Office</h3>
                    <p class="text-xs text-[#64748b] mt-0.5">Enter the department/office name to add.</p>
                </div>
                <button type="button" onclick="closeAddModal()" class="ml-4 flex h-8 w-8 items-center justify-center rounded-lg border border-[#e8edf2] text-[#94a3b8] hover:bg-[#f8fafc] hover:text-[#475569] transition">
                    <svg viewBox="0 0 16 16" class="h-4 w-4 stroke-current" fill="none"><path d="M3 3l10 10M13 3L3 13" stroke-width="1.8" stroke-linecap="round"/></svg>
                </button>
            </div>
            <!-- Modal Body -->
            <form action="{{ route('admin.offices.store') }}" method="POST" class="px-6 py-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-[#334155] mb-2">Office Name</label>
                    <input type="text" name="name"
                        class="w-full rounded-xl border border-[#d7e2ec] px-3 py-2.5 text-sm text-[#0f172a] placeholder-[#94a3b8] shadow-sm transition focus:border-[#16a34a] focus:outline-none focus:ring-2 focus:ring-[#16a34a]/20"
                        placeholder="e.g. MAYORS OFFICE" required autofocus>
                </div>
                <div class="flex items-center justify-end gap-3 pt-5 mt-5 border-t border-[#f1f5f9]">
                    <button type="button" onclick="closeAddModal()"
                        class="rounded-xl border border-[#e2e8f0] bg-white px-4 py-2 text-sm font-semibold text-[#475569] transition hover:bg-[#f8fafc]">
                        Cancel
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-[#16a34a] px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-[#15803d]">
                        <svg viewBox="0 0 16 16" class="h-3.5 w-3.5 stroke-current" fill="none"><path d="M3 8h10M8 3v10" stroke-width="2" stroke-linecap="round"/></svg>
                        Save Office
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Office Overlay Modal -->
    <div id="edit-office-modal" class="fixed inset-0 z-50 hidden items-center justify-center" aria-modal="true" role="dialog">
        <div class="absolute inset-0 bg-[#0f172a]/60" onclick="closeEditModal()"></div>
        <div class="relative w-full max-w-md mx-4 bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-[#f1f5f9]">
                <div>
                    <h3 class="text-base font-bold text-[#0f172a]">Rename Office</h3>
                    <p class="text-xs text-[#64748b] mt-0.5">Update the department/office name below.</p>
                </div>
                <button type="button" onclick="closeEditModal()" class="ml-4 flex h-8 w-8 items-center justify-center rounded-lg border border-[#e8edf2] text-[#94a3b8] hover:bg-[#f8fafc] hover:text-[#475569] transition">
                    <svg viewBox="0 0 16 16" class="h-4 w-4 stroke-current" fill="none"><path d="M3 3l10 10M13 3L3 13" stroke-width="1.8" stroke-linecap="round"/></svg>
                </button>
            </div>
            <!-- Modal Body -->
            <form id="edit-office-form" action="" method="POST" class="px-6 py-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-[#334155] mb-2">Office Name</label>
                    <input type="text" name="name" id="edit-office-name"
                        class="w-full rounded-xl border border-[#d7e2ec] px-3 py-2.5 text-sm text-[#0f172a] placeholder-[#94a3b8] shadow-sm transition focus:border-[#d97706] focus:outline-none focus:ring-2 focus:ring-[#d97706]/20"
                        required>
                </div>
                <div class="flex items-center justify-end gap-3 pt-5 mt-5 border-t border-[#f1f5f9]">
                    <button type="button" onclick="closeEditModal()"
                        class="rounded-xl border border-[#e2e8f0] bg-white px-4 py-2 text-sm font-semibold text-[#475569] transition hover:bg-[#f8fafc]">
                        Cancel
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-[#d97706] px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-[#b45309]">
                        <svg viewBox="0 0 16 16" class="h-3.5 w-3.5 stroke-current" fill="none"><path d="M2 12l1.5-4L11 1l3 3-7.5 7.5L2 12z" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Update Office
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            const modal = document.getElementById('add-office-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
        function closeAddModal() {
            const modal = document.getElementById('add-office-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
        function openEditModal(id, name) {
            document.getElementById('edit-office-name').value = name;
            document.getElementById('edit-office-form').action = '/admin/offices/' + id;
            const modal = document.getElementById('edit-office-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
        function closeEditModal() {
            const modal = document.getElementById('edit-office-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') { closeAddModal(); closeEditModal(); }
        });
    </script>
@endsection
