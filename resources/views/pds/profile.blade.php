@extends(($printMode ?? false) || ($pdfMode ?? false) ? 'layouts.print' : (($publicMode ?? false) ? 'layouts.public_profile' : 'layouts.app'))

@section('page_title', 'Employee Profile')
@section('page_subtitle', 'Saved PDS summary and verification view')

@section('page_actions')
    @if (!(($printMode ?? false) || ($pdfMode ?? false) || ($publicMode ?? false)))
        @php
            $printRoute = ($portalMode ?? '') === 'user-record' ? route('user.records.print', $employee) : route('profile.print', $employee);
            $exportRoute = ($portalMode ?? '') === 'user-record' ? route('user.records.export-pdf', $employee) : route('profile.export-pdf', $employee);
            $backRoute = ($portalMode ?? '') === 'user-record' ? route('user.records') : route('records.index');
        @endphp
        <a href="{{ $printRoute }}" class="btn-secondary">Print PDS</a>
        <a href="{{ $exportRoute }}" class="btn-secondary">Export PDF</a>
        <a href="{{ $backRoute }}" class="btn-secondary">Back to Records</a>
    @endif
@endsection

@section('content')
    @php
        $showContact = (bool) data_get($data, 'other.visibility.show_contact');
        $showIdentifiers = (bool) data_get($data, 'other.visibility.show_identifiers');
        $profilePhotoUrl = $profilePhotoUrl ?? null;
        $profilePlaceholderUrl = asset('assets/profile-placeholder.svg');
        $profileLink = $profileLink ?? route('profile.show', $employee);
        $profileStatus = $profileStatus ?? 'Active';
        $employmentType = $employmentType ?? ($employee->job_order ? 'Job Order' : 'Employee');
        
        $isPrintOrPdf = ($printMode ?? false) || ($pdfMode ?? false);
        $isInternal = !($publicMode ?? false);
        $forceShow = $isPrintOrPdf || $isInternal;

        $field = function (string $path, bool $identifier = false, bool $contact = false) use ($data, $showIdentifiers, $showContact, $forceShow) {
            if (!$forceShow) {
                if (($identifier && !$showIdentifiers) || ($contact && !$showContact)) {
                    return '';
                }
            }
            return data_get($data, $path) ?: '';
        };
        $rows = fn(string $path, int $count) => collect(data_get($data, $path, []))->pad($count, [])->take($count);
        $questions = data_get($data, 'other.questions', []);
    @endphp

    @if (!(($printMode ?? false) || ($pdfMode ?? false)))
        <section class="{{ ($publicMode ?? false) ? '' : 'print-hidden' }} space-y-4">
            @if ($publicMode ?? false)
                <div class="panel">
                    <div class="panel-heading">Employee Profile</div>
                    <div class="flex flex-wrap items-center justify-between gap-3 p-4">
                        <div>
                            <div class="text-lg font-bold uppercase text-[#0f172a]">{{ $employee->full_name }}</div>
                            <div class="mt-1 text-sm text-[#64748b]">Public verification view from the profile QR code.</div>
                        </div>
                        <a href="{{ $profileLink }}" target="_blank" rel="noopener noreferrer" class="btn-secondary">Open in New Tab</a>
                    </div>
                </div>
            @endif
            <div class="panel">
                <div class="panel-heading">Employee Summary</div>
                <div class="grid gap-4 p-4 lg:grid-cols-[160px_minmax(0,1fr)_220px]">
                    <div class="flex justify-center lg:justify-start">
                        <div class="flex h-[140px] w-[140px] items-center justify-center overflow-hidden rounded-[16px] border border-[#e8edf2] bg-[#f8fafc]">
                            <img src="{{ $profilePhotoUrl ?: $profilePlaceholderUrl }}" alt="{{ $employee->full_name }}"
                                class="h-full w-full object-cover">
                        </div>
                    </div>
                    <div>
                        <div class="text-xl font-bold uppercase text-[#0f172a]">{{ $employee->full_name }}</div>
                        <div class="mt-2 text-sm text-[#64748b]">Saved PDS summary for records verification and quick lookup.</div>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-[10px] border border-[#e8edf2] bg-[#f8fafc] px-4 py-3">
                                <div class="text-[10px] font-medium uppercase tracking-[0.08em] text-[#94a3b8]">Employment Type</div>
                                <div class="mt-1 text-lg font-bold text-[#0f172a]">{{ $employmentType }}</div>
                            </div>
                            <div class="rounded-[10px] border border-[#e8edf2] bg-[#f8fafc] px-4 py-3">
                                <div class="text-[10px] font-medium uppercase tracking-[0.08em] text-[#94a3b8]">Status</div>
                                <div class="mt-1 text-lg font-bold {{ $profileStatus === 'Active' ? 'text-[#15803d]' : 'text-[#dc2626]' }}">{{ $profileStatus }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-[10px] border border-[#e8edf2] bg-[#f8fafc] p-4 text-center">
                        <div class="text-[10px] font-medium uppercase tracking-[0.08em] text-[#94a3b8]">Scan to Verify</div>
                        @if ($qrSvg)
                            <div class="mt-3 flex justify-center rounded-[10px] border border-[#e8edf2] bg-white p-3">
                                <div class="w-[140px] max-w-full [&>svg]:h-auto [&>svg]:w-full">{!! $qrSvg !!}</div>
                            </div>
                        @endif
                        <a href="{{ $profileLink }}" target="_blank" rel="noopener noreferrer"
                            class="mt-3 inline-block text-xs font-semibold text-[#1d4ed8] underline underline-offset-4">
                            Open Profile Link
                        </a>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-heading">Profile Details</div>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-sm">
                        <tbody>
                            <tr>
                                <td class="border-b border-[#e8edf2] bg-[#f8fafc] px-4 py-3 text-[10px] font-medium uppercase tracking-[0.08em] text-[#64748b]">Job Order</td>
                                <td class="border-b border-[#e8edf2] px-4 py-3 font-semibold text-[#0f172a]">{{ $employee->job_order ?: 'N/A' }}</td>
                                <td class="border-b border-[#e8edf2] bg-[#f8fafc] px-4 py-3 text-[10px] font-medium uppercase tracking-[0.08em] text-[#64748b]">Position</td>
                                <td class="border-b border-[#e8edf2] px-4 py-3 font-semibold text-[#0f172a]">{{ $employee->position_title ?: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="border-b border-[#e8edf2] bg-[#f8fafc] px-4 py-3 text-[10px] font-medium uppercase tracking-[0.08em] text-[#64748b]">Office</td>
                                <td class="border-b border-[#e8edf2] px-4 py-3 font-semibold text-[#0f172a]">{{ $employee->office ?: 'N/A' }}</td>
                                <td class="border-b border-[#e8edf2] bg-[#f8fafc] px-4 py-3 text-[10px] font-medium uppercase tracking-[0.08em] text-[#64748b]">Sex</td>
                                <td class="border-b border-[#e8edf2] px-4 py-3 font-semibold text-[#0f172a]">{{ $employee->sex_at_birth ?: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="border-b border-[#e8edf2] bg-[#f8fafc] px-4 py-3 text-[10px] font-medium uppercase tracking-[0.08em] text-[#64748b]">Employee Code</td>
                                <td class="border-b border-[#e8edf2] px-4 py-3 font-semibold text-[#0f172a]">{{ $field('personal.agency_employee_no', true) ?: 'N/A' }}</td>
                                <td class="border-b border-[#e8edf2] bg-[#f8fafc] px-4 py-3 text-[10px] font-medium uppercase tracking-[0.08em] text-[#64748b]">Contact</td>
                                <td class="border-b border-[#e8edf2] px-4 py-3 font-semibold text-[#0f172a]">{{ $field('personal.mobile_no', false, true) ?: 'Hidden' }}</td>
                            </tr>
                            <tr>
                                <td class="bg-[#f8fafc] px-4 py-3 text-[10px] font-medium uppercase tracking-[0.08em] text-[#64748b]">Profile Link</td>
                                <td colspan="3" class="px-4 py-3">
                                    <a href="{{ $profileLink }}" target="_blank" rel="noopener noreferrer" class="text-sm font-semibold text-[#1d4ed8] underline underline-offset-4">
                                        {{ $profileLink }}
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            @if (!($publicMode ?? false))
                <div class="grid gap-4 lg:grid-cols-2">
                    <div class="panel">
                        <div class="panel-heading">Incomplete PDS Status</div>
                        <div class="p-4">
                            @if (!empty($incompleteFields))
                                <div class="mb-3 text-sm font-semibold text-[#92400e]">This record still needs admin follow-up.</div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($incompleteFields as $missing)
                                        <span class="inline-flex rounded-full bg-[#fef3c7] px-3 py-1 text-xs font-semibold uppercase text-[#92400e]">{{ $missing }}</span>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-sm font-semibold text-[#166534]">This employee record is complete based on office, photo, work data, and contact checks.</div>
                            @endif
                        </div>
                    </div>

                    <div class="panel">
                        <div class="panel-heading">Change Log</div>
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse text-sm">
                                <thead class="bg-[#f8fafc]">
                                    <tr>
                                        <th class="border-b border-[#e8edf2] px-3 py-2 text-left uppercase">When</th>
                                        <th class="border-b border-[#e8edf2] px-3 py-2 text-left uppercase">Who</th>
                                        <th class="border-b border-[#e8edf2] px-3 py-2 text-left uppercase">What Changed</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse (($changeLogs ?? collect())->take(10) as $log)
                                        <tr>
                                            <td class="border-b border-[#e8edf2] px-3 py-2 align-top text-[#475569]">{{ $log->created_at?->format('M d, Y h:i A') }}</td>
                                            <td class="border-b border-[#e8edf2] px-3 py-2 align-top font-semibold text-[#0f172a]">{{ $log->user?->name ?: $log->user?->username ?: 'System' }}</td>
                                            <td class="border-b border-[#e8edf2] px-3 py-2">
                                                <div class="space-y-1">
                                                    @foreach (collect($log->changes ?? [])->take(5) as $fieldName => $change)
                                                        <div class="text-xs text-[#475569]">
                                                            <span class="font-semibold text-[#0f172a]">{{ str_replace(['personal.', 'family.', 'other.questions.', 'other.'], '', $fieldName) }}</span>
                                                            <span class="text-[#94a3b8]">from</span>
                                                            "{{ $change['from'] ?? 'blank' }}"
                                                            <span class="text-[#94a3b8]">to</span>
                                                            "{{ $change['to'] ?? 'blank' }}"
                                                        </div>
                                                    @endforeach
                                                    @if (count($log->changes ?? []) > 5)
                                                        <div class="text-xs font-semibold text-[#15803d]">+ {{ count($log->changes ?? []) - 5 }} more field changes</div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-3 py-6 text-center font-semibold text-[#64748b]">No change log entries yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </section>
    @else
        <article class="pds-doc">
            <section class="pds-page">
                <table class="pds-table">
                    <tr>
                        <td colspan="14" class="text-center font-bold" style="font-size: 16px;">PERSONAL DATA SHEET</td>
                    </tr>
                    <tr>
                        <td colspan="14" class="font-bold">WARNING: Any misrepresentation made in the Personal Data Sheet and
                            the Work Experience Sheet shall cause the filing of administrative/criminal case/s against the
                            person concerned.</td>
                    </tr>
                    <tr>
                        <td colspan="14" class="font-bold">READ THE ATTACHED GUIDE TO FILLING OUT THE PERSONAL DATA SHEET (PDS)
                            BEFORE ACCOMPLISHING THE PDS FORM.</td>
                    </tr>
                    <tr>
                        <td colspan="14" class="pds-small">Print legibly if accomplished through own handwriting. Tick
                            appropriate boxes ( ) and use separate sheet if necessary. Indicate N/A if not applicable. DO NOT
                            ABBREVIATE.</td>
                    </tr>
                    <tr>
                        <td colspan="14" class="pds-section">I. Personal Information</td>
                    </tr>
                    <tr>
                        <td class="pds-label">1.</td>
                        <td colspan="2" class="pds-label">Surname</td>
                        <td colspan="11" class="pds-value">{{ $field('personal.surname') }}</td>
                    </tr>
                    <tr>
                        <td class="pds-label">2.</td>
                        <td colspan="2" class="pds-label">First Name</td>
                        <td colspan="7" class="pds-value">{{ $field('personal.first_name') }}</td>
                        <td colspan="2" class="pds-label">Name Extension (JR., SR)</td>
                        <td colspan="2" class="pds-value">{{ $field('personal.name_extension') }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2" class="pds-label">Middle Name</td>
                        <td colspan="11" class="pds-value">{{ $field('personal.middle_name') }}</td>
                    </tr>
                    <tr>
                        <td class="pds-label">3.</td>
                        <td colspan="2" class="pds-label">Date of Birth (dd/mm/yyyy)</td>
                        <td colspan="3" class="pds-value">{{ $field('personal.date_of_birth') }}</td>
                        <td class="pds-label">16.</td>
                        <td colspan="2" class="pds-label">Citizenship</td>
                        <td colspan="5" class="pds-value">{{ $field('personal.citizenship') }}</td>
                    </tr>
                    <tr>
                        <td class="pds-label">4.</td>
                        <td colspan="2" class="pds-label">Place of Birth</td>
                        <td colspan="3" class="pds-value">{{ $field('personal.place_of_birth') }}</td>
                        <td colspan="3" class="pds-label">If holder of dual citizenship, please indicate details</td>
                        <td colspan="5" class="pds-value">
                            {{ trim($field('personal.citizenship_basis') . ' ' . $field('personal.dual_citizenship_country')) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="pds-label">5.</td>
                        <td colspan="2" class="pds-label">Sex at Birth</td>
                        <td colspan="3" class="pds-value">{{ $field('personal.sex_at_birth') }}</td>
                        <td class="pds-label">17.</td>
                        <td colspan="7" class="pds-label">Residential Address</td>
                    </tr>
                    <tr>
                        <td class="pds-label">6.</td>
                        <td colspan="2" class="pds-label">Civil Status</td>
                        <td colspan="3" class="pds-value">{{ $field('personal.civil_status') }}</td>
                        <td colspan="2" class="pds-value">{{ $field('personal.residential_house_no') }}<br><span
                                class="pds-small">House/Block/Lot No.</span></td>
                        <td colspan="3" class="pds-value">{{ $field('personal.residential_street') }}<br><span
                                class="pds-small">Street</span></td>
                        <td colspan="3" class="pds-value">{{ $field('personal.residential_subdivision') }}<br><span
                                class="pds-small">Subdivision/Village</span></td>
                    </tr>
                    <tr>
                        <td class="pds-label">7.</td>
                        <td colspan="2" class="pds-label">Height (m)</td>
                        <td colspan="3" class="pds-value">{{ $field('personal.height_m') }}</td>
                        <td colspan="2" class="pds-value">{{ $field('personal.residential_barangay') }}<br><span
                                class="pds-small">Barangay</span></td>
                        <td colspan="3" class="pds-value">{{ $field('personal.residential_city') }}<br><span
                                class="pds-small">City/Municipality</span></td>
                        <td colspan="2" class="pds-value">{{ $field('personal.residential_province') }}<br><span
                                class="pds-small">Province</span></td>
                        <td class="pds-value">{{ $field('personal.residential_zip_code') }}<br><span
                                class="pds-small">ZIP</span></td>
                    </tr>
                    <tr>
                        <td class="pds-label">8.</td>
                        <td colspan="2" class="pds-label">Weight (kg)</td>
                        <td colspan="3" class="pds-value">{{ $field('personal.weight_kg') }}</td>
                        <td class="pds-label">18.</td>
                        <td colspan="7" class="pds-label">Permanent Address</td>
                    </tr>
                    <tr>
                        <td class="pds-label">9.</td>
                        <td colspan="2" class="pds-label">Blood Type</td>
                        <td colspan="3" class="pds-value">{{ $field('personal.blood_type') }}</td>
                        <td colspan="2" class="pds-value">{{ $field('personal.permanent_house_no') }}<br><span
                                class="pds-small">House/Block/Lot No.</span></td>
                        <td colspan="3" class="pds-value">{{ $field('personal.permanent_street') }}<br><span
                                class="pds-small">Street</span></td>
                        <td colspan="3" class="pds-value">{{ $field('personal.permanent_subdivision') }}<br><span
                                class="pds-small">Subdivision/Village</span></td>
                    </tr>
                    <tr>
                        <td class="pds-label">10.</td>
                        <td colspan="2" class="pds-label">UMID ID No.</td>
                        <td colspan="3" class="pds-value">{{ $field('personal.umid_id_no', true) }}</td>
                        <td colspan="2" class="pds-value">{{ $field('personal.permanent_barangay') }}<br><span
                                class="pds-small">Barangay</span></td>
                        <td colspan="3" class="pds-value">{{ $field('personal.permanent_city') }}<br><span
                                class="pds-small">City/Municipality</span></td>
                        <td colspan="2" class="pds-value">{{ $field('personal.permanent_province') }}<br><span
                                class="pds-small">Province</span></td>
                        <td class="pds-value">{{ $field('personal.permanent_zip_code') }}<br><span class="pds-small">ZIP</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="pds-label">11.</td>
                        <td colspan="2" class="pds-label">PAG-IBIG ID No.</td>
                        <td colspan="3" class="pds-value">{{ $field('personal.pagibig_id_no', true) }}</td>
                        <td class="pds-label">19.</td>
                        <td colspan="3" class="pds-label">Telephone No.</td>
                        <td colspan="4" class="pds-value">{{ $field('personal.telephone_no', false, true) }}</td>
                    </tr>
                    <tr>
                        <td class="pds-label">12.</td>
                        <td colspan="2" class="pds-label">PhilHealth No.</td>
                        <td colspan="3" class="pds-value">{{ $field('personal.philhealth_no', true) }}</td>
                        <td class="pds-label">20.</td>
                        <td colspan="3" class="pds-label">Mobile No.</td>
                        <td colspan="4" class="pds-value">{{ $field('personal.mobile_no', false, true) }}</td>
                    </tr>
                    <tr>
                        <td class="pds-label">13.</td>
                        <td colspan="2" class="pds-label">PhilSys Number (PSN)</td>
                        <td colspan="3" class="pds-value">{{ $field('personal.philsys_no', true) }}</td>
                        <td class="pds-label">21.</td>
                        <td colspan="3" class="pds-label">E-mail Address (if any)</td>
                        <td colspan="4" class="pds-value">{{ $field('personal.email_address', false, true) }}</td>
                    </tr>
                    <tr>
                        <td class="pds-label">14.</td>
                        <td colspan="2" class="pds-label">TIN No.</td>
                        <td colspan="3" class="pds-value">{{ $field('personal.tin_no', true) }}</td>
                        <td colspan="8" class="pds-label"></td>
                    </tr>
                    <tr>
                        <td class="pds-label">15.</td>
                        <td colspan="2" class="pds-label">Agency Employee No.</td>
                        <td colspan="11" class="pds-value">{{ $field('personal.agency_employee_no', true) }}</td>
                    </tr>

                    <tr>
                        <td colspan="14" class="pds-section">II. Family Background</td>
                    </tr>
                    <tr>
                        <td class="pds-label">22.</td>
                        <td colspan="2" class="pds-label">Spouse's Surname</td>
                        <td colspan="5" class="pds-value">{{ $field('family.spouse_surname') }}</td>
                        <td colspan="4" class="pds-label">23. Name of Children (Write full name and list all)</td>
                        <td colspan="2" class="pds-label">Date of Birth</td>
                    </tr>
                    @for ($i = 0; $i < 4; $i++)
                        <tr>
                            @if ($i === 0)
                                <td></td>
                                <td colspan="2" class="pds-label">First Name</td>
                                <td colspan="3" class="pds-value">{{ $field('family.spouse_first_name') }}</td>
                                <td colspan="2" class="pds-label">Name Extension</td>
                            @elseif ($i === 1)
                                <td></td>
                                <td colspan="2" class="pds-label">Middle Name</td>
                                <td colspan="5" class="pds-value">{{ $field('family.spouse_middle_name') }}</td>
                            @elseif ($i === 2)
                                <td></td>
                                <td colspan="2" class="pds-label">Occupation</td>
                                <td colspan="5" class="pds-value">{{ $field('family.spouse_occupation') }}</td>
                            @else
                                <td></td>
                                <td colspan="2" class="pds-label">Employer/Business Name</td>
                                <td colspan="5" class="pds-value">{{ $field('family.spouse_employer_business_name') }}</td>
                            @endif
                            <td colspan="4" class="pds-value">{{ data_get($data, "children.$i.name") }}</td>
                            <td colspan="2" class="pds-value">{{ data_get($data, "children.$i.date_of_birth") }}</td>
                        </tr>
                    @endfor
                    @for ($i = 4; $i < 8; $i++)
                        <tr>
                            @if ($i === 4)
                                <td></td>
                                <td colspan="2" class="pds-label">Business Address</td>
                                <td colspan="5" class="pds-value">{{ $field('family.spouse_business_address') }}</td>
                            @elseif ($i === 5)
                                <td></td>
                                <td colspan="2" class="pds-label">Telephone No.</td>
                                <td colspan="5" class="pds-value">{{ $field('family.spouse_telephone_no', false, true) }}</td>
                            @elseif ($i === 6)
                                <td class="pds-label">24.</td>
                                <td colspan="2" class="pds-label">Father's Surname</td>
                                <td colspan="5" class="pds-value">{{ $field('family.father_surname') }}</td>
                            @else
                                <td></td>
                                <td colspan="2" class="pds-label">First Name</td>
                                <td colspan="3" class="pds-value">{{ $field('family.father_first_name') }}</td>
                                <td colspan="2" class="pds-value">{{ $field('family.father_name_extension') }}</td>
                            @endif
                            <td colspan="4" class="pds-value">{{ data_get($data, "children.$i.name") }}</td>
                            <td colspan="2" class="pds-value">{{ data_get($data, "children.$i.date_of_birth") }}</td>
                        </tr>
                    @endfor
                    @for ($i = 8; $i < 12; $i++)
                        <tr>
                            @if ($i === 8)
                                <td></td>
                                <td colspan="2" class="pds-label">Middle Name</td>
                                <td colspan="5" class="pds-value">{{ $field('family.father_middle_name') }}</td>
                            @elseif ($i === 9)
                                <td class="pds-label">25.</td>
                                <td colspan="2" class="pds-label">Mother's Maiden Name</td>
                                <td colspan="5" class="pds-value">{{ $field('family.mother_maiden_name') }}</td>
                            @elseif ($i === 10)
                                <td></td>
                                <td colspan="2" class="pds-label">Surname</td>
                                <td colspan="5" class="pds-value">{{ $field('family.mother_surname') }}</td>
                            @else
                                <td></td>
                                <td colspan="2" class="pds-label">First/Middle Name</td>
                                <td colspan="5" class="pds-value">
                                    {{ trim($field('family.mother_first_name') . ' ' . $field('family.mother_middle_name')) }}</td>
                            @endif
                            <td colspan="4" class="pds-value">{{ data_get($data, "children.$i.name") }}</td>
                            <td colspan="2" class="pds-value">{{ data_get($data, "children.$i.date_of_birth") }}</td>
                        </tr>
                    @endfor

                    <tr>
                        <td colspan="14" class="pds-section">III. Educational Background</td>
                    </tr>
                    <tr class="pds-label text-center">
                        <td colspan="2">26. Level</td>
                        <td colspan="3">Name of School</td>
                        <td colspan="3">Basic Education/Degree/Course</td>
                        <td colspan="2">Period of Attendance</td>
                        <td>Highest Level/Units</td>
                        <td>Year Graduated</td>
                        <td colspan="2">Scholarship/Academic Honors</td>
                    </tr>
                    @foreach ($rows('education', 5) as $i => $education)
                        <tr>
                            <td colspan="2" class="pds-label">
                                {{ data_get($education, 'level') ?: data_get(\App\Services\PdsDataService::EDUCATION_LEVELS, $i) }}
                            </td>
                            <td colspan="3" class="pds-value">{{ data_get($education, 'school_name') }}</td>
                            <td colspan="3" class="pds-value">{{ data_get($education, 'degree_course') }}</td>
                            <td class="pds-value">{{ data_get($education, 'attendance_from') }}</td>
                            <td class="pds-value">{{ data_get($education, 'attendance_to') }}</td>
                            <td class="pds-value">{{ data_get($education, 'highest_level_units_earned') }}</td>
                            <td class="pds-value">{{ data_get($education, 'year_graduated') }}</td>
                            <td colspan="2" class="pds-value">{{ data_get($education, 'honors_received') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="pds-label">Signature</td>
                        <td colspan="6" class="pds-value"></td>
                        <td colspan="2" class="pds-label">Date</td>
                        <td colspan="3" class="pds-value"></td>
                    </tr>
                    <tr>
                        <td colspan="14" class="pds-label text-right">CS FORM 212 (Revised 2025), Page 1 of 4</td>
                    </tr>
                </table>
            </section>

            <section class="pds-page">
                <table class="pds-table">
                    <tr>
                        <td colspan="11" class="pds-section">IV. Civil Service Eligibility</td>
                    </tr>
                    <tr class="pds-label text-center">
                        <td colspan="5">27. CES/CSEE/Career Service/RA 1080/Board/Bar/Special Laws/Eligibility</td>
                        <td>Rating</td>
                        <td colspan="2">Date of Examination / Conferment</td>
                        <td>Place of Examination / Conferment</td>
                        <td>License Number</td>
                        <td>Valid Until</td>
                    </tr>
                    @foreach ($rows('eligibility', 7) as $eligibility)
                        <tr>
                            <td colspan="5" class="pds-value">{{ data_get($eligibility, 'career_service') }}</td>
                            <td class="pds-value">{{ data_get($eligibility, 'rating') }}</td>
                            <td colspan="2" class="pds-value">{{ data_get($eligibility, 'examination_date') }}</td>
                            <td class="pds-value">{{ data_get($eligibility, 'examination_place') }}</td>
                            <td class="pds-value">{{ data_get($eligibility, 'license_number') }}</td>
                            <td class="pds-value">{{ data_get($eligibility, 'license_valid_until') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="11" class="pds-section">V. Work Experience</td>
                    </tr>
                    <tr>
                        <td colspan="11" class="pds-small">(Include private employment. Start from your recent work.)
                            Description of duties should be indicated in the attached Work Experience Sheet.</td>
                    </tr>
                    <tr class="pds-label text-center">
                        <td colspan="2">28. Inclusive Dates</td>
                        <td colspan="3">Position Title</td>
                        <td colspan="3">Department / Agency / Office / Company</td>
                        <td colspan="2">Status of Appointment</td>
                        <td>Gov't Service</td>
                    </tr>
                    <tr class="pds-label text-center">
                        <td>From</td>
                        <td>To</td>
                        <td colspan="3"></td>
                        <td colspan="3"></td>
                        <td colspan="2"></td>
                        <td>Y/N</td>
                    </tr>
                    @foreach ($rows('work_experience', 28) as $work)
                        <tr>
                            <td class="pds-value">{{ data_get($work, 'date_from') }}</td>
                            <td class="pds-value">{{ data_get($work, 'date_to') }}</td>
                            <td colspan="3" class="pds-value">{{ data_get($work, 'position_title') }}</td>
                            <td colspan="3" class="pds-value">{{ data_get($work, 'department_agency_office_company') }}</td>
                            <td colspan="2" class="pds-value">{{ data_get($work, 'status_of_appointment') }}</td>
                            <td class="pds-value">{{ data_get($work, 'government_service') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="pds-label">Signature</td>
                        <td colspan="5" class="pds-value"></td>
                        <td class="pds-label">Date</td>
                        <td colspan="2" class="pds-value"></td>
                    </tr>
                    <tr>
                        <td colspan="11" class="pds-label text-right">CS FORM 212 (Revised 2025), Page 2 of 4</td>
                    </tr>
                </table>
            </section>

            <section class="pds-page">
                <table class="pds-table">
                    <tr>
                        <td colspan="11" class="pds-section">VI. Voluntary Work or Involvement in Civic / Non-Government /
                            People / Voluntary Organization/s</td>
                    </tr>
                    <tr class="pds-label text-center">
                        <td colspan="4">29. Name & Address of Organization</td>
                        <td>From</td>
                        <td>To</td>
                        <td>Hours</td>
                        <td colspan="4">Position / Nature of Work</td>
                    </tr>
                    @foreach ($rows('voluntary_work', 7) as $voluntary)
                        <tr>
                            <td colspan="4" class="pds-value">{{ data_get($voluntary, 'organization_name_address') }}</td>
                            <td class="pds-value">{{ data_get($voluntary, 'date_from') }}</td>
                            <td class="pds-value">{{ data_get($voluntary, 'date_to') }}</td>
                            <td class="pds-value">{{ data_get($voluntary, 'number_of_hours') }}</td>
                            <td colspan="4" class="pds-value">{{ data_get($voluntary, 'position_nature_of_work') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="11" class="pds-section">VII. Learning and Development (L&D) Interventions/Training Programs
                            Attended</td>
                    </tr>
                    <tr class="pds-label text-center">
                        <td colspan="4">30. Title of L&D Interventions/Training Programs</td>
                        <td>From</td>
                        <td>To</td>
                        <td>Hours</td>
                        <td>Type of L&D</td>
                        <td colspan="3">Conducted/Sponsored By</td>
                    </tr>
                    @foreach ($rows('trainings', 21) as $training)
                        <tr>
                            <td colspan="4" class="pds-value">{{ data_get($training, 'title') }}</td>
                            <td class="pds-value">{{ data_get($training, 'date_from') }}</td>
                            <td class="pds-value">{{ data_get($training, 'date_to') }}</td>
                            <td class="pds-value">{{ data_get($training, 'number_of_hours') }}</td>
                            <td class="pds-value">{{ data_get($training, 'type_of_ld') }}</td>
                            <td colspan="3" class="pds-value">{{ data_get($training, 'conducted_sponsored_by') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="11" class="pds-section">VIII. Other Information</td>
                    </tr>
                    <tr class="pds-label text-center">
                        <td colspan="2">31. Special Skills and Hobbies</td>
                        <td colspan="6">32. Non-Academic Distinctions / Recognition</td>
                        <td colspan="3">33. Membership in Association/Organization</td>
                    </tr>
                    @for ($i = 0; $i < 7; $i++)
                        <tr>
                            <td colspan="2" class="pds-value">{{ data_get($data, "other.special_skills_hobbies.$i") }}</td>
                            <td colspan="6" class="pds-value">{{ data_get($data, "other.non_academic_distinctions.$i") }}</td>
                            <td colspan="3" class="pds-value">{{ data_get($data, "other.memberships.$i") }}</td>
                        </tr>
                    @endfor
                    <tr>
                        <td colspan="2" class="pds-label">Signature</td>
                        <td colspan="4" class="pds-value"></td>
                        <td colspan="2" class="pds-label">Date</td>
                        <td colspan="3" class="pds-value"></td>
                    </tr>
                    <tr>
                        <td colspan="11" class="pds-label text-right">CS FORM 212 (Revised 2025), Page 3 of 4</td>
                    </tr>
                </table>
            </section>

            <section class="pds-page">
                <table class="pds-table">
                    <tr>
                        <td colspan="2" class="pds-label">34.</td>
                        <td colspan="5">Are you related by consanguinity or affinity to the appointing or recommending
                            authority, chief of bureau or office, or immediate supervisor?</td>
                        <td colspan="6" class="pds-value">a. within third degree:
                            {{ data_get($questions, 'related_third_degree') }}<br>b. within fourth degree (LGU):
                            {{ data_get($questions, 'related_fourth_degree_lgu') }}<br>If YES, give details:
                            {{ data_get($questions, 'related_details') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="pds-label">35.a.</td>
                        <td colspan="5">Have you ever been found guilty of any administrative offense?</td>
                        <td colspan="6" class="pds-value">{{ data_get($questions, 'administrative_offense') }}
                            {{ data_get($questions, 'administrative_offense_details') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="pds-label">35.b.</td>
                        <td colspan="5">Have you been criminally charged before any court?</td>
                        <td colspan="6" class="pds-value">{{ data_get($questions, 'criminally_charged') }} Date Filed:
                            {{ data_get($questions, 'criminal_date_filed') }} Status:
                            {{ data_get($questions, 'criminal_case_status') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="pds-label">36.</td>
                        <td colspan="5">Have you ever been convicted of any crime or violation of any law, decree, ordinance or
                            regulation?</td>
                        <td colspan="6" class="pds-value">{{ data_get($questions, 'convicted_crime') }}
                            {{ data_get($questions, 'convicted_crime_details') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="pds-label">37.</td>
                        <td colspan="5">Have you ever been separated from service by resignation, retirement, dismissal,
                            termination, end of term, finished contract or phase out?</td>
                        <td colspan="6" class="pds-value">{{ data_get($questions, 'separated_service') }}
                            {{ data_get($questions, 'separated_service_details') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="pds-label">38.a.</td>
                        <td colspan="5">Have you ever been a candidate in a national or local election held within the last
                            year?</td>
                        <td colspan="6" class="pds-value">{{ data_get($questions, 'candidate_last_year') }}
                            {{ data_get($questions, 'candidate_details') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="pds-label">38.b.</td>
                        <td colspan="5">Have you resigned from government service during the three-month period before the last
                            election to campaign?</td>
                        <td colspan="6" class="pds-value">{{ data_get($questions, 'resigned_before_election') }}
                            {{ data_get($questions, 'resigned_details') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="pds-label">39.</td>
                        <td colspan="5">Have you acquired immigrant or permanent resident status of another country?</td>
                        <td colspan="6" class="pds-value">{{ data_get($questions, 'immigrant_status') }}
                            {{ data_get($questions, 'immigrant_country') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="pds-label">40.</td>
                        <td colspan="5">Indigenous group, disability, and solo parent status.</td>
                        <td colspan="6" class="pds-value">Indigenous: {{ data_get($questions, 'indigenous_group') }}
                            {{ data_get($questions, 'indigenous_group_details') }}<br>PWD:
                            {{ data_get($questions, 'person_with_disability') }} ID:
                            {{ data_get($questions, 'pwd_id_no') }}<br>Solo Parent: {{ data_get($questions, 'solo_parent') }}
                            ID: {{ data_get($questions, 'solo_parent_id_no') }}</td>
                    </tr>
                    <tr>
                        <td colspan="13" class="pds-section">41. References (Person not related by consanguinity or affinity to
                            applicant/appointee)</td>
                    </tr>
                    <tr class="pds-label text-center">
                        <td colspan="5">Name</td>
                        <td colspan="4">Office / Residential Address</td>
                        <td colspan="4">Contact No. and/or Email</td>
                    </tr>
                    @foreach ($rows('other.references', 3) as $reference)
                        <tr>
                            <td colspan="5" class="pds-value">{{ data_get($reference, 'name') }}</td>
                            <td colspan="4" class="pds-value">{{ data_get($reference, 'address') }}</td>
                            <td colspan="4" class="pds-value">{{ data_get($reference, 'contact') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="5" rowspan="3" class="pds-label">42. I declare under oath that I have personally
                            accomplished this Personal Data Sheet which is a true, correct, and complete statement pursuant to
                            the provisions of pertinent laws, rules, and regulations of the Republic of the Philippines.</td>
                        <td colspan="4" class="pds-label">Government Issued ID</td>
                        <td colspan="4" class="pds-value">{{ $field('other.government_id_type', true) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="pds-label">ID/License/Passport No.</td>
                        <td colspan="4" class="pds-value">{{ $field('other.government_id_no', true) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="pds-label">Date/Place of Issuance</td>
                        <td colspan="4" class="pds-value">{{ $field('other.government_id_date_place_issued', true) }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="pds-value text-center" style="height: 46px;">
                            {{ $field('other.signature_name') }}<br><span class="pds-small">Signature (Sign inside the
                                box)</span></td>
                        <td colspan="4" class="pds-label">Date Accomplished</td>
                        <td colspan="4" class="pds-value">{{ $field('other.date_accomplished') }}</td>
                    </tr>
                    <tr>
                        <td colspan="13" class="pds-small">SUBSCRIBED AND SWORN to before me this, affiant exhibiting his/her
                            validly issued government ID as indicated above.</td>
                    </tr>
                    <tr>
                        <td colspan="13" class="pds-value text-center" style="height: 42px;">Person Administering Oath</td>
                    </tr>
                    <tr>
                        <td colspan="13" class="pds-label text-right">CS FORM 212 (Revised 2025), Page 4 of 4</td>
                    </tr>
                </table>
            </section>
        </article>
    @endif
@endsection
