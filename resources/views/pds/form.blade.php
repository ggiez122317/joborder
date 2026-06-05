@extends('layouts.app')

@section('content')
@php
    $value = fn (string $path, $default = '') => old($path, data_get($data, $path, $default));
    $employee = $employee ?? null;
    $officeOptions = $officeOptions ?? [];
    $profilePhotoUrl = $profilePhotoUrl ?? null;
    $profilePlaceholderUrl = asset('assets/profile-placeholder.svg');
    $portalMode = $portalMode ?? 'admin';
    $photoError = $errors->first('profile_photo');
    $educationLevels = \App\Services\PdsDataService::EDUCATION_LEVELS;
    $duplicateContext = $duplicateContext ?? null;
    $duplicateFields = collect($duplicateContext['fields'] ?? [])->flip();
    $duplicateCompareEmployee = $duplicateContext['compare_employee'] ?? null;
    $duplicateCompareData = $duplicateCompareEmployee ? app(\App\Services\PdsDataService::class)->fromEmployee($duplicateCompareEmployee) : null;
    $isDuplicateField = fn (string $path) => $duplicateFields->has($path);
    $duplicateCompareValue = fn (string $path) => data_get($duplicateCompareData, $path);
    $reviewContext = $reviewContext ?? null;
    $reviewFields = collect($reviewContext['field_paths'] ?? [])->flip();
    $reviewLabels = $reviewContext['missing_labels'] ?? [];
    $reviewFocusStep = $reviewContext['focus_step'] ?? 0;
    $isReviewField = fn (string $path) => $reviewFields->has($path);
    $questions = [
        'related_third_degree' => '34.a. Related within the third degree?',
        'related_fourth_degree_lgu' => '34.b. Related within the fourth degree for LGU career employees?',
        'related_details' => '34. If YES, give details',
        'administrative_offense' => '35.a. Found guilty of any administrative offense?',
        'administrative_offense_details' => '35.a. If YES, give details',
        'criminally_charged' => '35.b. Criminally charged before any court?',
        'criminal_date_filed' => '35.b. Date Filed',
        'criminal_case_status' => '35.b. Status of Case/s',
        'convicted_crime' => '36. Convicted of any crime or violation?',
        'convicted_crime_details' => '36. If YES, give details',
        'separated_service' => '37. Separated from service by resignation, retirement, dismissal, termination, end of term, finished contract or phase out?',
        'separated_service_details' => '37. If YES, give details',
        'candidate_last_year' => '38.a. Candidate in a national or local election within the last year?',
        'candidate_details' => '38.a. If YES, give details',
        'resigned_before_election' => '38.b. Resigned during the three-month period before the last election to campaign?',
        'resigned_details' => '38.b. If YES, give details',
        'immigrant_status' => '39. Immigrant or permanent resident of another country?',
        'immigrant_country' => '39. If YES, give country',
        'indigenous_group' => '40.a. Member of any indigenous group?',
        'indigenous_group_details' => '40.a. If YES, specify',
        'person_with_disability' => '40.b. Person with disability?',
        'pwd_id_no' => '40.b. If YES, specify ID No.',
        'solo_parent' => '40.c. Solo parent?',
        'solo_parent_id_no' => '40.c. If YES, specify ID No.',
    ];
@endphp

