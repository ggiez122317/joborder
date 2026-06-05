@extends('layouts.app')

@section('page_title', 'Offices Framework')
@section('page_subtitle', 'Manage LGU Trento offices for PDS categorization.')

@section('page_actions')
    <!-- Add new office modal trigger -->
    <button type="button" class="inline-flex items-center rounded-md bg-[#16a34a] px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#15803d] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#16a34a]" onclick="document.getElementById('add-office-modal').showModal()">
        <svg viewBox="0 0 24 24" class="h-4 w-4 stroke-current mr-2" fill="none"><path d="M12 5v14m-7-7h14" stroke-width="2" stroke-linecap="round"/></svg>
        Add Office
    </button>
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
        <div class="panel-heading">Manage Offices</div>
        <div class="p-4">
            @if ($dbOffices->isEmpty())
                <div class="rounded-[10px] border border-dashed border-[#e8edf2] px-4 py-6 text-sm text-[#64748b]">
                    No offices available. Please add an office above.
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
    <!-- Add Modal -->
    <dialog id="add-office-modal" class="rounded-[16px] border border-[#e8edf2] bg-white p-6 shadow-[0_20px_40px_rgba(15,23,42,0.12)] backdrop:bg-[#0f172a]/40 open:animate-in open:fade-in open:zoom-in-95" style="width: 100%; max-width: 400px; margin: auto;">
        <form action="{{ route('admin.offices.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <h3 class="text-lg font-bold text-[#0f172a]">Add New Office</h3>
                <p class="text-sm text-[#64748b] mt-1">Enter the exact name of the department/office.</p>
            </div>
            <div class="mb-5">
                <label class="block text-sm font-medium text-[#334155] mb-1.5">Office Name</label>
                <input type="text" name="name" class="w-full rounded-[10px] border-[#d7e2ec] shadow-sm focus:border-[#16a34a] focus:ring-[#16a34a] sm:text-sm" placeholder="e.g. MAYORS OFFICE" required>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" class="rounded-[10px] border border-[#e8edf2] bg-white px-4 py-2 text-sm font-semibold text-[#475569] hover:bg-[#f8fafc]" onclick="document.getElementById('add-office-modal').close()">Cancel</button>
                <button type="submit" class="inline-flex items-center rounded-[10px] border border-transparent bg-[#16a34a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#15803d]">Save Office</button>
            </div>
        </form>
    </dialog>

    <!-- Edit Modal -->
    <dialog id="edit-office-modal" class="rounded-[16px] border border-[#e8edf2] bg-white p-6 shadow-[0_20px_40px_rgba(15,23,42,0.12)] backdrop:bg-[#0f172a]/40 open:animate-in open:fade-in open:zoom-in-95" style="width: 100%; max-width: 400px; margin: auto;">
        <form id="edit-office-form" action="" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <h3 class="text-lg font-bold text-[#0f172a]">Edit Office</h3>
                <p class="text-sm text-[#64748b] mt-1">Rename the department/office below.</p>
            </div>
            <div class="mb-5">
                <label class="block text-sm font-medium text-[#334155] mb-1.5">Office Name</label>
                <input type="text" name="name" id="edit-office-name" class="w-full rounded-[10px] border-[#d7e2ec] shadow-sm focus:border-[#16a34a] focus:ring-[#16a34a] sm:text-sm" required>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" class="rounded-[10px] border border-[#e8edf2] bg-white px-4 py-2 text-sm font-semibold text-[#475569] hover:bg-[#f8fafc]" onclick="document.getElementById('edit-office-modal').close()">Cancel</button>
                <button type="submit" class="inline-flex items-center rounded-[10px] border border-transparent bg-[#d97706] px-4 py-2 text-sm font-semibold text-white hover:bg-[#b45309]">Update Office</button>
            </div>
        </form>
    </dialog>

    <script>
        function openEditModal(id, name) {
            document.getElementById('edit-office-name').value = name;
            document.getElementById('edit-office-form').action = '/admin/offices/' + id;
            document.getElementById('edit-office-modal').showModal();
        }
    </script>
@endsection
