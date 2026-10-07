<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PDS Registration &mdash; {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: #eef4f1;
            color: #0f172a;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .reg-topbar {
            background: linear-gradient(135deg, #065f46, #15803d);
            color: #fff;
            padding: 28px 24px;
            text-align: center;
        }
        .reg-brand { display: flex; align-items: center; justify-content: center; gap: 12px; }
        .reg-brand img { width: 52px; height: 52px; object-fit: contain; background: #fff; border-radius: 50%; padding: 2px; }
        .reg-kicker { font-size: 11px; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; opacity: .85; }
        .reg-title { margin-top: 4px; font-size: 20px; font-weight: 800; }
        .reg-sub { margin: 8px auto 0; max-width: 560px; font-size: 13px; line-height: 1.6; color: rgba(255,255,255,.85); }
        .reg-wrap { max-width: 720px; margin: -0px auto 64px; padding: 24px 16px 0; }
        .reg-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 12px 32px rgba(15, 23, 42, .08);
            padding: clamp(20px, 4vw, 36px);
        }
        .reg-section { margin-bottom: 28px; }
        .reg-section-head { display: flex; align-items: baseline; gap: 10px; margin-bottom: 14px; }
        .reg-step {
            flex-shrink: 0;
            width: 26px; height: 26px; border-radius: 50%;
            background: #16a34a; color: #fff;
            font-size: 13px; font-weight: 800;
            display: inline-flex; align-items: center; justify-content: center;
            transform: translateY(4px);
        }
        .reg-section-head h2 { margin: 0; font-size: 16px; font-weight: 800; }
        .reg-section-head p { margin: 2px 0 0; font-size: 12px; color: #64748b; }
        .reg-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .reg-grid .full { grid-column: 1 / -1; }
        @media (max-width: 560px) { .reg-grid { grid-template-columns: 1fr; } }
        .form-label { display: block; font-size: 12px; font-weight: 700; margin-bottom: 6px; color: #334155; }
        .form-label .req { color: #dc2626; }
        .form-input {
            width: 100%; border: 1px solid #cbd5e1; border-radius: 10px;
            padding: 11px 13px; font-size: 14px; background: #fff; color: #0f172a;
        }
        .form-input:focus { outline: none; border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.15); }
        .field-error { margin-top: 5px; font-size: 12px; font-weight: 600; color: #dc2626; }
        .form-error { border-color: #f87171 !important; }
        .alert-error {
            background: #fef2f2; border: 1px solid #fecaca; color: #991b1b;
            border-radius: 12px; padding: 12px 16px; font-size: 13px; margin-bottom: 20px;
        }
        .alert-error ul { margin: 6px 0 0; padding-left: 18px; }
        /* Photo uploader */
        .photo-zone {
            display: flex; gap: 18px; align-items: center; flex-wrap: wrap;
            border: 2px dashed #cbd5e1; border-radius: 14px; padding: 18px;
            background: #f8fafc; cursor: pointer; transition: border-color .15s;
        }
        .photo-zone:hover { border-color: #16a34a; }
        .photo-preview {
            width: 120px; height: 120px; border-radius: 16px; object-fit: cover;
            background: #e2e8f0; flex-shrink: 0; border: 1px solid #e2e8f0;
        }
        .photo-hint { font-size: 13px; color: #475569; line-height: 1.6; }
        .photo-hint strong { color: #15803d; }
        /* Combobox */
        .combo { position: relative; }
        .combo-list {
            position: absolute; top: calc(100% + 4px); left: 0; right: 0;
            background: #fff; border: 1px solid #e2e8f0; border-radius: 10px;
            box-shadow: 0 12px 28px rgba(15,23,42,.14);
            max-height: 220px; overflow-y: auto; z-index: 50; display: none;
        }
        .combo.open .combo-list { display: block; }
        .combo-item { padding: 10px 13px; font-size: 14px; cursor: pointer; }
        .combo-item:hover, .combo-item.active { background: #f0fdf4; }
        .combo-item small { color: #64748b; }
        .combo-empty { padding: 10px 13px; font-size: 13px; color: #94a3b8; }
        .btn-submit {
            width: 100%; border: none; border-radius: 12px; cursor: pointer;
            background: #16a34a; color: #fff; font-size: 15px; font-weight: 800;
            padding: 14px; transition: background .15s;
        }
        .btn-submit:hover { background: #15803d; }
        .reg-foot { text-align: center; font-size: 12px; color: #64748b; margin-top: 16px; line-height: 1.6; }
    </style>
</head>
<body>
    <header class="reg-topbar">
        <div class="reg-brand">
            <img src="{{ route('brand.logo') }}" alt="LGU Trento Logo">
            <div style="text-align:left;">
                <div class="reg-kicker">LGU Trento</div>
                <div class="reg-title">PDS Registration Form</div>
            </div>
        </div>
        <p class="reg-sub">Fill out this form so the HR office can create your Personnel Data Sheet record. All fields marked <strong>*</strong> are required.</p>
    </header>

    <main class="reg-wrap">
        <div class="reg-card">
            @if ($errors->any())
                <div class="alert-error">
                    <strong>Please fix the following:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div id="restoreNote" class="alert-error" style="display:none;background:#f0fdf4;border-color:#bbf7d0;color:#166534;">
                <strong>Welcome back!</strong> We restored what you typed — just re-attach your photo below.
            </div>

            <form method="POST" action="{{ route('register.store', $link) }}" enctype="multipart/form-data" id="regForm" novalidate>
                @csrf

                {{-- 1. Photo --}}
                <section class="reg-section">
                    <div class="reg-section-head">
                        <span class="reg-step">1</span>
                        <div><h2>Your Photo <span style="color:#dc2626;">*</span></h2><p>Clear front-facing photo, JPG/PNG/WebP up to 5MB.</p></div>
                    </div>
                    <label class="photo-zone" for="profile_photo">
                        <img id="photoPreview" class="photo-preview" alt="Photo preview"
                             src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Crect width='120' height='120' fill='%23e2e8f0'/%3E%3Ctext x='60' y='66' font-size='13' text-anchor='middle' fill='%23475569' font-family='sans-serif'%3ENo photo%3C/text%3E%3C/svg%3E">
                        <div class="photo-hint"><strong>Tap to choose a photo</strong><br>It will appear on your ID once HR approves your record.</div>
                    </label>
                    <input id="profile_photo" name="profile_photo" type="file" accept="image/jpeg,image/png,image/jpg,image/webp" style="display:none;" required>
                    @error('profile_photo')<p class="field-error">{{ $message }}</p>@enderror
                </section>

                {{-- 2. Name --}}
                <section class="reg-section">
                    <div class="reg-section-head">
                        <span class="reg-step">2</span>
                        <div><h2>Your Name</h2><p>Use your legal name as it appears on official documents.</p></div>
                    </div>
                    <div class="reg-grid">
                        <div>
                            <label class="form-label" for="surname">Surname <span class="req">*</span></label>
                            <input id="surname" name="surname" class="form-input @error('surname') form-error @enderror" value="{{ old('surname') }}" required>
                            @error('surname')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label" for="first_name">First Name <span class="req">*</span></label>
                            <input id="first_name" name="first_name" class="form-input @error('first_name') form-error @enderror" value="{{ old('first_name') }}" required>
                            @error('first_name')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label" for="middle_name">Middle Name</label>
                            <input id="middle_name" name="middle_name" class="form-input" value="{{ old('middle_name') }}">
                        </div>
                        <div>
                            <label class="form-label" for="nickname">Nickname</label>
                            <input id="nickname" name="nickname" class="form-input" value="{{ old('nickname') }}" placeholder="e.g. Al">
                        </div>
                        <div>
                            <label class="form-label" for="name_extension">Extension <span style="color:#94a3b8;font-weight:400;">(Jr, Sr, III…)</span></label>
                            <input id="name_extension" name="name_extension" class="form-input" value="{{ old('name_extension') }}" maxlength="20">
                        </div>
                        <div>
                            <label class="form-label" for="sex_at_birth">Sex <span class="req">*</span></label>
                            <select id="sex_at_birth" name="sex_at_birth" class="form-input @error('sex_at_birth') form-error @enderror" required>
                                <option value="">Select…</option>
                                <option value="Male" @selected(old('sex_at_birth') === 'Male')>Male</option>
                                <option value="Female" @selected(old('sex_at_birth') === 'Female')>Female</option>
                            </select>
                            @error('sex_at_birth')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </section>

                {{-- 3. Work details --}}
                <section class="reg-section">
                    <div class="reg-section-head">
                        <span class="reg-step">3</span>
                        <div><h2>Work Details</h2><p>Start typing to search — pick your office from the list.</p></div>
                    </div>
                    <div class="reg-grid">
                        <div class="full">
                            <label class="form-label" for="office_search">Office <span class="req">*</span></label>
                            <div class="combo" id="officeCombo">
                                <input id="office_search" class="form-input @error('office') form-error @enderror"
                                       autocomplete="off" placeholder="Type to search offices…"
                                       value="{{ old('office') }}">
                                <input type="hidden" name="office" id="office_value" value="{{ old('office') }}">
                                <div class="combo-list" id="officeList"></div>
                            </div>
                            @error('office')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label" for="job_order">Job Order No. <span style="color:#94a3b8;font-weight:400;">(if any)</span></label>
                            <input id="job_order" name="job_order" class="form-input" value="{{ old('job_order') }}">
                        </div>
                        <div>
                            <label class="form-label" for="agency_employee_no">Employee No. <span style="color:#94a3b8;font-weight:400;">(if any)</span></label>
                            <input id="agency_employee_no" name="agency_employee_no" class="form-input" value="{{ old('agency_employee_no') }}">
                        </div>
                    </div>
                </section>

                {{-- 4. Contact --}}
                <section class="reg-section">
                    <div class="reg-section-head">
                        <span class="reg-step">4</span>
                        <div><h2>Contact</h2><p>HR uses this to reach you about your record.</p></div>
                    </div>
                    <div class="reg-grid">
                        <div>
                            <label class="form-label" for="mobile_no">Mobile No. <span class="req">*</span></label>
                            <input id="mobile_no" name="mobile_no" class="form-input @error('mobile_no') form-error @enderror" value="{{ old('mobile_no') }}" placeholder="09xx xxx xxxx" required>
                            @error('mobile_no')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label" for="email_address">Email Address</label>
                            <input id="email_address" name="email_address" type="email" class="form-input @error('email_address') form-error @enderror" value="{{ old('email_address') }}">
                            @error('email_address')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </section>

                <button type="submit" class="btn-submit">Submit Registration</button>
                <p class="reg-foot">By submitting, your details go straight to the HR office for review.<br>Please double-check spelling before submitting.</p>
            </form>
        </div>
    </main>

    <script>
        const OPTIONS_URL = @json(route('register.options', $link));
        const INITIAL_OFFICES = @json($offices);
        const officeCombo = document.getElementById('officeCombo');
        const officeSearch = document.getElementById('office_search');
        const officeValue = document.getElementById('office_value');
        const officeList = document.getElementById('officeList');
        let activeIdx = -1, currentItems = [];

        function renderOfficeList(items, q) {
            officeList.innerHTML = '';
            activeIdx = -1;
            if (!items.length) {
                officeList.innerHTML = '<div class="combo-empty">No office matches "' + q + '". Pick from the list.</div>';
                return;
            }
            items.forEach((name) => {
                const div = document.createElement('div');
                div.className = 'combo-item';
                div.textContent = name;
                div.addEventListener('mousedown', (e) => { e.preventDefault(); pickOffice(name); });
                officeList.appendChild(div);
            });
            currentItems = items;
        }

        function pickOffice(name) {
            officeSearch.value = name;
            officeValue.value = name;
            officeCombo.classList.remove('open');
        }

        let debounce;
        async function fetchOffices(q) {
            try {
                const res = await fetch(OPTIONS_URL + '?q=' + encodeURIComponent(q));
                if (!res.ok) return;
                const data = await res.json();
                renderOfficeList(data.offices || [], q);
            } catch (e) { /* keep last list on offline blips */ }
        }

        officeSearch.addEventListener('input', () => {
            officeValue.value = '';
            officeCombo.classList.add('open');
            const q = officeSearch.value.trim();
            clearTimeout(debounce);
            if (!q) { renderOfficeList(INITIAL_OFFICES.slice(0, 30), ''); return; }
            const local = INITIAL_OFFICES.filter(o => o.toLowerCase().includes(q.toLowerCase()));
            if (local.length) renderOfficeList(local.slice(0, 30), q);
            debounce = setTimeout(() => fetchOffices(q), 250);
        });

        officeSearch.addEventListener('focus', () => {
            officeCombo.classList.add('open');
            if (!officeSearch.value.trim()) renderOfficeList(INITIAL_OFFICES.slice(0, 30), '');
        });

        officeSearch.addEventListener('keydown', (e) => {
            const items = officeList.querySelectorAll('.combo-item');
            if (!items.length) return;
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();
                activeIdx = e.key === 'ArrowDown'
                    ? Math.min(activeIdx + 1, items.length - 1)
                    : Math.max(activeIdx - 1, 0);
                items.forEach((el, i) => el.classList.toggle('active', i === activeIdx));
                items[activeIdx].scrollIntoView({ block: 'nearest' });
            } else if (e.key === 'Enter' && activeIdx >= 0) {
                e.preventDefault();
                pickOffice(items[activeIdx].textContent);
            } else if (e.key === 'Escape') {
                officeCombo.classList.remove('open');
            }
        });

        document.addEventListener('click', (e) => {
            if (!officeCombo.contains(e.target)) officeCombo.classList.remove('open');
        });

        // Photo preview
        const photoInput = document.getElementById('profile_photo');
        const photoPreview = document.getElementById('photoPreview');
        photoInput.addEventListener('change', () => {
            const file = photoInput.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => { photoPreview.src = e.target.result; };
            reader.readAsDataURL(file);
        });

        // Guard: office must come from the list
        document.getElementById('regForm').addEventListener('submit', (e) => {
            if (!officeValue.value) {
                const typed = officeSearch.value.trim().toLowerCase();
                const exact = INITIAL_OFFICES.find(o => o.toLowerCase() === typed);
                if (exact) { pickOffice(exact); return; }
                e.preventDefault();
                officeSearch.classList.add('form-error');
                officeSearch.focus();
                officeCombo.classList.add('open');
                alert('Please pick your office from the dropdown list.');
                return;
            }
            try { localStorage.removeItem(DRAFT_KEY); } catch (err) {}
        });

        // Draft autosave: keep typed values across refreshes (per link).
        // Note: the photo itself can't be restored (browser security) — user re-attaches it.
        const DRAFT_KEY = 'pds-reg-draft-' + @json($link->token);
        const regForm = document.getElementById('regForm');
        function collectDraft() {
            const data = {};
            regForm.querySelectorAll('input[name], select[name]').forEach((el) => {
                if (el.type === 'file' || el.type === 'hidden' || el.name === '_token') return;
                data[el.name] = el.value;
            });
            return data;
        }
        let saveDebounce;
        regForm.addEventListener('input', () => {
            clearTimeout(saveDebounce);
            saveDebounce = setTimeout(() => {
                try { localStorage.setItem(DRAFT_KEY, JSON.stringify(collectDraft())); } catch (err) {}
            }, 300);
        });
        (function restoreDraft() {
            let draft = {};
            try { draft = JSON.parse(localStorage.getItem(DRAFT_KEY) || '{}'); } catch (err) { return; }
            let restored = false;
            regForm.querySelectorAll('input[name], select[name]').forEach((el) => {
                if (el.type === 'file' || el.type === 'hidden' || el.name === '_token') return;
                if (!el.value && draft[el.name] !== undefined && draft[el.name] !== '') {
                    el.value = draft[el.name];
                    restored = true;
                }
            });
            if (restored) {
                if (officeSearch.value && !officeValue.value) {
                    const exact = INITIAL_OFFICES.find(o => o.toLowerCase() === officeSearch.value.trim().toLowerCase());
                    if (exact) pickOffice(exact);
                }
                document.getElementById('restoreNote').style.display = 'block';
            }
        })();
    </script>
</body>
</html>
