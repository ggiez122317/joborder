@extends('layouts.app')

@section('content')
    @php
        $portalMode = $portalMode ?? 'admin';
        $isUserPds = $portalMode === 'user';
        $isUserRecord = $portalMode === 'user-record';
        $isAdmin = $portalMode === 'admin';
    @endphp

    <style>
        @keyframes loading-progress {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        .animate-progress {
            animation: loading-progress 1.6s infinite ease-in-out;
        }
    </style>

    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold uppercase">{{ $isAdmin ? 'Upload PDS File' : 'Test Upload Mockup PDS' }}</h1>
            <p class="text-sm font-semibold">Accepted file: Excel (.xlsx)</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @if ($isUserPds)
                <a href="{{ route('user.dashboard') }}" class="btn-secondary">Back to Dashboard</a>
                <a href="{{ route('user.pds.form') }}" class="btn-secondary">Manual PDS Form</a>
            @elseif ($isUserRecord)
                <a href="{{ route('user.records') }}" class="btn-secondary">My Records</a>
                <a href="{{ route('user.records.create') }}" class="btn-secondary">Manual PDS Form</a>
            @else
                <a href="{{ route('admin.import-history') }}" class="btn-secondary">Import History</a>
                <a href="{{ route('records.index') }}" class="btn-secondary">View Records</a>
            @endif
        </div>
    </div>

    <section class="panel max-w-2xl">
        <div class="panel-heading">File Upload</div>
        <form id="pds-upload-form" method="POST" action="{{ $isUserPds ? route('user.pds.upload.parse') : ($isUserRecord ? route('user.records.upload.parse') : route('pds.upload.parse')) }}" enctype="multipart/form-data" class="space-y-5 p-5">
            @csrf
            <div>
                <label class="form-label" for="pds_file">{{ $isAdmin ? 'PDS Excel or PDF File' : 'Mockup PDS Excel or PDF File' }}</label>
                <input id="pds_file" type="file" name="pds_file" accept=".xlsx" class="form-input">
                @error('pds_file')
                    <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                @enderror
                @if (!$isAdmin)
                    <p class="mt-2 text-xs text-[#64748b]">Testing only. The file is mapped into your form so you can review it before saving.</p>
                @endif
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn-primary">{{ $isAdmin ? 'Upload and Map Fields' : 'Upload Mockup and Fill Form' }}</button>
                @if ($isUserPds)
                    <a href="{{ route('user.pds.form') }}" class="btn-secondary">Manual Encoding</a>
                @elseif ($isUserRecord)
                    <a href="{{ route('user.records.create') }}" class="btn-secondary">Manual Encoding</a>
                @else
                    <a href="{{ route('pds.create') }}" class="btn-secondary">Manual Encoding</a>
                @endif
            </div>
        </form>
    </section>

    <!-- Premium Glassmorphic Loading Toast -->
    <div id="loading-toast" class="pointer-events-none fixed right-4 top-4 z-[130] w-full max-w-sm hidden">
        <div class="pointer-events-auto rounded-[14px] border border-slate-100 bg-white/90 backdrop-blur-md px-4 py-3.5 shadow-[0_18px_40px_rgba(15,23,42,0.12)] text-slate-900">
            <div class="flex items-center gap-3.5">
                <!-- Sleek Premium Spinner -->
                <div class="relative flex h-5 w-5 shrink-0 items-center justify-center">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-blue-400 opacity-75"></span>
                    <svg class="h-5 w-5 animate-spin text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="text-sm font-bold text-slate-800">Processing Personal Data Sheet</div>
                    <div class="text-xs text-slate-500 mt-0.5">Uploading and parsing file content...</div>
                </div>
            </div>
            <!-- Progress Bar micro-animation -->
            <div class="mt-3 h-1.5 w-full bg-slate-100 overflow-hidden rounded-full">
                <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full animate-progress" style="width: 100%; transform: translateX(-100%);"></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('pds-upload-form');
            if (!form) return;

            form.addEventListener('submit', function(e) {
                const fileInput = document.getElementById('pds_file');
                if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                    return;
                }

                // Show the loading toast
                const toast = document.getElementById('loading-toast');
                if (toast) {
                    toast.classList.remove('hidden');
                }

                // Disable submit button & show loading state inside it
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    submitBtn.style.pointerEvents = 'none';
                    submitBtn.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    `;
                }
            });
        });
    </script>
@endsection
