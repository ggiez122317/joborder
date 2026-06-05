<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="app-shell min-h-screen bg-[#f8fafc] text-[#0f172a]">
    @include('partials.toast')
    @php
        $sidebarAdminCounts = $sidebarAdminCounts ?? ['imports' => 0, 'incomplete' => 0];
        $authUser = auth()->user();
    @endphp
    <div class="flex min-h-screen">
        <button type="button" class="app-sidebar-backdrop print-hidden" id="appSidebarBackdrop" aria-label="Close sidebar"></button>
        <aside id="appSidebar" class="app-sidebar print-hidden flex-col border-r border-[#e8edf2] bg-white">
            <div class="border-b border-[#f0f4f8] px-4 py-[18px]">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="mb-2 flex h-12 w-12 items-center justify-center overflow-hidden rounded-[12px] border border-[#e8edf2] bg-white shadow-[0_12px_24px_rgba(15,23,42,0.08)]">
                            <img src="{{ route('brand.logo') }}" alt="LGU Trento Logo" class="h-full w-full object-cover">
                        </div>
                        <div class="sidebar-label text-[11px] font-medium uppercase tracking-[0.08em] text-[#16a34a]">
                            LGU Trento</div>
                        <div class="sidebar-label mt-0.5 text-xs text-[#64748b]">PDS Records Office</div>
                    </div>
                    <button type="button" id="desktopSidebarToggle"
                        class="sidebar-desktop-toggle hidden h-9 w-9 items-center justify-center rounded-[10px] border border-[#e8edf2] bg-white text-[#475569] shadow-sm md:inline-flex"
                        aria-label="Toggle sidebar">
                        <svg viewBox="0 0 24 24" class="h-4 w-4 stroke-current" fill="none" aria-hidden="true">
                            <path d="M15 6l-6 6 6 6" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
            <nav class="flex-1 px-2 py-[10px] text-sm">
                @if ($authUser?->isAdmin())
                    <div class="sidebar-label px-2 pb-1 pt-1 text-[10px] uppercase tracking-[0.08em] text-[#94a3b8]">Main
                    </div>
                    <a href="{{ route('dashboard') }}"
                        class="mb-1 flex items-center gap-3 rounded-[10px] px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-[#f0fdf4] text-[#15803d]' : 'text-[#64748b]' }}">
                        <svg viewBox="0 0 16 16" class="h-[14px] w-[14px] fill-current shrink-0" aria-hidden="true">
                            <rect x="1" y="1" width="6" height="6" rx="1.5"></rect>
                            <rect x="9" y="1" width="6" height="6" rx="1.5" opacity=".4"></rect>
                            <rect x="1" y="9" width="6" height="6" rx="1.5" opacity=".4"></rect>
                            <rect x="9" y="9" width="6" height="6" rx="1.5" opacity=".4"></rect>
                        </svg>
                        <span class="sidebar-label">Dashboard</span>
                    </a>
                    <a href="{{ route('pds.create') }}"
                        class="mb-1 flex items-center gap-3 rounded-[10px] px-3 py-2.5 text-sm font-medium {{ request()->routeIs('pds.create') ? 'bg-[#f0fdf4] text-[#15803d]' : 'text-[#64748b]' }}">
                        <svg viewBox="0 0 16 16" class="h-[14px] w-[14px] stroke-current shrink-0" fill="none"
                            aria-hidden="true">
                            <circle cx="8" cy="5.5" r="2.5" stroke-width="1.4"></circle>
                            <path d="M3 13c0-2.8 2.2-5 5-5s5 2.2 5 5" stroke-width="1.4" stroke-linecap="round"></path>
                        </svg>
                        <span class="sidebar-label">Add New PDS</span>
                    </a>
                    <a href="{{ route('pds.upload') }}"
                        class="mb-1 flex items-center gap-3 rounded-[10px] px-3 py-2.5 text-sm font-medium {{ request()->routeIs('pds.upload') ? 'bg-[#f0fdf4] text-[#15803d]' : 'text-[#64748b]' }}">
                        <svg viewBox="0 0 16 16" class="h-[14px] w-[14px] stroke-current shrink-0" fill="none"
                            aria-hidden="true">
                            <path d="M9 2H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V6L9 2z" stroke-width="1.4">
                            </path>
                            <path d="M9 2v4h4" stroke-width="1.4" stroke-linecap="round"></path>
                            <path d="M5 9h6M5 11.5h4" stroke-width="1.2" stroke-linecap="round"></path>
                        </svg>
                        <span class="sidebar-label">Upload PDS File</span>
                    </a>
                    <a href="{{ route('offices.index') }}"
                        class="mb-1 flex items-center gap-3 rounded-[10px] px-3 py-2.5 text-sm font-medium {{ request()->routeIs('offices.index') ? 'bg-[#f0fdf4] text-[#15803d]' : 'text-[#64748b]' }}">
                        <svg viewBox="0 0 16 16" class="h-[14px] w-[14px] stroke-current shrink-0" fill="none"
                            aria-hidden="true">
                            <path d="M3 3.5h10v9H3z" stroke-width="1.4"></path>
                            <path d="M6 3.5v9M10 3.5v9M3 7.5h10" stroke-width="1.2"></path>
                        </svg>
                        <span class="sidebar-label">Offices</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="mb-1 flex items-center gap-3 rounded-[10px] px-3 py-2.5 text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'bg-[#f0fdf4] text-[#15803d]' : 'text-[#64748b]' }}">
                        <svg viewBox="0 0 16 16" class="h-[14px] w-[14px] stroke-current shrink-0" fill="none" aria-hidden="true">
                            <circle cx="5" cy="5" r="2.2" stroke-width="1.3"></circle>
                            <circle cx="11.2" cy="6.2" r="1.8" stroke-width="1.3" opacity=".7"></circle>
                            <path d="M1.8 13c0-2.2 1.8-4 4-4s4 1.8 4 4" stroke-width="1.3" stroke-linecap="round"></path>
                            <path d="M8.7 12.5c.3-1.6 1.5-2.8 3.1-3.2" stroke-width="1.3" stroke-linecap="round" opacity=".7"></path>
                        </svg>
                        <span class="sidebar-label">Users</span>
                    </a>
                    <div
                        class="sidebar-label mt-[10px] px-2 pb-1 pt-2 text-[10px] uppercase tracking-[0.08em] text-[#94a3b8]">
                        Records</div>
                    <a href="{{ route('records.index') }}"
                        class="mb-1 flex items-center gap-3 rounded-[10px] px-3 py-2.5 text-sm font-medium {{ request()->routeIs('records.index') ? 'bg-[#f0fdf4] text-[#15803d]' : 'text-[#64748b]' }}">
                        <svg viewBox="0 0 16 16" class="h-[14px] w-[14px] stroke-current shrink-0" fill="none"
                            aria-hidden="true">
                            <rect x="1" y="4" width="14" height="9" rx="1" stroke-width="1.4"></rect>
                            <path d="M4 4V3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v1" stroke-width="1.4"></path>
                            <path d="M5 8.5h6M5 11h4" stroke-width="1.2" stroke-linecap="round"></path>
                        </svg>
                        <span class="sidebar-label">View Records</span>
                    </a>
                    <a href="{{ route('reports.analytics') }}"
                        class="mb-1 flex items-center gap-3 rounded-[10px] px-3 py-2.5 text-sm font-medium {{ request()->routeIs('reports.analytics') ? 'bg-[#f0fdf4] text-[#15803d]' : 'text-[#64748b]' }}">
                        <svg viewBox="0 0 16 16" class="h-[14px] w-[14px] stroke-current shrink-0" fill="none" aria-hidden="true">
                            <path d="M2.5 12.5V9.5M8 12.5v-9M13.5 12.5v-6" stroke-width="1.4" stroke-linecap="round"></path>
                            <path d="M1.5 13.5h13" stroke-width="1.2" stroke-linecap="round"></path>
                        </svg>
                        <span class="sidebar-label">Report Analytics</span>
                    </a>
                    <div
                        class="sidebar-label mt-[10px] px-2 pb-1 pt-2 text-[10px] uppercase tracking-[0.08em] text-[#94a3b8]">
                        Admin tools</div>
                    <a href="{{ route('admin.import-history') }}"
                    class="mb-1 flex items-center gap-3 rounded-[10px] px-3 py-2.5 text-sm font-medium {{ request()->routeIs('admin.import-history*') ? 'bg-[#f0fdf4] text-[#15803d]' : 'text-[#64748b]' }}">
                        <svg viewBox="0 0 16 16" class="h-[14px] w-[14px] stroke-current shrink-0" fill="none" aria-hidden="true">
                            <path d="M3 2.5h7l3 3V13a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-9a1 1 0 0 1 1-1Z" stroke-width="1.3"></path>
                            <path d="M10 2.5V6h3" stroke-width="1.3" stroke-linecap="round"></path>
                            <path d="M5 9h6M5 11h4" stroke-width="1.3" stroke-linecap="round"></path>
                        </svg>
                        <span class="sidebar-label">Import History</span>
                        @if (($sidebarAdminCounts['imports'] ?? 0) > 0)
                            <span class="ml-auto inline-flex min-w-[22px] items-center justify-center rounded-full bg-[#dcfce7] px-2 py-0.5 text-[11px] font-bold text-[#15803d]">
                                {{ $sidebarAdminCounts['imports'] }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('admin.incomplete-queue') }}"
                    class="mb-1 flex items-center gap-3 rounded-[10px] px-3 py-2.5 text-sm font-medium {{ request()->routeIs('admin.incomplete-queue') ? 'bg-[#f0fdf4] text-[#15803d]' : 'text-[#64748b]' }}">
                        <svg viewBox="0 0 16 16" class="h-[14px] w-[14px] stroke-current shrink-0" fill="none" aria-hidden="true">
                            <circle cx="8" cy="8" r="6" stroke-width="1.3"></circle>
                            <path d="M8 4.5v4" stroke-width="1.3" stroke-linecap="round"></path>
                            <circle cx="8" cy="11.5" r=".7" fill="currentColor" stroke="none"></circle>
                        </svg>
                        <span class="sidebar-label">Incomplete Queue</span>
                        @if (($sidebarAdminCounts['incomplete'] ?? 0) > 0)
                            <span class="ml-auto inline-flex min-w-[22px] items-center justify-center rounded-full bg-[#fef3c7] px-2 py-0.5 text-[11px] font-bold text-[#92400e]">
                                {{ $sidebarAdminCounts['incomplete'] }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('admin.audit-logs') }}"
                        class="mb-1 flex items-center gap-3 rounded-[10px] px-3 py-2.5 text-sm font-medium {{ request()->routeIs('admin.audit-logs') ? 'bg-[#f0fdf4] text-[#15803d]' : 'text-[#64748b]' }}">
                        <svg viewBox="0 0 16 16" class="h-[14px] w-[14px] stroke-current shrink-0" fill="none" aria-hidden="true">
                            <path d="M3 13V5M8 13V3M13 13V8" stroke-width="1.4" stroke-linecap="round"></path>
                        </svg>
                        <span class="sidebar-label">Audit Logs</span>
                    </a>
                    <a href="{{ route('admin.id-templates.index') }}"
                        class="mb-1 flex items-center gap-3 rounded-[10px] px-3 py-2.5 text-sm font-medium {{ request()->routeIs('admin.id-templates*') ? 'bg-[#f0fdf4] text-[#15803d]' : 'text-[#64748b]' }}">
                        <svg viewBox="0 0 16 16" class="h-[14px] w-[14px] stroke-current shrink-0" fill="none" aria-hidden="true">
                            <rect x="2" y="2" width="12" height="12" rx="1.5" stroke-width="1.3"></rect>
                            <circle cx="8" cy="7" r="2.5" stroke-width="1.3"></circle>
                            <path d="M4.5 12c.5-1.5 2-2.5 3.5-2.5s3 1 3.5 2.5" stroke-width="1.3" stroke-linecap="round"></path>
                        </svg>
                        <span class="sidebar-label">ID Templates</span>
                    </a>
                @elseif ($authUser?->isUser())
                    <div class="sidebar-label px-2 pb-1 pt-1 text-[10px] uppercase tracking-[0.08em] text-[#94a3b8]">My Portal</div>
                    <a href="{{ route('user.dashboard') }}"
                        class="mb-1 flex items-center gap-3 rounded-[10px] px-3 py-2.5 text-sm font-medium {{ request()->routeIs('user.dashboard') ? 'bg-[#f0fdf4] text-[#15803d]' : 'text-[#64748b]' }}">
                        <svg viewBox="0 0 16 16" class="h-[14px] w-[14px] fill-current shrink-0" aria-hidden="true">
                            <rect x="1" y="1" width="6" height="6" rx="1.5"></rect>
                            <rect x="9" y="1" width="6" height="6" rx="1.5" opacity=".4"></rect>
                            <rect x="1" y="9" width="6" height="6" rx="1.5" opacity=".4"></rect>
                            <rect x="9" y="9" width="6" height="6" rx="1.5" opacity=".4"></rect>
                        </svg>
                        <span class="sidebar-label">Dashboard</span>
                    </a>

                    <div class="sidebar-label mt-[10px] px-2 pb-1 pt-2 text-[10px] uppercase tracking-[0.08em] text-[#94a3b8]">Records</div>
                    <a href="{{ route('user.records.create') }}"
                        class="mb-1 flex items-center gap-3 rounded-[10px] px-3 py-2.5 text-sm font-medium {{ request()->routeIs('user.records.create') ? 'bg-[#f0fdf4] text-[#15803d]' : 'text-[#64748b]' }}">
                        <svg viewBox="0 0 16 16" class="h-[14px] w-[14px] stroke-current shrink-0" fill="none" aria-hidden="true">
                            <circle cx="8" cy="5.5" r="2.5" stroke-width="1.4"></circle>
                            <path d="M3 13c0-2.8 2.2-5 5-5s5 2.2 5 5" stroke-width="1.4" stroke-linecap="round"></path>
                        </svg>
                        <span class="sidebar-label">Add New PDS</span>
                    </a>
                    <a href="{{ route('user.records') }}"
                        class="mb-1 flex items-center gap-3 rounded-[10px] px-3 py-2.5 text-sm font-medium {{ request()->routeIs('user.records') || request()->routeIs('user.records.edit') ? 'bg-[#f0fdf4] text-[#15803d]' : 'text-[#64748b]' }}">
                        <svg viewBox="0 0 16 16" class="h-[14px] w-[14px] stroke-current shrink-0" fill="none" aria-hidden="true">
                            <rect x="1" y="4" width="14" height="9" rx="1" stroke-width="1.4"></rect>
                            <path d="M4 4V3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v1" stroke-width="1.4"></path>
                            <path d="M5 8.5h6M5 11h4" stroke-width="1.2" stroke-linecap="round"></path>
                        </svg>
                        <span class="sidebar-label">My Records</span>
                    </a>
                    <a href="{{ route('user.report-analytics') }}"
                        class="mb-1 flex items-center gap-3 rounded-[10px] px-3 py-2.5 text-sm font-medium {{ request()->routeIs('user.report-analytics') ? 'bg-[#f0fdf4] text-[#15803d]' : 'text-[#64748b]' }}">
                        <svg viewBox="0 0 16 16" class="h-[14px] w-[14px] stroke-current shrink-0" fill="none" aria-hidden="true">
                            <path d="M2.5 12.5V9.5M8 12.5v-9M13.5 12.5v-6" stroke-width="1.4" stroke-linecap="round"></path>
                            <path d="M1.5 13.5h13" stroke-width="1.2" stroke-linecap="round"></path>
                        </svg>
                        <span class="sidebar-label">Report Analytics</span>
                    </a>
                    <div class="sidebar-label mt-[10px] px-2 pb-1 pt-2 text-[10px] uppercase tracking-[0.08em] text-[#94a3b8]">Info</div>
                    <a href="{{ route('user.offices') }}"
                        class="mb-1 flex items-center gap-3 rounded-[10px] px-3 py-2.5 text-sm font-medium {{ request()->routeIs('user.offices') ? 'bg-[#f0fdf4] text-[#15803d]' : 'text-[#64748b]' }}">
                        <svg viewBox="0 0 16 16" class="h-[14px] w-[14px] stroke-current shrink-0" fill="none"
                            aria-hidden="true">
                            <path d="M3 3.5h10v9H3z" stroke-width="1.4"></path>
                            <path d="M6 3.5v9M10 3.5v9M3 7.5h10" stroke-width="1.2"></path>
                        </svg>
                        <span class="sidebar-label">Offices</span>
                    </a>

                    <div class="sidebar-label mt-[10px] px-2 pb-1 pt-2 text-[10px] uppercase tracking-[0.08em] text-[#94a3b8]">Account</div>
                    <a href="{{ route('user.profile') }}"
                        class="mb-1 flex items-center gap-3 rounded-[10px] px-3 py-2.5 text-sm font-medium {{ request()->routeIs('user.profile') ? 'bg-[#f0fdf4] text-[#15803d]' : 'text-[#64748b]' }}">
                        <svg viewBox="0 0 16 16" class="h-[14px] w-[14px] stroke-current shrink-0" fill="none" aria-hidden="true">
                            <circle cx="8" cy="8" r="7" stroke-width="1.4"></circle>
                            <circle cx="8" cy="6.5" r="2" stroke-width="1.2"></circle>
                            <path d="M4.5 12.5c.5-1.8 2-3 3.5-3s3 1.2 3.5 3" stroke-width="1.2" stroke-linecap="round"></path>
                        </svg>
                        <span class="sidebar-label">My Profile</span>
                    </a>
                @endif
            </nav>
            <div class="border-t border-[#f0f4f8] px-4 py-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 text-sm font-medium text-[#94a3b8]">
                        <svg viewBox="0 0 16 16" class="h-[13px] w-[13px] stroke-current shrink-0" fill="none"
                            aria-hidden="true">
                            <path d="M6 3H3a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h3M10 5l3 3-3 3M13 8H6" stroke-width="1.4"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <span class="sidebar-label">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="app-main flex min-w-0 flex-1 flex-col">
            <div class="print-hidden border-b border-[#e8edf2] bg-white px-4 py-4 md:hidden">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <button type="button" id="mobileSidebarToggle"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-[12px] border border-[#e8edf2] bg-white text-[#475569] shadow-sm"
                            aria-label="Toggle sidebar">
                            <svg viewBox="0 0 24 24" class="h-5 w-5 stroke-current" fill="none" aria-hidden="true">
                                <path d="M4 7h16M4 12h16M4 17h16" stroke-width="1.8" stroke-linecap="round"></path>
                            </svg>
                        </button>
                        <div class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-[12px] border border-[#e8edf2] bg-white shadow-[0_10px_20px_rgba(15,23,42,0.08)]">
                            <img src="{{ route('brand.logo') }}" alt="LGU Trento Logo" class="h-full w-full object-cover">
                        </div>
                        <div class="min-w-0">
                            <div class="truncate text-sm font-semibold text-[#0f172a]">LGU Trento</div>
                            <div class="truncate text-xs text-[#94a3b8]">PDS Management System</div>
                        </div>
                    </div>
                </div>
            </div>

            <header class="print-hidden border-b border-[#e8edf2] bg-white">
                <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-[14px]">
                    <div class="flex items-center gap-3">
                        <div>
                            <div class="text-[15px] font-medium text-[#0f172a]">
                                @hasSection('page_title')
                                    @yield('page_title')
                                @else
                                    Personal Data Sheet Management System
                                @endif
                            </div>
                            <div class="mt-0.5 text-[11px] text-[#94a3b8]">
                                @hasSection('page_subtitle')
                                    @yield('page_subtitle')
                                @else
                                    LGU Trento - PDS Management
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        @if ($authUser)
                        @php
                            $globalNotifications = \Illuminate\Support\Facades\Cache::remember('notifications_' . auth()->id(), 60, function() {
                                $notifQuery = \App\Models\SystemAuditLog::query()
                                    ->whereIn('action', [
                                        'user-pds-create', 
                                        'user-pds-update', 
                                        'employee-created', 
                                        'employee-updated', 
                                        'importhistory-created',
                                        'user-pds-upload-review', 
                                        'admin-pds-return-incomplete'
                                    ])
                                    ->latest();
                                
                                if (auth()->user()->isAdmin()) {
                                    $notifQuery->where(function($q) {
                                        $q->where('user_role', 'user')
                                          ->orWhere('action', 'admin-pds-return-incomplete')
                                          ->orWhere('action', 'importhistory-created');
                                    });
                                } elseif (auth()->user()->isUser()) {
                                    $notifQuery
                                        ->where('action', 'admin-pds-return-incomplete')
                                        ->where('context->recipient_user_id', auth()->id());
                                }

                                return $notifQuery->limit(15)->get();
                            });
                            $globalUnreadCount = $globalNotifications->where('read_at', null)->count();
                        @endphp

                        <details class="relative">
                            <summary class="cursor-pointer list-none flex h-10 items-center gap-2 rounded-[10px] border border-[#d7e2ec] bg-white px-3 py-1.5 text-[#334155] shadow-sm transition hover:border-[#bfd5c5] hover:bg-[#f8fbf9]">
                                <div class="relative">
                                    <svg viewBox="0 0 16 16" class="h-4 w-4 fill-current" aria-hidden="true">
                                        <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.921L8 1.918zM14.22 12c.223.447.481.801.78 1.062a.5.5 0 0 1-.347.855H1.347a.5.5 0 0 1-.347-.855c.299-.261.557-.615.78-1.062C2.135 11.297 2.25 10 2.25 10V6a5.75 5.75 0 0 1 11.5 0v4s.115 1.297.47 2z"/>
                                    </svg>
                                    @if ($globalUnreadCount > 0)
                                        <span class="absolute -top-1.5 -right-1.5 flex h-3 w-3">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
                                        </span>
                                    @endif
                                </div>
                                <span class="hidden sm:inline text-sm font-semibold">Notifications</span>
                                @if ($globalUnreadCount > 0)
                                    <span class="ml-1 text-[10px] font-bold text-red-600">({{ $globalUnreadCount }})</span>
                                @endif
                            </summary>
                                <div class="absolute right-0 z-50 mt-2 overflow-hidden rounded-[12px] border border-[#dbe5ee] bg-white p-0 shadow-[0_22px_48px_rgba(15,23,42,0.18)]" style="width: 440px; max-width: calc(100vw - 1rem);">
                                    <div class="flex items-center justify-between gap-3 border-b border-[#f1f5f9] bg-[#f8fafc] px-4 py-3">
                                        <span class="text-[11px] font-bold text-[#64748b] uppercase tracking-wider">Recent Activity</span>
                                        @if ($globalNotifications->isNotEmpty())
                                            <form action="{{ route('user.notifications.read') }}" method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center rounded-[8px] border border-[#d7efe0] bg-white px-3 py-1.5 text-[11px] font-semibold text-[#16a34a] transition hover:bg-[#f0fdf4] hover:text-[#15803d]">Mark all as read</button>
                                            </form>
                                        @endif
                                    </div>
                                    <div class="max-h-[520px] overflow-y-auto custom-scrollbar bg-white">
                                        @forelse ($globalNotifications as $notification)
                                            @php
                                                $notifUrl = '#';
                                                if ($notification->target_type === 'App\Models\Employee' && $notification->target_id) {
                                                    $notifUrl = auth()->user()->isAdmin() 
                                                        ? route('profile.show', $notification->target_id) 
                                                        : route('user.pds.form');
                                                } elseif ($notification->path) {
                                                    $notifUrl = url($notification->path);
                                                }
                                                
                                                $iconColor = match($notification->action) {
                                                    'user-pds-create', 'employee-created' => '#16a34a',
                                                    'user-pds-update', 'employee-updated' => '#2563eb',
                                                    'user-pds-upload-review', 'importhistory-created' => '#d97706',
                                                    'admin-pds-return-incomplete' => '#dc2626',
                                                    default => '#64748b'
                                                };
                                                $iconBg = $iconColor . '15'; // 8% opacity
                                            @endphp
                                            <a href="{{ route('user.notifications.read.single', $notification->id) }}" class="group block border-b border-[#eef2f6] transition-all hover:bg-[#f8fafc] {{ !$notification->read_at ? 'bg-[#fff7f7]' : 'bg-white' }}" style="text-decoration: none;">
                                                <div class="grid grid-cols-[44px_minmax(0,1fr)] items-start gap-3 px-4 py-4">
                                                    <div class="pt-0.5">
                                                        <div class="flex h-11 w-11 items-center justify-center rounded-[10px] border border-[color:{{ $iconColor }}22]" style="background-color: {{ $iconBg }}; color: {{ $iconColor }};">
                                                            @if ($notification->action === 'user-pds-create' || $notification->action === 'employee-created')
                                                                <svg viewBox="0 0 16 16" class="h-5 w-5 fill-current"><path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0ZM4.5 7.5a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3a.5.5 0 0 0-1 0v3h-3Z"/></svg>
                                                            @elseif ($notification->action === 'user-pds-update' || $notification->action === 'employee-updated')
                                                                <svg viewBox="0 0 16 16" class="h-5 w-5 fill-current"><path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/></svg>
                                                            @elseif ($notification->action === 'admin-pds-return-incomplete')
                                                                <svg viewBox="0 0 16 16" class="h-5 w-5 fill-current"><path d="M8 1.25a6.75 6.75 0 1 0 6.75 6.75A6.758 6.758 0 0 0 8 1.25Zm0 9.5a.875.875 0 1 1 .875-.875A.875.875 0 0 1 8 10.75Zm.75-3.25a.75.75 0 0 1-1.5 0V5a.75.75 0 0 1 1.5 0Z"/></svg>
                                                            @else
                                                                <svg viewBox="0 0 16 16" class="h-5 w-5 fill-current"><path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/><path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/></svg>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-start gap-2 pr-2">
                                                            @if (!$notification->read_at)
                                                                <span class="mt-1 inline-flex h-2 w-2 flex-shrink-0 rounded-full" style="background-color: {{ $iconColor }};"></span>
                                                            @endif
                                                            <div class="text-[14px] {{ !$notification->read_at ? 'font-semibold text-[#0f172a]' : 'font-medium text-[#475569]' }} leading-5 group-hover:text-[#16a34a] transition-colors break-words">
                                                                {{ $notification->description }}
                                                            </div>
                                                        </div>
                                                        <div class="mt-1 flex flex-wrap items-center gap-x-2 gap-y-1 text-[11px] text-[#64748b]">
                                                            @if ($notification->user)
                                                                <span class="font-bold text-[#16a34a]">{{ explode(' ', $notification->user->name)[0] }}</span>
                                                                <span class="opacity-30">•</span>
                                                            @endif
                                                            <span>{{ $notification->created_at?->diffForHumans() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        @empty
                                            <div class="flex flex-col items-center justify-center py-12 px-6 text-center">
                                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#f8fafc] text-[#94a3b8] mb-3">
                                                    <svg viewBox="0 0 16 16" class="h-6 w-6 fill-current"><path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.921L8 1.918zM14.22 12c.223.447.481.801.78 1.062a.5.5 0 0 1-.347.855H1.347a.5.5 0 0 1-.347-.855c.299-.261.557-.615.78-1.062C2.135 11.297 2.25 10 2.25 10V6a5.75 5.75 0 0 1 11.5 0v4s.115 1.297.47 2z"/></svg>
                                                </div>
                                                <div class="text-sm font-semibold text-[#64748b]">No notifications yet</div>
                                                <p class="mt-1 text-xs text-[#94a3b8]">New activity will appear here.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </details>
                        @endif
                        @yield('page_actions')
                    </div>
                </div>
            </header>

            <main class="flex-1 px-4 py-4 sm:p-5">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>

</html>