<style>
    .pds-form-shell .panel {
        border-color: #d7efe0;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.04);
    }
    .pds-form-shell .panel-heading {
        border-color: #d7efe0;
        background: linear-gradient(135deg, #f7fcf8, #eefbf1);
        color: #15803d;
    }
    .pds-form-shell .form-accent-border {
        border-color: #bbf7d0;
    }
    .pds-form-shell .form-accent-table th,
    .pds-form-shell .form-accent-table td {
        border-color: #bbf7d0;
    }
    .pds-form-shell .step-tab {
        border-color: #bbf7d0;
        background: #ffffff;
        color: #166534;
        transition: background-color .2s ease, color .2s ease, border-color .2s ease, box-shadow .2s ease;
    }
    .pds-form-shell .step-tab.is-active {
        border-color: #16a34a;
        background: linear-gradient(135deg, #16a34a, #15803d);
        color: #ffffff;
        box-shadow: 0 12px 24px rgba(22, 163, 74, 0.18);
    }
    .pds-form-shell .duplicate-card {
        border: 1px solid #fde68a;
        background: linear-gradient(135deg, #fffdf2, #fffbeb);
    }
    .pds-form-shell .review-card {
        border: 1px solid #fecaca;
        background: linear-gradient(135deg, #fff7f7, #fff1f2);
    }
    .pds-form-shell .duplicate-highlight {
        border-color: #f59e0b !important;
        background: #fff7ed;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.12);
    }
    .pds-form-shell .review-highlight {
        border-color: #f97316 !important;
        background: #fff7ed !important;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.12);
    }
    .pds-form-shell .duplicate-chip {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        background: #fef3c7;
        color: #92400e;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
    }
    .pds-form-shell .review-chip {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        background: #fed7aa;
        color: #9a3412;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
    }
</style>

<div class="pds-form-shell">
<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="text-xl font-bold uppercase">{{ $mode === 'upload' ? 'Review Uploaded PDS' : ($mode === 'edit' ? 'Edit Personal Data Sheet' : 'Encode Personal Data Sheet') }}</h1>
        <p class="text-sm font-semibold">{{ $portalMode === 'user' ? 'Fill out your personal data sheet using the same LGU format used by administrators.' : ($portalMode === 'user-record' ? 'Encode a PDS record for another employee.' : 'CS Form No. 212 Revised 2025, LGU TRENTO.') }}</p>
    </div>
    @if ($portalMode === 'user')
        <a href="{{ route('user.dashboard') }}" class="btn-secondary">Back to Dashboard</a>
    @elseif ($portalMode === 'user-record')
        <div class="flex gap-2">
            <a href="{{ route('user.records') }}" class="btn-secondary">My Records</a>
        </div>
    @else
        <a href="{{ route('records.index') }}" class="btn-secondary">View Records</a>
    @endif
</div>

@if (! empty($uploadNotice))
    <div class="mb-4 rounded-[12px] border border-[#bbf7d0] bg-[#f7fcf8] px-4 py-3 text-sm font-semibold text-[#166534]">{{ $uploadNotice }}</div>
@endif

@if ($errors->any())
    <div class="mb-4 rounded-[12px] border border-[#fecaca] bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
        Please review the highlighted fields and save again.
    </div>
@endif

@if ($reviewContext && !empty($reviewLabels))
    <div class="review-card mb-4 rounded-[14px] px-4 py-4">
        <div class="text-sm font-bold uppercase text-[#9a3412]">Admin follow-up required</div>
        <p class="mt-1 text-sm text-[#7c2d12]">Your PDS needs a few missing details before it can be finalized. The highlighted fields below are the items you need to fill in.</p>
        <div class="mt-3 flex flex-wrap gap-2">
            @foreach ($reviewLabels as $label)
                <span class="review-chip">{{ $label }}</span>
            @endforeach
        </div>
    </div>
@endif

@if ($duplicateContext && $duplicateCompareEmployee)
    <div class="duplicate-card mb-4 rounded-[14px] px-4 py-4">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <div class="text-sm font-bold uppercase text-[#92400e]">Duplicate review mode</div>
                <div class="mt-1 text-sm text-[#78350f]">
                    Reason: <span class="font-semibold">{{ $duplicateContext['reason'] }}</span>
                    @if (!empty($duplicateContext['match_key']))
                        · Match key: <span class="font-semibold">{{ $duplicateContext['match_key'] }}</span>
                    @endif
                </div>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach ($duplicateContext['fields'] as $field)
                        <span class="duplicate-chip">{{ str_replace('personal.', '', $field) }}</span>
                    @endforeach
                </div>
            </div>
            <a href="{{ route('pds.edit', $employee) }}" class="btn-secondary">Exit duplicate review</a>
        </div>

        <div class="mt-4 grid gap-3 md:grid-cols-2">
            <div class="rounded-[12px] border border-[#fcd34d] bg-white px-4 py-3">
                <div class="text-xs font-bold uppercase tracking-[0.08em] text-[#b45309]">Current record</div>
                <div class="mt-2 text-sm font-semibold text-[#0f172a]">{{ $employee->full_name }}</div>
                <div class="mt-2 space-y-1 text-sm text-[#475569]">
                    <div>Employee Code: <span class="font-semibold">{{ data_get($data, 'personal.agency_employee_no') ?: 'N/A' }}</span></div>
                    <div>Birthdate: <span class="font-semibold">{{ data_get($data, 'personal.date_of_birth') ?: 'N/A' }}</span></div>
                    <div>Office: <span class="font-semibold">{{ data_get($data, 'personal.office') ?: 'N/A' }}</span></div>
                </div>
            </div>
            <div class="rounded-[12px] border border-[#fcd34d] bg-white px-4 py-3">
                <div class="text-xs font-bold uppercase tracking-[0.08em] text-[#b45309]">Compare against</div>
                <div class="mt-2 text-sm font-semibold text-[#0f172a]">{{ $duplicateCompareEmployee->full_name }}</div>
                <div class="mt-2 space-y-1 text-sm text-[#475569]">
                    <div>Employee Code: <span class="font-semibold">{{ data_get($duplicateCompareData, 'personal.agency_employee_no') ?: 'N/A' }}</span></div>
                    <div>Birthdate: <span class="font-semibold">{{ data_get($duplicateCompareData, 'personal.date_of_birth') ?: 'N/A' }}</span></div>
                    <div>Office: <span class="font-semibold">{{ data_get($duplicateCompareData, 'personal.office') ?: 'N/A' }}</span></div>
                </div>
            </div>
        </div>
    </div>
@endif

@php
    if ($portalMode === 'user') {
        $formAction = route('user.pds.save');
    } elseif ($portalMode === 'user-record') {
        $formAction = $mode === 'edit' && $employee ? route('user.records.update', $employee) : route('user.records.store');
    } else {
        $formAction = $mode === 'edit' && $employee ? route('pds.update', $employee) : route('pds.store');
    }
@endphp
<form method="POST" action="{{ $formAction }}" class="space-y-4" id="pdsWizard" enctype="multipart/form-data">
    @csrf
    @if ($mode === 'edit' && $portalMode !== 'user')
        @method('PUT')
    @endif
    <input type="hidden" name="source_file" value="{{ $sourceFile }}">
    <input type="hidden" name="import_history_id" value="{{ $importHistoryId ?? '' }}">
    <input type="hidden" name="drawn_signature" id="drawnSignatureInput" value="{{ old('drawn_signature') }}">

    <div class="panel p-3">
        <div class="grid gap-2 text-xs font-bold uppercase md:grid-cols-4 lg:grid-cols-8">
            @foreach (['Personal Information', 'Family Background', 'Educational Background', 'Civil Service Eligibility', 'Work Experience', 'Voluntary Work', 'Learning & Development', 'Other Information'] as $index => $step)
                <button type="button" class="step-tab rounded-[10px] border px-2 py-2" data-step-button="{{ $index }}">{{ $step }}</button>
            @endforeach
        </div>
    </div>

    <section class="wizard-step panel" data-step="0">
        <div class="panel-heading">I. Personal Information</div>
        <div class="form-accent-border border-b p-4">
            <div class="grid gap-6 md:grid-cols-2 items-start">
                <!-- Profile Picture Card (Perfect Uniform Alignment) -->
                <div class="rounded-xl border border-[#cbd5e1] bg-[#f8fafc] p-3 relative" style="display: flex; flex-direction: column; justify-content: space-between; min-height: 280px; border-radius: 12px; border: 1px solid #cbd5e1; background-color: #f8fafc; padding: 12px; box-sizing: border-box;">
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #cbd5e1; padding-bottom: 6px; margin-bottom: 10px;">
                        <span style="font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.04em; color: #334155;">Profile Picture</span>
                        <span style="font-size: 10px; color: #94a3b8; font-weight: bold; text-transform: uppercase;">Card ID Photo</span>
                    </div>

                    <!-- Centered Preview Wrapper -->
                    <div style="display: flex; justify-content: center; align-items: center; height: 170px; margin-bottom: 8px;">
                        <div style="width: 150px; height: 150px; border-radius: 8px; border: 1px dashed #cbd5e1; background-color: #ffffff; padding: 4px; display: flex; align-items: center; justify-content: center; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); box-sizing: border-box;">
                            <img id="profilePhotoImage" src="{{ $profilePhotoUrl ?: $profilePlaceholderUrl }}" alt="Profile preview" style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px;">
                        </div>
                    </div>

                    <!-- Bottom Controls -->
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 4px;">
                        <div style="position: relative; display: inline-block;">
                            <!-- Hidden input -->
                            <input id="profile_photo" type="file" name="profile_photo" accept="image/png,image/jpeg,image/jpg,image/webp" style="position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 10;">
                            <!-- Styled custom button -->
                            <div style="display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); font-size: 10px; font-weight: bold; color: #475569;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px; color: #64748b;">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                </svg>
                                <span id="profilePhotoName">Choose image...</span>
                            </div>
                        </div>
                        <p style="font-size: 8.5px; color: #64748b; margin: 2px 0 0 0; text-align: center; line-height: 1.2;">
                            Appears on the QR profile card.
                        </p>
                    </div>
                    @if ($photoError)
                        <p style="margin-top: 4px; font-size: 10px; font-weight: 600; color: #dc2626; text-align: center;">{{ $photoError }}</p>
                    @endif
                </div>

                <!-- E-Signature Section (Perfect Uniform Alignment) -->
                <div class="rounded-xl border border-[#cbd5e1] bg-[#f8fafc] p-3 relative" style="display: flex; flex-direction: column; justify-content: space-between; min-height: 280px; border-radius: 12px; border: 1px solid #cbd5e1; background-color: #f8fafc; padding: 12px; box-sizing: border-box;">
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #cbd5e1; padding-bottom: 6px; margin-bottom: 10px;">
                        <span style="font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.04em; color: #334155;">E-Signature Choice</span>
                        
                        <!-- Toggle switcher styled beautifully with pure CSS -->
                        <div style="display: inline-flex; background-color: #e2e8f0; padding: 2px; border-radius: 6px; box-sizing: border-box;">
                            <button type="button" id="tabDraw" style="border: none; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; color: #334155; background-color: #ffffff; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05); outline: none; transition: all 0.2s;">Draw Pad</button>
                            <button type="button" id="tabUpload" style="border: none; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; color: #64748b; background-color: transparent; cursor: pointer; outline: none; transition: all 0.2s; margin-left: 2px;">Upload File</button>
                        </div>
                    </div>

                    <!-- Panel 1: Draw Signature Pad -->
                    <div id="panelDraw" style="display: flex; flex-direction: column; justify-content: space-between; flex-grow: 1;">
                        <!-- Centered Canvas Wrapper -->
                        <div style="display: flex; justify-content: center; align-items: center; height: 170px; margin-bottom: 8px;">
                            <div style="width: 100%; height: 160px; border-radius: 8px; border: 1px solid #cbd5e1; background-color: #ffffff; overflow: hidden; display: flex; align-items: center; justify-content: center; position: relative; box-shadow: inset 0 1px 2px rgba(0,0,0,0.03); box-sizing: border-box;">
                                <canvas id="signatureCanvas" style="width: 100%; height: 100%; display: block; background-color: transparent; cursor: crosshair; touch-action: none;"></canvas>
                                <div id="canvasPlaceholder" style="position: absolute; pointer-events: none; color: #94a3b8; font-size: 10px; display: flex; align-items: center; gap: 4px; user-select: none;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 14px; height: 14px;">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                    </svg>
                                    <span>Sign here</span>
                                </div>
                            </div>
                        </div>

                        <!-- Controls -->
                        <div style="display: flex; align-items: center; justify-content: space-between; width: 100%; font-size: 9px; color: #64748b; padding: 0 4px;">
                            <span>Draw inside the pad</span>
                            <button type="button" id="clearPadBtn" style="border: none; background-color: #fef2f2; color: #dc2626; font-size: 9.5px; font-weight: bold; padding: 2px 8px; border-radius: 4px; cursor: pointer; transition: background-color 0.2s;">Clear</button>
                        </div>
                    </div>

                    <!-- Panel 2: Upload File -->
                    <div id="panelUpload" style="display: none; flex-direction: column; justify-content: space-between; flex-grow: 1;">
                        <!-- Centered Preview Wrapper -->
                        <div style="display: flex; justify-content: center; align-items: center; height: 170px; margin-bottom: 8px;">
                            <div style="width: 250px; height: 150px; border-radius: 8px; border: 1px dashed #cbd5e1; background-color: #ffffff; padding: 4px; display: flex; align-items: center; justify-content: center; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); box-sizing: border-box;">
                                <img id="eSignatureImage" src="{{ ($employee && $employee->e_signature_path) ? ($portalMode === 'user' ? route('user.signature', $employee) : route('profile.signature', $employee)) : asset('assets/profile-placeholder.svg') }}" alt="Signature preview" style="width: 100%; height: 100%; object-fit: contain; border-radius: 6px;">
                            </div>
                        </div>

                        <!-- Controls -->
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 4px;">
                            <div style="position: relative; display: inline-block;">
                                <!-- Hidden input -->
                                <input id="e_signature" type="file" name="e_signature" accept="image/png,image/jpeg,image/jpg,image/webp" style="position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 10;">
                                <!-- Styled custom button -->
                                <div style="display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); font-size: 10px; font-weight: bold; color: #475569;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px; color: #64748b;">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                    </svg>
                                    <span id="signatureFileName">Choose file...</span>
                                </div>
                            </div>
                            <p style="font-size: 8.5px; color: #64748b; margin: 2px 0 0 0; text-align: center; line-height: 1.2;">
                                Use a transparent or white background image.
                            </p>
                        </div>
                    </div>

                    @if ($errors->has('e_signature'))
                        <p style="margin-top: 4px; font-size: 10px; font-weight: 600; color: #dc2626; text-align: center;">{{ $errors->first('e_signature') }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="grid gap-4 p-4 md:grid-cols-3">
            <div>
                <label class="form-label">1. Surname</label>
                <input name="personal[surname]" value="{{ $value('personal.surname') }}" class="form-input {{ $isDuplicateField('personal.surname') ? 'duplicate-highlight' : '' }}">
                @if ($isDuplicateField('personal.surname'))
                    <p class="mt-1 text-xs font-semibold text-[#b45309]">Other record: {{ $duplicateCompareValue('personal.surname') ?: 'blank' }}</p>
                @endif
            </div>
            <div>
                <label class="form-label">2. First Name</label>
                <input name="personal[first_name]" value="{{ $value('personal.first_name') }}" class="form-input {{ $isDuplicateField('personal.first_name') ? 'duplicate-highlight' : '' }}">
                @if ($isDuplicateField('personal.first_name'))
                    <p class="mt-1 text-xs font-semibold text-[#b45309]">Other record: {{ $duplicateCompareValue('personal.first_name') ?: 'blank' }}</p>
                @endif
            </div>
            <div>
                <label class="form-label">Nickname</label>
                <input name="personal[nickname]" value="{{ $value('personal.nickname') }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Name Extension (JR., SR)</label>
                <input name="personal[name_extension]" value="{{ $value('personal.name_extension') }}" class="form-input {{ $isDuplicateField('personal.name_extension') ? 'duplicate-highlight' : '' }}">
                @if ($isDuplicateField('personal.name_extension'))
                    <p class="mt-1 text-xs font-semibold text-[#b45309]">Other record: {{ $duplicateCompareValue('personal.name_extension') ?: 'blank' }}</p>
                @endif
            </div>
            <div>
                <label class="form-label">Middle Name</label>
                <input name="personal[middle_name]" value="{{ $value('personal.middle_name') }}" class="form-input {{ $isDuplicateField('personal.middle_name') ? 'duplicate-highlight' : '' }}">
                @if ($isDuplicateField('personal.middle_name'))
                    <p class="mt-1 text-xs font-semibold text-[#b45309]">Other record: {{ $duplicateCompareValue('personal.middle_name') ?: 'blank' }}</p>
                @endif
            </div>
            <div>
                <label class="form-label">3. Date of Birth (dd/mm/yyyy)</label>
                <input type="date" name="personal[date_of_birth]" value="{{ $value('personal.date_of_birth') }}" class="form-input {{ $isDuplicateField('personal.date_of_birth') ? 'duplicate-highlight' : '' }}">
                @if ($isDuplicateField('personal.date_of_birth'))
                    <p class="mt-1 text-xs font-semibold text-[#b45309]">Other record: {{ $duplicateCompareValue('personal.date_of_birth') ?: 'blank' }}</p>
                @endif
            </div>
            <div><label class="form-label">4. Place of Birth</label><input name="personal[place_of_birth]" value="{{ $value('personal.place_of_birth') }}" class="form-input"></div>
            <div>
                <label class="form-label">5. Sex at Birth</label>
                <select name="personal[sex_at_birth]" class="form-input">
                    <option value=""></option>
                    <option value="Male" @selected($value('personal.sex_at_birth') === 'Male')>Male</option>
                    <option value="Female" @selected($value('personal.sex_at_birth') === 'Female')>Female</option>
                </select>
            </div>
            <div><label class="form-label">6. Civil Status</label><input name="personal[civil_status]" value="{{ $value('personal.civil_status') }}" class="form-input"></div>
            <div><label class="form-label">7. Height (m)</label><input name="personal[height_m]" value="{{ $value('personal.height_m') }}" class="form-input"></div>
            <div><label class="form-label">8. Weight (kg)</label><input name="personal[weight_kg]" value="{{ $value('personal.weight_kg') }}" class="form-input"></div>
            <div><label class="form-label">9. Blood Type</label><input name="personal[blood_type]" value="{{ $value('personal.blood_type') }}" class="form-input"></div>
            <div><label class="form-label">10. UMID ID No.</label><input name="personal[umid_id_no]" value="{{ $value('personal.umid_id_no') }}" class="form-input"></div>
            <div><label class="form-label">11. PAG-IBIG ID No.</label><input name="personal[pagibig_id_no]" value="{{ $value('personal.pagibig_id_no') }}" class="form-input"></div>
            <div><label class="form-label">12. PhilHealth No.</label><input name="personal[philhealth_no]" value="{{ $value('personal.philhealth_no') }}" class="form-input"></div>
            <div><label class="form-label">13. PhilSys Number (PSN)</label><input name="personal[philsys_no]" value="{{ $value('personal.philsys_no') }}" class="form-input"></div>
            <div><label class="form-label">14. TIN No.</label><input name="personal[tin_no]" value="{{ $value('personal.tin_no') }}" class="form-input"></div>
            <div>
                <label class="form-label">15. Agency Employee No.</label>
                <input name="personal[agency_employee_no]" value="{{ $value('personal.agency_employee_no') }}" class="form-input {{ $isDuplicateField('personal.agency_employee_no') ? 'duplicate-highlight' : '' }}">
                @if ($isDuplicateField('personal.agency_employee_no'))
                    <p class="mt-1 text-xs font-semibold text-[#b45309]">Other record: {{ $duplicateCompareValue('personal.agency_employee_no') ?: 'blank' }}</p>
                @endif
            </div>
            <div><label class="form-label">16. Citizenship</label><input name="personal[citizenship]" value="{{ $value('personal.citizenship') }}" class="form-input"></div>
            <div><label class="form-label">Citizenship Details</label><input name="personal[citizenship_basis]" value="{{ $value('personal.citizenship_basis') }}" class="form-input"></div>
            <div><label class="form-label">Please Indicate Country</label><input name="personal[dual_citizenship_country]" value="{{ $value('personal.dual_citizenship_country') }}" class="form-input"></div>
            <div><label class="form-label">19. Telephone No.</label><input name="personal[telephone_no]" value="{{ $value('personal.telephone_no') }}" class="form-input {{ $isReviewField('personal.telephone_no') ? 'review-highlight' : '' }}"></div>
            <div><label class="form-label">20. Mobile No.</label><input name="personal[mobile_no]" value="{{ $value('personal.mobile_no') }}" class="form-input {{ $isReviewField('personal.mobile_no') ? 'review-highlight' : '' }}"></div>
            <div><label class="form-label">21. E-mail Address (if any)</label><input name="personal[email_address]" value="{{ $value('personal.email_address') }}" class="form-input {{ $isReviewField('personal.email_address') ? 'review-highlight' : '' }}"></div>
            <div><label class="form-label">Job Order</label><input name="personal[job_order]" value="{{ $value('personal.job_order') }}" class="form-input"></div>
            <div>
                <label class="form-label">Office</label>
                <select name="{{ $portalMode === 'user' ? '_personal_office_disabled' : 'personal[office]' }}" class="form-input {{ $isReviewField('personal.office') ? 'review-highlight' : '' }}" {{ $portalMode === 'user' ? 'disabled' : '' }}>
                    <option value="">Select office</option>
                    @foreach ($officeOptions as $officeOption)
                        <option value="{{ $officeOption }}" @selected($value('personal.office') === $officeOption)>{{ $officeOption }}</option>
                    @endforeach
                </select>
                @if($portalMode === 'user')
                    <input type="hidden" name="personal[office]" value="{{ $value('personal.office') }}">
                @endif
                <p class="mt-2 text-xs text-[#64748b]">
                    This office is saved to the employee record and used in Profile, Records, Dashboard, and Offices pages.
                    @if($portalMode === 'user') <br><span class="text-amber-600">You cannot change your registered office. Contact HR to edit.</span> @endif
                </p>
            </div>
        </div>

        <div class="form-accent-border grid gap-4 border-t p-4 md:grid-cols-2">
            <div>
                <h3 class="mb-3 text-sm font-bold uppercase">17. Residential Address</h3>
                <div class="grid gap-3 md:grid-cols-2">
                    <input name="personal[residential_house_no]" value="{{ $value('personal.residential_house_no') }}" class="form-input mt-0" placeholder="House/Block/Lot No.">
                    <input name="personal[residential_street]" value="{{ $value('personal.residential_street') }}" class="form-input mt-0" placeholder="Street">
                    <input name="personal[residential_subdivision]" value="{{ $value('personal.residential_subdivision') }}" class="form-input mt-0" placeholder="Subdivision/Village">
                    <input name="personal[residential_barangay]" value="{{ $value('personal.residential_barangay') }}" class="form-input mt-0" placeholder="Barangay">
                    <input name="personal[residential_city]" value="{{ $value('personal.residential_city') }}" class="form-input mt-0" placeholder="City/Municipality">
                    <input name="personal[residential_province]" value="{{ $value('personal.residential_province') }}" class="form-input mt-0" placeholder="Province">
                    <input name="personal[residential_zip_code]" value="{{ $value('personal.residential_zip_code') }}" class="form-input mt-0" placeholder="ZIP Code">
                </div>
            </div>
            <div>
                <h3 class="mb-3 text-sm font-bold uppercase">18. Permanent Address</h3>
                <div class="grid gap-3 md:grid-cols-2">
                    <input name="personal[permanent_house_no]" value="{{ $value('personal.permanent_house_no') }}" class="form-input mt-0" placeholder="House/Block/Lot No.">
                    <input name="personal[permanent_street]" value="{{ $value('personal.permanent_street') }}" class="form-input mt-0" placeholder="Street">
                    <input name="personal[permanent_subdivision]" value="{{ $value('personal.permanent_subdivision') }}" class="form-input mt-0" placeholder="Subdivision/Village">
                    <input name="personal[permanent_barangay]" value="{{ $value('personal.permanent_barangay') }}" class="form-input mt-0" placeholder="Barangay">
                    <input name="personal[permanent_city]" value="{{ $value('personal.permanent_city') }}" class="form-input mt-0" placeholder="City/Municipality">
                    <input name="personal[permanent_province]" value="{{ $value('personal.permanent_province') }}" class="form-input mt-0" placeholder="Province">
                    <input name="personal[permanent_zip_code]" value="{{ $value('personal.permanent_zip_code') }}" class="form-input mt-0" placeholder="ZIP Code">
                </div>
            </div>
        </div>
    </section>

    <section class="wizard-step panel hidden" data-step="1">
        <div class="panel-heading">II. Family Background</div>
        <div class="grid gap-4 p-4 lg:grid-cols-2">
            <div class="grid gap-3 md:grid-cols-2">
                @foreach ([
                    'spouse_surname' => "22. Spouse's Surname",
                    'spouse_first_name' => 'Spouse First Name',
                    'spouse_name_extension' => 'Name Extension (JR., SR)',
                    'spouse_middle_name' => 'Spouse Middle Name',
                    'spouse_occupation' => 'Occupation',
                    'spouse_employer_business_name' => 'Employer/Business Name',
                    'spouse_business_address' => 'Business Address',
                    'spouse_telephone_no' => 'Telephone No.',
                    'father_surname' => "24. Father's Surname",
                    'father_first_name' => 'Father First Name',
                    'father_name_extension' => 'Father Name Extension (JR., SR)',
                    'father_middle_name' => 'Father Middle Name',
                    'mother_maiden_name' => "25. Mother's Maiden Name",
                    'mother_surname' => 'Mother Surname',
                    'mother_first_name' => 'Mother First Name',
                    'mother_middle_name' => 'Mother Middle Name',
                ] as $field => $label)
                    <div><label class="form-label">{{ $label }}</label><input name="family[{{ $field }}]" value="{{ $value('family.' . $field) }}" class="form-input"></div>
                @endforeach
            </div>
            <div>
                <h3 class="mb-3 text-sm font-bold uppercase">23. Name of Children</h3>
                <div class="overflow-x-auto">
                    <table class="form-accent-table w-full border-collapse text-sm">
                        <thead class="bg-[#F3F4F6]">
                            <tr><th class="border px-2 py-2 text-left">Full Name</th><th class="border px-2 py-2 text-left">Date of Birth (dd/mm/yyyy)</th></tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 12; $i++)
                                <tr>
                                    <td class="border p-1"><input name="children[{{ $i }}][name]" value="{{ $value("children.$i.name") }}" class="w-full border-0 bg-white px-2 py-1 outline-none"></td>
                                    <td class="border p-1"><input name="children[{{ $i }}][date_of_birth]" value="{{ $value("children.$i.date_of_birth") }}" class="w-full border-0 bg-white px-2 py-1 outline-none"></td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <section class="wizard-step panel hidden" data-step="2">
        <div class="panel-heading">III. Educational Background</div>
        <div class="overflow-x-auto p-4">
            <table class="form-accent-table w-full min-w-[1100px] border-collapse text-xs">
                <thead class="bg-[#F3F4F6]">
                    <tr>
                        <th class="border p-2">26. Level</th>
                        <th class="border p-2">Name of School (Write in full)</th>
                        <th class="border p-2">Basic Education/Degree/Course (Write in full)</th>
                        <th class="border p-2">Period From</th>
                        <th class="border p-2">Period To</th>
                        <th class="border p-2">Highest Level/Units Earned</th>
                        <th class="border p-2">Year Graduated</th>
                        <th class="border p-2">Scholarship/Academic Honors Received</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($educationLevels as $i => $level)
                        <tr>
                            <td class="border p-1 font-semibold">
                                {{ $level }}
                                <input type="hidden" name="education[{{ $i }}][level]" value="{{ $level }}">
                            </td>
                            @foreach (['school_name', 'degree_course', 'attendance_from', 'attendance_to', 'highest_level_units_earned', 'year_graduated', 'honors_received'] as $field)
                                <td class="border p-1"><input name="education[{{ $i }}][{{ $field }}]" value="{{ $value("education.$i.$field") }}" class="w-full border-0 bg-white px-2 py-1 outline-none"></td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section class="wizard-step panel hidden" data-step="3">
        <div class="panel-heading">IV. Civil Service Eligibility</div>
        <div class="overflow-x-auto p-4">
            <table class="form-accent-table w-full min-w-[1000px] border-collapse text-xs">
                <thead class="bg-[#F3F4F6]">
                    <tr>
                        <th class="border p-2">27. CES/CSEE/Career Service/RA 1080/Board/Bar/Special Laws/Eligibility</th>
                        <th class="border p-2">Rating</th>
                        <th class="border p-2">Date of Examination/Conferment</th>
                        <th class="border p-2">Place of Examination/Conferment</th>
                        <th class="border p-2">License Number</th>
                        <th class="border p-2">Valid Until</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < 7; $i++)
                        <tr>
                            @foreach (['career_service', 'rating', 'examination_date', 'examination_place', 'license_number', 'license_valid_until'] as $field)
                                <td class="border p-1"><input name="eligibility[{{ $i }}][{{ $field }}]" value="{{ $value("eligibility.$i.$field") }}" class="w-full border-0 bg-white px-2 py-1 outline-none"></td>
                            @endforeach
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </section>

    <section class="wizard-step panel hidden" data-step="4">
        <div class="panel-heading">V. Work Experience</div>
        <div class="overflow-x-auto p-4">
            <table class="form-accent-table w-full min-w-[1100px] border-collapse text-xs">
                <thead class="bg-[#F3F4F6]">
                    <tr>
                        <th class="border p-2">28. From</th>
                        <th class="border p-2">To</th>
                        <th class="border p-2">Position Title (Write in full/Do not abbreviate)</th>
                        <th class="border p-2">Department / Agency / Office / Company</th>
                        <th class="border p-2">Status of Appointment</th>
                        <th class="border p-2">Gov't Service (Y/N)</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < 28; $i++)
                        <tr>
                            @foreach (['date_from', 'date_to', 'position_title', 'department_agency_office_company', 'status_of_appointment', 'government_service'] as $field)
                                <td class="border p-1"><input name="work_experience[{{ $i }}][{{ $field }}]" value="{{ $value("work_experience.$i.$field") }}" class="w-full border-0 bg-white px-2 py-1 outline-none {{ $isReviewField("work_experience.$i.$field") ? 'review-highlight' : '' }}"></td>
                            @endforeach
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </section>

    <section class="wizard-step panel hidden" data-step="5">
        <div class="panel-heading">VI. Voluntary Work or Involvement in Civic / Non-Government / People / Voluntary Organization/s</div>
        <div class="overflow-x-auto p-4">
            <table class="form-accent-table w-full min-w-[1000px] border-collapse text-xs">
                <thead class="bg-[#F3F4F6]">
                    <tr>
                        <th class="border p-2">29. Name & Address of Organization (Write in full)</th>
                        <th class="border p-2">From</th>
                        <th class="border p-2">To</th>
                        <th class="border p-2">Number of Hours</th>
                        <th class="border p-2">Position / Nature of Work</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < 7; $i++)
                        <tr>
                            @foreach (['organization_name_address', 'date_from', 'date_to', 'number_of_hours', 'position_nature_of_work'] as $field)
                                <td class="border p-1"><input name="voluntary_work[{{ $i }}][{{ $field }}]" value="{{ $value("voluntary_work.$i.$field") }}" class="w-full border-0 bg-white px-2 py-1 outline-none"></td>
                            @endforeach
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </section>

    <section class="wizard-step panel hidden" data-step="6">
        <div class="panel-heading">VII. Learning and Development (L&D) Interventions/Training Programs Attended</div>
        <div class="overflow-x-auto p-4">
            <table class="form-accent-table w-full min-w-[1100px] border-collapse text-xs">
                <thead class="bg-[#F3F4F6]">
                    <tr>
                        <th class="border p-2">30. Title of L&D Interventions/Training Programs (Write in full)</th>
                        <th class="border p-2">From</th>
                        <th class="border p-2">To</th>
                        <th class="border p-2">Number of Hours</th>
                        <th class="border p-2">Type of L&D</th>
                        <th class="border p-2">Conducted/Sponsored By</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < 21; $i++)
                        <tr>
                            @foreach (['title', 'date_from', 'date_to', 'number_of_hours', 'type_of_ld', 'conducted_sponsored_by'] as $field)
                                <td class="border p-1"><input name="trainings[{{ $i }}][{{ $field }}]" value="{{ $value("trainings.$i.$field") }}" class="w-full border-0 bg-white px-2 py-1 outline-none"></td>
                            @endforeach
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </section>

    <section class="wizard-step panel hidden" data-step="7">
        <div class="panel-heading">VIII. Other Information</div>
        <div class="grid gap-4 p-4 lg:grid-cols-3">
            @foreach ([
                'special_skills_hobbies' => '31. Special Skills and Hobbies',
                'non_academic_distinctions' => '32. Non-Academic Distinctions / Recognition',
                'memberships' => '33. Membership in Association/Organization',
            ] as $field => $label)
                <div>
                    <h3 class="mb-3 text-sm font-bold uppercase">{{ $label }}</h3>
                    @for ($i = 0; $i < 7; $i++)
                        <input name="other[{{ $field }}][{{ $i }}]" value="{{ $value("other.$field.$i") }}" class="form-input mb-2">
                    @endfor
                </div>
            @endforeach
        </div>

        <div class="form-accent-border border-t p-4">
            <h3 class="mb-3 text-sm font-bold uppercase">34-40. Additional Questions</h3>
            <div class="grid gap-3 md:grid-cols-2">
                @foreach ($questions as $field => $label)
                    <div>
                        <label class="form-label">{{ $label }}</label>
                        @if (str_contains($field, 'details') || str_contains($field, 'status') || str_contains($field, 'country') || str_contains($field, 'id_no') || str_contains($field, 'date_filed'))
                            <input name="other[questions][{{ $field }}]" value="{{ $value("other.questions.$field") }}" class="form-input">
                        @else
                            <select name="other[questions][{{ $field }}]" class="form-input">
                                <option value=""></option>
                                <option value="Yes" @selected($value("other.questions.$field") === 'Yes')>Yes</option>
                                <option value="No" @selected($value("other.questions.$field") === 'No')>No</option>
                            </select>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="form-accent-border grid gap-4 border-t p-4 lg:grid-cols-2">
            <div>
                <h3 class="mb-3 text-sm font-bold uppercase">41. References</h3>
                <div class="overflow-x-auto">
                    <table class="form-accent-table w-full border-collapse text-xs">
                        <thead class="bg-[#F3F4F6]">
                            <tr><th class="border p-2">Name</th><th class="border p-2">Office / Residential Address</th><th class="border p-2">Contact No. and/or Email</th></tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 3; $i++)
                                <tr>
                                    @foreach (['name', 'address', 'contact'] as $field)
                                        <td class="border p-1"><input name="other[references][{{ $i }}][{{ $field }}]" value="{{ $value("other.references.$i.$field") }}" class="w-full border-0 bg-white px-2 py-1 outline-none"></td>
                                    @endforeach
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="grid gap-3 md:grid-cols-2">
                <div><label class="form-label">Government Issued ID</label><input name="other[government_id_type]" value="{{ $value('other.government_id_type') }}" class="form-input"></div>
                <div><label class="form-label">ID/License/Passport No.</label><input name="other[government_id_no]" value="{{ $value('other.government_id_no') }}" class="form-input"></div>
                <div><label class="form-label">Date/Place of Issuance</label><input name="other[government_id_date_place_issued]" value="{{ $value('other.government_id_date_place_issued') }}" class="form-input"></div>
                <div><label class="form-label">Date Accomplished</label><input type="date" name="other[date_accomplished]" value="{{ $value('other.date_accomplished') }}" class="form-input"></div>
                <div><label class="form-label">Signature Name</label><input name="other[signature_name]" value="{{ $value('other.signature_name') }}" class="form-input"></div>
                <label class="flex items-center gap-2 text-sm font-semibold">
                    <input type="hidden" name="other[visibility][show_contact]" value="0">
                    <input type="checkbox" name="other[visibility][show_contact]" value="1" @checked((bool) $value('other.visibility.show_contact'))>
                    <span>Show contact fields on profile</span>
                </label>
                <label class="flex items-center gap-2 text-sm font-semibold">
                    <input type="hidden" name="other[visibility][show_identifiers]" value="0">
                    <input type="checkbox" name="other[visibility][show_identifiers]" value="1" @checked((bool) $value('other.visibility.show_identifiers'))>
                    <span>Show government ID fields on profile</span>
                </label>
            </div>
        </div>
    </section>

    <div class="print-hidden flex flex-wrap justify-between gap-2">
        <button type="button" class="btn-secondary" id="prevStep">Previous Section</button>
        <div class="flex gap-2">
            <button type="button" class="btn-secondary" id="nextStep">Next Section</button>
            <button type="submit" class="btn-primary">
                @if ($portalMode === 'user')
                    {{ $mode === 'edit' ? 'Update My PDS' : 'Save My PDS' }}
                @elseif ($portalMode === 'user-record')
                    {{ $mode === 'edit' ? 'Update Record' : 'Save Record' }}
                @else
                    {{ $mode === 'edit' ? 'Update PDS and Refresh QR' : 'Save PDS and Generate QR' }}
                @endif
            </button>
        </div>
    </div>
</form>
</div>

<script>
    (() => {
        const steps = Array.from(document.querySelectorAll('.wizard-step'));
        const tabs = Array.from(document.querySelectorAll('.step-tab'));
        const prev = document.getElementById('prevStep');
        const next = document.getElementById('nextStep');
        const profilePhotoInput = document.getElementById('profile_photo');
        const profilePhotoPreview = document.getElementById('profilePhotoImage');
        let current = Number(@js($reviewFocusStep));

        const show = (index) => {
            current = Math.max(0, Math.min(index, steps.length - 1));
            steps.forEach((step, i) => step.classList.toggle('hidden', i !== current));
            tabs.forEach((tab, i) => {
                tab.classList.toggle('is-active', i === current);
            });
            prev.disabled = current === 0;
            next.disabled = current === steps.length - 1;
        };

        tabs.forEach((tab, index) => tab.addEventListener('click', () => show(index)));
        prev.addEventListener('click', () => show(current - 1));
        next.addEventListener('click', () => show(current + 1));

        if (profilePhotoInput && profilePhotoPreview) {
            profilePhotoInput.addEventListener('change', (event) => {
                const [file] = event.target.files || [];
                const nameDisplay = document.getElementById('profilePhotoName');

                if (!file) {
                    profilePhotoPreview.src = @js($profilePhotoUrl ?: $profilePlaceholderUrl);
                    if (nameDisplay) nameDisplay.textContent = 'Choose file...';
                    return;
                }

                const objectUrl = URL.createObjectURL(file);
                profilePhotoPreview.src = objectUrl;
                if (nameDisplay) {
                    nameDisplay.textContent = file.name.length > 20 ? file.name.substring(0, 17) + '...' : file.name;
                }
            });
        }

        // Tab switching and signature pad variables
        const tabDraw = document.getElementById('tabDraw');
        const tabUpload = document.getElementById('tabUpload');
        const panelDraw = document.getElementById('panelDraw');
        const panelUpload = document.getElementById('panelUpload');
        const canvas = document.getElementById('signatureCanvas');
        const ctx = canvas ? canvas.getContext('2d') : null;
        const placeholder = document.getElementById('canvasPlaceholder');
        const clearPadBtn = document.getElementById('clearPadBtn');
        const eSignatureInput = document.getElementById('e_signature');
        const eSignaturePreview = document.getElementById('eSignatureImage');
        const defaultSignatureUrl = @js(($employee && $employee->e_signature_path) ? ($portalMode === 'user' ? route('user.signature', $employee) : route('profile.signature', $employee)) : asset('assets/profile-placeholder.svg'));
        const drawnSigInput = document.getElementById('drawnSignatureInput');
        
        let hasDrawn = false;
        let activeTab = 'draw';

        // Update Hidden Input with Base64 drawn signature
        function updateDrawnSignature() {
            if (canvas && hasDrawn && activeTab === 'draw') {
                if (drawnSigInput) {
                    drawnSigInput.value = canvas.toDataURL('image/png');
                }
            } else {
                if (drawnSigInput) {
                    // Do not clear on upload if there was a saved old drawing
                    if (activeTab !== 'draw') {
                        drawnSigInput.value = '';
                    }
                }
            }
        }

        // 1. Switch Tab Logic
        const switchSignatureTab = (tab) => {
            activeTab = tab;
            if (tab === 'draw') {
                if (tabDraw) {
                    tabDraw.style.backgroundColor = '#ffffff';
                    tabDraw.style.color = '#334155';
                    tabDraw.style.boxShadow = '0 1px 2px rgba(0,0,0,0.05)';
                }
                if (tabUpload) {
                    tabUpload.style.backgroundColor = 'transparent';
                    tabUpload.style.color = '#64748b';
                    tabUpload.style.boxShadow = 'none';
                }
                if (panelDraw) panelDraw.style.display = 'flex';
                if (panelUpload) panelUpload.style.display = 'none';
                setTimeout(resizeCanvas, 50);
            } else {
                if (tabUpload) {
                    tabUpload.style.backgroundColor = '#ffffff';
                    tabUpload.style.color = '#334155';
                    tabUpload.style.boxShadow = '0 1px 2px rgba(0,0,0,0.05)';
                }
                if (tabDraw) {
                    tabDraw.style.backgroundColor = 'transparent';
                    tabDraw.style.color = '#64748b';
                    tabDraw.style.boxShadow = 'none';
                }
                if (panelUpload) panelUpload.style.display = 'flex';
                if (panelDraw) panelDraw.style.display = 'none';
            }
            updateDrawnSignature();
        };

        if (tabDraw && tabUpload) {
            tabDraw.addEventListener('click', () => switchSignatureTab('draw'));
            tabUpload.addEventListener('click', () => switchSignatureTab('upload'));
        }

        // 2. Canvas Drawing Logic
        let currentDpr = 1;
        let isFirstInit = true;
        
        function resizeCanvas() {
            if (!canvas) return;
            const rect = canvas.getBoundingClientRect();
            currentDpr = window.devicePixelRatio || 1;
            
            // Set canvas buffer scaled size
            canvas.width = rect.width * currentDpr;
            canvas.height = rect.height * currentDpr;
            
            // Set element layout size
            canvas.style.width = rect.width + 'px';
            canvas.style.height = rect.height + 'px';
            
            // Scale and configure drawing settings
            ctx.scale(currentDpr, currentDpr);
            ctx.lineWidth = 2.8;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.strokeStyle = '#0f172a'; // slate-900 color for smooth drawings
            ctx.clearRect(0, 0, rect.width, rect.height);
            hasDrawn = false;
            if (placeholder) placeholder.style.display = 'flex';

            // Restore from validation error state
            const savedDrawnSig = drawnSigInput ? drawnSigInput.value : '';
            if (isFirstInit && savedDrawnSig && savedDrawnSig.startsWith('data:image/')) {
                isFirstInit = false;
                const img = new Image();
                img.onload = function() {
                    ctx.drawImage(img, 0, 0, rect.width, rect.height);
                    hasDrawn = true;
                    if (placeholder) placeholder.style.display = 'none';
                    updateDrawnSignature();
                };
                img.src = savedDrawnSig;
            } else if (isFirstInit) {
                isFirstInit = false;
            }
        }

        if (canvas) {
            let drawing = false;
            let lastX = 0;
            let lastY = 0;
            let prevMidX = 0;
            let prevMidY = 0;
            let isFirstSegment = true;

            function getEventCoords(e) {
                const rect = canvas.getBoundingClientRect();
                if (e.touches && e.touches.length > 0) {
                    return {
                        x: e.touches[0].clientX - rect.left,
                        y: e.touches[0].clientY - rect.top
                    };
                }
                return {
                    x: e.clientX - rect.left,
                    y: e.clientY - rect.top
                };
            }

            function startDrawing(e) {
                drawing = true;
                hasDrawn = true;
                if (placeholder) placeholder.style.display = 'none';
                const coords = getEventCoords(e);
                lastX = coords.x;
                lastY = coords.y;
                isFirstSegment = true;
                
                // Draw single dot on touchstart/mousedown
                ctx.beginPath();
                ctx.arc(lastX, lastY, ctx.lineWidth / 2, 0, Math.PI * 2);
                ctx.fillStyle = ctx.strokeStyle;
                ctx.fill();
                updateDrawnSignature();
            }

            function draw(e) {
                if (!drawing) return;
                e.preventDefault();
                const coords = getEventCoords(e);
                
                const midX = (lastX + coords.x) / 2;
                const midY = (lastY + coords.y) / 2;

                ctx.beginPath();
                if (isFirstSegment) {
                    ctx.moveTo(lastX, lastY);
                    ctx.lineTo(midX, midY);
                    ctx.stroke();
                    isFirstSegment = false;
                } else {
                    ctx.moveTo(prevMidX, prevMidY);
                    ctx.quadraticCurveTo(lastX, lastY, midX, midY);
                    ctx.stroke();
                }

                prevMidX = midX;
                prevMidY = midY;
                lastX = coords.x;
                lastY = coords.y;
            }

            function stopDrawing() {
                drawing = false;
                updateDrawnSignature();
            }

            canvas.addEventListener('mousedown', startDrawing);
            canvas.addEventListener('mousemove', draw);
            canvas.addEventListener('mouseup', stopDrawing);
            canvas.addEventListener('mouseleave', stopDrawing);

            canvas.addEventListener('touchstart', startDrawing, { passive: false });
            canvas.addEventListener('touchmove', draw, { passive: false });
            canvas.addEventListener('touchend', stopDrawing);

            if (clearPadBtn) {
                clearPadBtn.addEventListener('click', () => {
                    const rect = canvas.getBoundingClientRect();
                    ctx.clearRect(0, 0, rect.width, rect.height);
                    hasDrawn = false;
                    if (placeholder) placeholder.style.display = 'flex';
                    updateDrawnSignature();
                });
            }

            setTimeout(resizeCanvas, 300);
            window.addEventListener('resize', resizeCanvas);
        }

        // 3. Upload File Event Listener with Advanced Adaptive Shadow/Background Removal & Auto-Cropping
        function bradleyThreshold(imageData, s, t) {
            const width = imageData.width;
            const height = imageData.height;
            const input = imageData.data;
            const intImg = new Int32Array(width * height);
            
            // Step 1: Create 2D Integral Image
            for (let y = 0; y < height; y++) {
                for (let x = 0; x < width; x++) {
                    const index = y * width + x;
                    const r = input[index * 4];
                    const g = input[index * 4 + 1];
                    const b = input[index * 4 + 2];
                    const val = 0.299 * r + 0.587 * g + 0.114 * b;
                    
                    let sum = val;
                    if (x > 0) sum += intImg[y * width + (x - 1)];
                    if (y > 0) sum += intImg[(y - 1) * width + x];
                    if (x > 0 && y > 0) sum -= intImg[(y - 1) * width + (x - 1)];
                    
                    intImg[index] = sum;
                }
            }
            
            // Step 2: Perform Adaptive Thresholding
            const s2 = Math.floor(s / 2);
            for (let x = 0; x < width; x++) {
                for (let y = 0; y < height; y++) {
                    const index = y * width + x;
                    
                    const x1 = Math.max(x - s2, 0);
                    const x2 = Math.min(x + s2, width - 1);
                    const y1 = Math.max(y - s2, 0);
                    const y2 = Math.min(y + s2, height - 1);
                    
                    const count = (x2 - x1 + 1) * (y2 - y1 + 1);
                    
                    let sum = intImg[y2 * width + x2];
                    if (x1 > 0) sum -= intImg[y2 * width + (x1 - 1)];
                    if (y1 > 0) sum -= intImg[(y1 - 1) * width + x2];
                    if (x1 > 0 && y1 > 0) sum += intImg[(y1 - 1) * width + (x1 - 1)];
                    
                    const r = input[index * 4];
                    const g = input[index * 4 + 1];
                    const b = input[index * 4 + 2];
                    const val = 0.299 * r + 0.587 * g + 0.114 * b;
                    
                    // If pixel is t % darker than the local average neighborhood, it is signature ink
                    if (val * count < sum * (1.0 - t)) {
                        input[index * 4] = 15;      // R (Slate 900 ink)
                        input[index * 4 + 1] = 23;  // G
                        input[index * 4 + 2] = 42;  // B
                        input[index * 4 + 3] = 255; // Opaque alpha
                    } else {
                        // All other background pixels (shadows, paper folds, yellow casts) become 100% transparent
                        input[index * 4] = 255;
                        input[index * 4 + 1] = 255;
                        input[index * 4 + 2] = 255;
                        input[index * 4 + 3] = 0;   // Transparent alpha
                    }
                }
            }
        }

        // Tightly crops the transparent signature to eliminate all empty margins
        function cropCanvasToContent(canvas) {
            const ctx = canvas.getContext('2d');
            const width = canvas.width;
            const height = canvas.height;
            const imgData = ctx.getImageData(0, 0, width, height);
            const data = imgData.data;
            
            let minX = width;
            let minY = height;
            let maxX = 0;
            let maxY = 0;
            let found = false;
            
            for (let y = 0; y < height; y++) {
                for (let x = 0; x < width; x++) {
                    const alphaIndex = (y * width + x) * 4 + 3;
                    const alpha = data[alphaIndex];
                    
                    if (alpha > 0) {
                        if (x < minX) minX = x;
                        if (x > maxX) maxX = x;
                        if (y < minY) minY = y;
                        if (y > maxY) maxY = y;
                        found = true;
                    }
                }
            }
            
            if (!found) return canvas; // return original if empty
            
            // Add safety margin padding (6px on each side)
            const padding = 6;
            minX = Math.max(0, minX - padding);
            minY = Math.max(0, minY - padding);
            maxX = Math.min(width - 1, maxX + padding);
            maxY = Math.min(height - 1, maxY + padding);
            
            const cropWidth = maxX - minX + 1;
            const cropHeight = maxY - minY + 1;
            
            const cropCanvas = document.createElement('canvas');
            cropCanvas.width = cropWidth;
            cropCanvas.height = cropHeight;
            const cropCtx = cropCanvas.getContext('2d');
            
            cropCtx.drawImage(canvas, minX, minY, cropWidth, cropHeight, 0, 0, cropWidth, cropHeight);
            return cropCanvas;
        }

        if (eSignatureInput && eSignaturePreview) {
            eSignatureInput.addEventListener('change', (event) => {
                const [file] = event.target.files || [];
                const nameDisplay = document.getElementById('signatureFileName');

                if (!file) {
                    eSignaturePreview.src = defaultSignatureUrl;
                    if (nameDisplay) nameDisplay.textContent = 'Choose file...';
                    if (drawnSigInput) drawnSigInput.value = '';
                    return;
                }

                if (nameDisplay) {
                    nameDisplay.textContent = 'Processing...';
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = new Image();
                    img.onload = function() {
                        // Limit dimensions to a max of 600px for speed and database size optimization
                        const maxDim = 600;
                        let w = img.width;
                        let h = img.height;
                        if (w > maxDim || h > maxDim) {
                            if (w > h) {
                                h = Math.round((h * maxDim) / w);
                                w = maxDim;
                            } else {
                                w = Math.round((w * maxDim) / h);
                                h = maxDim;
                            }
                        }

                        // Create offscreen canvas for real-time background subtraction
                        const tempCanvas = document.createElement('canvas');
                        tempCanvas.width = w;
                        tempCanvas.height = h;
                        const tempCtx = tempCanvas.getContext('2d');
                        tempCtx.drawImage(img, 0, 0, w, h);

                        // Run local adaptive threshold binarization
                        const imgData = tempCtx.getImageData(0, 0, w, h);
                        const winSize = Math.max(15, Math.floor(w / 10)); // Local context window size
                        bradleyThreshold(imgData, winSize, 0.15);         // 15% sensitivity threshold
                        tempCtx.putImageData(imgData, 0, 0);

                        // Tightly auto-crop to signature bounds
                        const croppedCanvas = cropCanvasToContent(tempCanvas);

                        // Generate clean transparent PNG base64
                        const cleanedDataUrl = croppedCanvas.toDataURL('image/png');

                        // Update signature preview with transparent binarized version
                        eSignaturePreview.src = cleanedDataUrl;
                        
                        // Load into the hidden drawnSignatureInput so server saves clean version automatically
                        if (drawnSigInput) {
                            drawnSigInput.value = cleanedDataUrl;
                        }

                        if (nameDisplay) {
                            nameDisplay.textContent = (file.name.length > 20 ? file.name.substring(0, 15) + '...' : file.name) + ' (Cleaned)';
                        }

                        // Clear file input value so that browser does not submit raw shadowy file on form submit
                        eSignatureInput.value = '';
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            });
        }

        show(0);
    })();
</script>
@endsection
