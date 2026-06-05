@extends('layouts.app')

@section('page_title', 'Report Analytics')
@section('page_subtitle', 'Smart filters, summaries, and breakdowns for PDS records')

@section('content')
    @php
        $totals = data_get($analytics, 'totals', []);
        $breakdowns = data_get($analytics, 'breakdowns', []);
        $insights = data_get($analytics, 'insights', []);
        $needsAttention = data_get($analytics, 'needs_attention', collect());
        $filterLabels = [
            'q' => 'Search',
            'office' => 'Office',
            'sex' => 'Sex',
            'status' => 'Status',
            'submitted_by' => 'Submission Source',
            'date_from' => 'From',
            'date_to' => 'To',
        ];
        $activeFilters = collect($filters)
            ->filter(fn ($value) => filled($value))
            ->map(fn ($value, $key) => ($filterLabels[$key] ?? Str::headline($key)) . ': ' . $value);
    @endphp

    <div class="mb-5 flex flex-wrap items-start justify-between gap-4">
        <div class="max-w-3xl">
            <div class="text-[11px] font-semibold uppercase tracking-[0.12em] text-[#16a34a]">Analytics Workspace</div>
            <h1 class="mt-2 text-2xl font-bold uppercase text-[#0f172a]">Report Analytics</h1>
            <p class="mt-2 text-sm leading-6 text-[#64748b]">Use filters to narrow the report, review quick insights, and export the exact filtered result to Excel.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('reports.analytics.export-excel', request()->query()) }}" class="btn-primary">Export Filtered Excel</a>
            <a href="{{ route('records.index') }}" class="btn-secondary">Back to Records</a>
        </div>
    </div>

    <section class="panel mb-5 overflow-hidden">
        <div class="border-b border-[#eef2f6] bg-[linear-gradient(135deg,#f8fbf9,white)] px-5 py-4">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <div class="text-sm font-semibold text-[#0f172a]">Filter Builder</div>
                    <div class="mt-1 text-xs text-[#64748b]">Choose the exact records you want to analyze or export.</div>
                </div>
                <div class="rounded-full bg-[#f0fdf4] px-3 py-1 text-xs font-semibold text-[#166534]">
                    {{ $filteredCount }} matching {{ Str::plural('record', $filteredCount) }}
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('reports.analytics') }}" class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-4">
            <label class="block">
                <span class="form-label">Keyword Search</span>
                <input name="q" value="{{ $filters['q'] ?? '' }}" class="form-input mt-2" placeholder="Name, office, position, email">
            </label>

            <label class="block">
                <span class="form-label">Office</span>
                <select name="office" class="form-input mt-2">
                    <option value="">All offices</option>
                    @foreach ($officeOptions as $option)
                        <option value="{{ $option }}" @selected(($filters['office'] ?? '') === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </label>

            <label class="block">
                <span class="form-label">Sex</span>
                <select name="sex" class="form-input mt-2">
                    <option value="">All sexes</option>
                    <option value="Male" @selected(($filters['sex'] ?? '') === 'Male')>Male</option>
                    <option value="Female" @selected(($filters['sex'] ?? '') === 'Female')>Female</option>
                </select>
            </label>

            <label class="block">
                <span class="form-label">Completion Status</span>
                <select name="status" class="form-input mt-2">
                    <option value="">All completion states</option>
                    <option value="complete" @selected(($filters['status'] ?? '') === 'complete')>Complete</option>
                    <option value="incomplete" @selected(($filters['status'] ?? '') === 'incomplete')>Incomplete</option>
                </select>
            </label>

            <label class="block">
                <span class="form-label">Submission Source</span>
                <select name="submitted_by" class="form-input mt-2">
                    <option value="">All submission sources</option>
                    <option value="user" @selected(($filters['submitted_by'] ?? '') === 'user')>User Portal</option>
                    <option value="admin" @selected(($filters['submitted_by'] ?? '') === 'admin')>Admin / Office</option>
                </select>
            </label>

            <label class="block">
                <span class="form-label">Created From</span>
                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="form-input mt-2">
            </label>

            <label class="block">
                <span class="form-label">Created To</span>
                <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="form-input mt-2">
            </label>

            <div class="flex flex-wrap gap-3 md:col-span-2 xl:col-span-4">
                <button type="submit" class="btn-primary min-w-[170px]">Apply Filters</button>
                <a href="{{ route('reports.analytics') }}" class="btn-secondary min-w-[120px]">Clear</a>
                <a href="{{ route('reports.analytics.export-excel', request()->query()) }}" class="btn-secondary min-w-[190px]">Export Current Result</a>
            </div>
        </form>

        @if ($activeFilters->isNotEmpty())
            <div class="border-t border-[#eef2f6] bg-[#fbfdff] px-5 py-4">
                <div class="mb-2 text-[11px] font-semibold uppercase tracking-[0.08em] text-[#94a3b8]">Active Filters</div>
                <div class="flex flex-wrap gap-2">
                    @foreach ($activeFilters as $filterText)
                        <span class="inline-flex rounded-full border border-[#dbe7f3] bg-white px-3 py-1 text-xs font-semibold text-[#334155]">{{ $filterText }}</span>
                    @endforeach
                </div>
            </div>
        @endif
    </section>

    <section class="mb-5 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="panel p-5">
            <div class="text-[11px] font-semibold uppercase tracking-[0.08em] text-[#94a3b8]">Filtered Records</div>
            <div class="mt-3 text-4xl font-bold text-[#0f172a]">{{ $totals['records'] ?? 0 }}</div>
            <div class="mt-2 text-sm text-[#64748b]">Records included in the current report.</div>
        </div>
        <div class="panel p-5">
            <div class="text-[11px] font-semibold uppercase tracking-[0.08em] text-[#94a3b8]">Completion Rate</div>
            <div class="mt-3 text-4xl font-bold text-[#15803d]">{{ $totals['completion_rate'] ?? 0 }}%</div>
            <div class="mt-2 text-sm text-[#64748b]">{{ $totals['complete'] ?? 0 }} complete, {{ $totals['incomplete'] ?? 0 }} needing follow-up.</div>
        </div>
        <div class="panel p-5">
            <div class="text-[11px] font-semibold uppercase tracking-[0.08em] text-[#94a3b8]">Active Offices</div>
            <div class="mt-3 text-4xl font-bold text-[#0f172a]">{{ $totals['active_offices'] ?? 0 }}</div>
            <div class="mt-2 text-sm text-[#64748b]">Offices represented after filtering.</div>
        </div>
        <div class="panel p-5">
            <div class="text-[11px] font-semibold uppercase tracking-[0.08em] text-[#94a3b8]">Updated This Month</div>
            <div class="mt-3 text-4xl font-bold text-[#0f172a]">{{ $totals['updated_this_month'] ?? 0 }}</div>
            <div class="mt-2 text-sm text-[#64748b]">Recently maintained records in this report.</div>
        </div>
    </section>

    <section class="mb-5 grid gap-4 xl:grid-cols-[1.3fr_0.9fr]">
        <div class="panel">
            <div class="panel-heading">What This Report Is Telling You</div>
            <div class="space-y-3 p-4 text-sm text-[#475569]">
                @foreach ($insights as $insight)
                    <div class="rounded-[12px] border border-[#e8edf2] bg-[#f8fafc] px-4 py-3">{{ $insight }}</div>
                @endforeach
            </div>
            <div class="border-t border-[#eef2f6] p-4">
                <canvas id="completionChart" height="80"></canvas>
            </div>
        </div>
        <div class="panel">
            <div class="panel-heading">Needs Attention by Office</div>
            <div class="p-4">
                @forelse ($needsAttention as $item)
                    <div class="mb-3 flex items-center justify-between rounded-[12px] border border-[#f7e3b2] bg-[#fffaf0] px-4 py-3">
                        <div>
                            <div class="font-semibold text-[#0f172a]">{{ $item['office'] }}</div>
                            <div class="text-xs text-[#64748b]">Incomplete records in this office</div>
                        </div>
                        <div class="text-lg font-bold text-[#b45309]">{{ $item['count'] }}</div>
                    </div>
                @empty
                    <div class="rounded-[12px] border border-[#dcfce7] bg-[#f0fdf4] px-4 py-3 text-sm font-semibold text-[#166534]">No incomplete records inside the current filter set.</div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="mb-5 grid gap-4 lg:grid-cols-2">
        <div class="panel p-5">
            <div class="mb-4 text-[11px] font-semibold uppercase tracking-[0.08em] text-[#94a3b8]">Office Distribution</div>
            <div class="relative h-[300px]">
                <canvas id="officeChart"></canvas>
            </div>
        </div>
        <div class="grid gap-4 lg:grid-cols-2">
            <div class="panel p-5">
                <div class="mb-4 text-[11px] font-semibold uppercase tracking-[0.08em] text-[#94a3b8]">Sex Distribution Breakdown</div>
                <div class="flex items-center justify-center min-h-[300px]">
                    <div id="sexApexChart" class="w-full"></div>
                </div>
            </div>
            <div class="panel p-5">
                <div class="mb-4 text-[11px] font-semibold uppercase tracking-[0.08em] text-[#94a3b8]">Submission Methods</div>
                <div class="relative h-[200px]">
                    <canvas id="submissionChart"></canvas>
                </div>
            </div>
        </div>
    </section>

    <section class="panel overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#eef2f6] px-4 py-4">
            <div>
                <div class="text-sm font-semibold text-[#0f172a]">Matching Records Preview</div>
                <div class="mt-1 text-xs text-[#64748b]">A quick look at the newest filtered records before exporting.</div>
            </div>
            <a href="{{ route('reports.analytics.export-excel', request()->query()) }}" class="btn-secondary">Download Excel</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-sm">
                <thead class="bg-[#F8FAFC]">
                    <tr>
                        <th class="border-b border-[#e8edf2] px-4 py-3 text-left uppercase text-[11px] tracking-[0.08em] text-[#64748b]">Full Name</th>
                        <th class="border-b border-[#e8edf2] px-4 py-3 text-left uppercase text-[11px] tracking-[0.08em] text-[#64748b]">Office</th>
                        <th class="border-b border-[#e8edf2] px-4 py-3 text-left uppercase text-[11px] tracking-[0.08em] text-[#64748b]">Status</th>
                        <th class="border-b border-[#e8edf2] px-4 py-3 text-left uppercase text-[11px] tracking-[0.08em] text-[#64748b]">Submitted By</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr class="hover:bg-[#fafcff]">
                            <td class="border-b border-[#eef2f6] px-4 py-3 font-semibold text-[#0f172a]">{{ $employee->full_name }}</td>
                            <td class="border-b border-[#eef2f6] px-4 py-3 text-[#475569]">{{ $employee->office ?: 'N/A' }}</td>
                            <td class="border-b border-[#eef2f6] px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $employee->record_status === 'complete' ? 'bg-[#dcfce7] text-[#166534]' : 'bg-[#fef3c7] text-[#92400e]' }}">
                                    {{ ucfirst($employee->record_status) }}
                                </span>
                            </td>
                            <td class="border-b border-[#eef2f6] px-4 py-3 text-[#475569]">{{ $employee->submission_source === 'user' ? ($employee->user?->email ?: 'User Portal') : 'Admin / Office' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center">
                                <div class="text-base font-semibold text-[#0f172a]">No records match the current filters.</div>
                                <div class="mt-1 text-sm text-[#64748b]">Try broadening your filters or clearing them to see more records.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartConfig = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15, font: { size: 11 } } }
                }
            };

            // Office Distribution
            new Chart(document.getElementById('officeChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode(collect($breakdowns['offices'] ?? [])->keys()) !!},
                    datasets: [{
                        label: 'Records',
                        data: {!! json_encode(collect($breakdowns['offices'] ?? [])->values()) !!},
                        backgroundColor: '#16a34a',
                        borderRadius: 6
                    }]
                },
                options: {
                    ...chartConfig,
                    indexAxis: 'y',
                    plugins: { legend: { display: false } },
                    scales: { x: { grid: { display: false } }, y: { grid: { display: false } } }
                }
            });

            // ApexChart for Sex Distribution
            const sexOptions = {
                series: [
                    {{ $breakdowns['sexes']['Male'] ?? 0 }},
                    {{ $breakdowns['sexes']['Female'] ?? 0 }},
                    {{ $breakdowns['sexes']['Prefer not to say'] ?? 0 }}
                ],
                chart: {
                    height: 350,
                    type: 'donut',
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '50%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'GENDER',
                                    formatter: function (w) {
                                        return ''
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return Math.round(val) + "%"
                    },
                    dropShadow: { enabled: false }
                },
                stroke: { width: 2, colors: ['#fff'] },
                colors: ['#4A89C8', '#F7941D', '#9A89A0'],
                labels: ['Male Representation', 'Female Representation', 'Other / Not Specified'],
                legend: {
                    show: true,
                    position: 'bottom',
                    fontSize: '11px',
                    markers: { radius: 12 },
                    itemMargin: { horizontal: 10, vertical: 5 }
                }
            };

            const sexChart = new ApexCharts(document.querySelector("#sexApexChart"), sexOptions);
            sexChart.render();

            // Submission Methods
            new Chart(document.getElementById('submissionChart'), {
                type: 'pie',
                data: {
                    labels: {!! json_encode(collect($breakdowns['submissions'] ?? [])->keys()->map(fn($k) => $k === 'user' ? 'User Portal' : 'Admin')) !!},
                    datasets: [{
                        data: {!! json_encode(collect($breakdowns['submissions'] ?? [])->values()) !!},
                        backgroundColor: ['#2563eb', '#94a3b8'],
                        borderWidth: 0
                    }]
                },
                options: chartConfig
            });

            // Completion Rate (mini bar)
            new Chart(document.getElementById('completionChart'), {
                type: 'bar',
                data: {
                    labels: ['Complete', 'Incomplete'],
                    datasets: [{
                        data: [{{ $totals['complete'] }}, {{ $totals['incomplete'] }}],
                        backgroundColor: ['#16a34a', '#f59e0b'],
                        borderRadius: 4
                    }]
                },
                options: {
                    ...chartConfig,
                    indexAxis: 'y',
                    plugins: { legend: { display: false } },
                    scales: { 
                        x: { display: false, stacked: true }, 
                        y: { display: false, stacked: true } 
                    }
                }
            });
        });
    </script>
    @endpush
@endsection
