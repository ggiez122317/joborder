@extends('layouts.app')

@section('page_title', 'My Profile')
@section('page_subtitle', 'Manage your LGU Trento portal settings and account credentials')

@section('content')
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Profile & Office Settings Panel -->
        <div class="panel">
            <div class="panel-heading flex items-center gap-2">
                <svg viewBox="0 0 16 16" class="h-4 w-4 fill-current" aria-hidden="true">
                    <path
                        d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
                </svg>
                Account Details
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('user.profile.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action_type" value="details">

                    <div>
                        <label class="form-label" for="name">Full Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name', auth()->user()->name) }}"
                            class="form-input w-full" placeholder="Enter your full name">
                        @error('name')
                            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label" for="email">Email Address</label>
                        <input id="email" type="email" value="{{ auth()->user()->email }}"
                            class="form-input w-full bg-[#f8fafc]" readonly style="cursor: not-allowed;">
                        <p class="mt-1 text-[11px] text-[#64748b]">Your verified Gmail address cannot be change.</p>
                    </div>

                    @php
                        $isHR = auth()->user()->isAdmin() || strcasecmp(auth()->user()->office ?? '', 'HRMO') === 0;
                    @endphp
                    <div>
                        <label class="form-label" for="office">Registered Office</label>
                        <select id="office" name="{{ $isHR ? 'office' : '_office_disabled' }}" class="form-input w-full" {{ $isHR ? '' : 'disabled' }}>
                            <option value="">-- Select Office --</option>
                            @foreach ($offices as $office)
                                <option value="{{ $office }}" {{ old('office', auth()->user()->office) === $office ? 'selected' : '' }}>{{ $office }}</option>
                            @endforeach
                        </select>
                        @if(!$isHR)
                            <input type="hidden" name="office" value="{{ auth()->user()->office }}">
                        @endif
                        @error('office')
                            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-[11px] text-[#64748b]">
                            This office choice scopes PDS records you can encode.
                            @if(!$isHR) <br><span class="text-amber-600 font-medium">You cannot change your registered office. Contact HR to edit.</span> @endif
                        </p>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="btn-primary px-6 py-2.5">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Security & Password Panel -->
        <div class="panel">
            <div class="panel-heading flex items-center gap-2">
                <svg viewBox="0 0 16 16" class="h-4 w-4 fill-current" aria-hidden="true">
                    <path
                        d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z" />
                </svg>
                Change Password
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('user.profile.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action_type" value="password">

                    <div>
                        <label class="form-label" for="current_password">Current Password</label>
                        <input id="current_password" name="current_password" type="password" class="form-input w-full"
                            placeholder="••••••••">
                        @error('current_password')
                            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label" for="password">New Password</label>
                        <input id="password" name="password" type="password" class="form-input w-full"
                            placeholder="••••••••">
                        @error('password')
                            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label" for="password_confirmation">Confirm New Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            class="form-input w-full" placeholder="••••••••">
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="btn-primary px-6 py-2.5">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection