@extends('layouts.app')

@section('page_title', 'ID Template Management')
@section('page_subtitle', 'Manage, upload, and toggle dynamic backgrounds for printed ID cards.')

@section('content')
    <div class="dash-shell">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

            .dash-shell {
                font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif;
            }

            .glass-card {
                background: white;
                border: 1px solid #e2e8f0;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
                border-radius: 16px;
                padding: 24px;
                transition: transform 0.2s, box-shadow 0.2s;
            }

            .glass-card:hover {
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
            }

            .badge-active {
                background-color: #dcfce7;
                color: #15803d;
                border: 1px solid #bbf7d0;
            }

            .badge-inactive {
                background-color: #f1f5f9;
                color: #475569;
                border: 1px solid #e2e8f0;
            }

            .form-input {
                width: 100%;
                padding: 10px 14px;
                border: 1px solid #cbd5e1;
                border-radius: 10px;
                outline: none;
                font-size: 14px;
                transition: border-color 0.2s, box-shadow 0.2s;
            }

            .form-input:focus {
                border-color: #16a34a;
                box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.15);
            }

            .btn-green {
                background-color: #16a34a;
                color: white;
                padding: 10px 20px;
                border-radius: 10px;
                font-weight: 600;
                transition: all 0.2s;
                text-align: center;
                border: none;
                cursor: pointer;
            }

            .btn-green:hover {
                background-color: #15803d;
                transform: translateY(-1px);
            }

            .btn-light {
                background-color: #f8fafc;
                color: #475569;
                padding: 8px 16px;
                border-radius: 10px;
                font-weight: 600;
                border: 1px solid #e2e8f0;
                transition: all 0.2s;
                cursor: pointer;
            }

            .btn-light:hover {
                background-color: #f1f5f9;
                color: #1e293b;
            }

            .template-thumbnail {
                width: 100%;
                max-width: 160px;
                margin: 0 auto;
                aspect-ratio: 3.5 / 5.5;
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                border-radius: 12px;
                border: 1px solid #cbd5e1;
                overflow: hidden;
                box-shadow: inset 0 0 40px rgba(0,0,0,0.02);
            }
        </style>

        <!-- Status Alerts -->
        @if(session('success'))
            <div class="mb-6 flex items-center gap-3 rounded-[12px] bg-[#f0fdf4] border border-[#bbf7d0] p-4 text-[#15803d]">
                <svg viewBox="0 0 16 16" class="h-5 w-5 stroke-current fill-none shrink-0" aria-hidden="true">
                    <circle cx="8" cy="8" r="6" stroke-width="1.3"></circle>
                    <path d="M5 8l2 2 4-4" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 flex items-center gap-3 rounded-[12px] bg-[#fef2f2] border border-[#fecaca] p-4 text-[#b91c1c]">
                <svg viewBox="0 0 16 16" class="h-5 w-5 stroke-current fill-none shrink-0" aria-hidden="true">
                    <circle cx="8" cy="8" r="6" stroke-width="1.3"></circle>
                    <path d="M8 5v4M8 11h.01" stroke-width="1.3" stroke-linecap="round"></path>
                </svg>
                <span class="font-medium text-sm">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Side: Upload Panel & Active Card -->
            <div class="lg:col-span-1 flex flex-col gap-6">
                <!-- Upload Card -->
                <div class="glass-card">
                    <h3 class="text-lg font-bold text-[#0f172a] mb-4 flex items-center gap-2">
                        <svg viewBox="0 0 16 16" class="h-5 w-5 text-[#16a34a] fill-none stroke-current" aria-hidden="true">
                            <path d="M12 9v4a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9M8 2.5v7M5 5.5l3-3 3 3" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        Upload ID Templates
                    </h3>

                    <form action="{{ route('admin.id-templates.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-[#64748b] mb-1.5 uppercase tracking-wider">Template Set Name</label>
                            <input type="text" name="name" placeholder="e.g. Trento Municipal ID v3" class="form-input" required>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#64748b] mb-1.5 uppercase tracking-wider">Front Template Image (.JPG, .PNG)</label>
                            <input type="file" name="template_file" accept="image/jpeg,image/png" class="form-input" required style="padding: 8px 10px;">
                            <p class="text-[11px] text-[#94a3b8] mt-1">Recommended size: 1360 x 2048px (aspect ratio 3.5" x 5.5")</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#64748b] mb-1.5 uppercase tracking-wider">Back Template Image (.JPG, .PNG) <span class="text-[#94a3b8] font-normal">(Optional)</span></label>
                            <input type="file" name="back_template_file" accept="image/jpeg,image/png" class="form-input" style="padding: 8px 10px;">
                            <p class="text-[11px] text-[#94a3b8] mt-1">If blank, defaults to our premium vector-styled CSS layout.</p>
                        </div>

                        <div class="flex items-center gap-2.5 mt-1">
                            <input type="checkbox" name="is_active" id="is_active" value="1" class="h-4.5 w-4.5 rounded border-[#cbd5e1] text-[#16a34a] focus:ring-[#16a34a]">
                            <label for="is_active" class="text-sm font-medium text-[#475569] cursor-pointer">Set as active template immediately</label>
                        </div>

                        <button type="submit" class="btn-green w-full mt-2">Upload and Save</button>
                    </form>
                </div>

                <!-- Active Template Preview -->
                <div class="glass-card">
                    <h3 class="text-lg font-bold text-[#0f172a] mb-4">Active Configuration</h3>
                    @if ($activeTemplate)
                        <div class="mb-4 grid grid-cols-2 gap-4">
                            <div>
                                <span class="block text-[10px] font-bold text-[#64748b] uppercase tracking-wider mb-1.5 text-center">Front Background</span>
                                <div class="template-thumbnail" style="background-image: url('{{ asset('storage/' . $activeTemplate->image_path) }}')"></div>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold text-[#64748b] uppercase tracking-wider mb-1.5 text-center">Back Background</span>
                                @if ($activeTemplate->back_image_path)
                                    <div class="template-thumbnail" style="background-image: url('{{ asset('storage/' . $activeTemplate->back_image_path) }}')"></div>
                                @else
                                    <div class="template-thumbnail flex flex-col items-center justify-center bg-[#f8fafc] text-[#94a3b8] text-[9.5pt] font-semibold text-center border-dashed border-2 border-[#cbd5e1] p-3 box-sizing-border-box">
                                        <svg viewBox="0 0 16 16" class="h-6 w-6 text-[#94a3b8] fill-none stroke-current mb-1" aria-hidden="true">
                                            <path d="M11 2.5h2.5v11H11m-6 0H2.5v-11H5M4.5 5.5h7" stroke-width="1.3" stroke-linecap="round"></path>
                                        </svg>
                                        CSS Styled Layout
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col gap-1 mt-3">
                            <span class="text-sm font-bold text-[#0f172a]">{{ $activeTemplate->name }}</span>
                            <span class="text-[11px] text-[#64748b]">Active since {{ $activeTemplate->updated_at->format('M d, Y') }}</span>
                        </div>
                    @else
                        <div class="mb-4 grid grid-cols-2 gap-4">
                            <div>
                                <span class="block text-[10px] font-bold text-[#64748b] uppercase tracking-wider mb-1.5 text-center">Front Background</span>
                                <div class="template-thumbnail" style="background-image: url('{{ asset('assets/idv3.jpg') }}')"></div>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold text-[#64748b] uppercase tracking-wider mb-1.5 text-center">Back Background</span>
                                <div class="template-thumbnail flex flex-col items-center justify-center bg-[#f8fafc] text-[#94a3b8] text-[9.5pt] font-semibold text-center border-dashed border-2 border-[#cbd5e1] p-3 box-sizing-border-box">
                                    <svg viewBox="0 0 16 16" class="h-6 w-6 text-[#94a3b8] fill-none stroke-current mb-1" aria-hidden="true">
                                        <path d="M11 2.5h2.5v11H11m-6 0H2.5v-11H5M4.5 5.5h7" stroke-width="1.3" stroke-linecap="round"></path>
                                    </svg>
                                    CSS Styled Layout
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1 mt-3">
                            <span class="text-sm font-bold text-[#0f172a]">Default Fallback (idv3.jpg)</span>
                            <span class="text-[11px] text-[#64748b]">Currently active as no custom template is set.</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Side: Template List -->
            <div class="lg:col-span-2">
                <div class="glass-card h-full flex flex-col">
                    <h3 class="text-lg font-bold text-[#0f172a] mb-6 flex items-center justify-between">
                        <span>Stored ID Templates</span>
                        <span class="text-xs font-semibold text-[#64748b] bg-[#f1f5f9] border border-[#e2e8f0] px-2.5 py-1 rounded-full">{{ count($templates) }} template(s)</span>
                    </h3>

                    @if(count($templates) === 0)
                        <div class="flex-1 flex flex-col items-center justify-center py-16 text-center">
                            <div class="h-16 w-16 bg-[#f0fdf4] border border-[#bbf7d0] text-[#16a34a] rounded-full flex items-center justify-center mb-4">
                                <svg viewBox="0 0 16 16" class="h-8 w-8 stroke-current fill-none" aria-hidden="true">
                                    <path d="M2.5 8h11M8 2.5v11" stroke-width="1.3" stroke-linecap="round"></path>
                                </svg>
                            </div>
                            <span class="text-base font-bold text-[#1e293b] mb-1">No custom templates uploaded yet</span>
                            <p class="text-sm text-[#64748b] max-w-sm">The system is currently running on the default `idv3.jpg` background. Upload your custom templates on the left!</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach ($templates as $template)
                                <div class="border border-[#e2e8f0] rounded-2xl p-4 flex flex-col bg-[#fafafb] hover:border-[#cbd5e1] transition-all max-w-[380px] w-full">
                                    <div class="relative mb-3 grid grid-cols-2 gap-3">
                                        <div>
                                            <span class="block text-[9px] font-bold text-[#64748b] uppercase tracking-wider mb-1 text-center">Front</span>
                                            <div class="template-thumbnail" style="background-image: url('{{ asset('storage/' . $template->image_path) }}')"></div>
                                        </div>
                                        <div>
                                            <span class="block text-[9px] font-bold text-[#64748b] uppercase tracking-wider mb-1 text-center">Back</span>
                                            @if ($template->back_image_path)
                                                <div class="template-thumbnail" style="background-image: url('{{ asset('storage/' . $template->back_image_path) }}')"></div>
                                            @else
                                                <div class="template-thumbnail flex flex-col items-center justify-center bg-[#f8fafc] text-[#94a3b8] text-[8px] font-bold text-center border-dashed border-2 border-[#cbd5e1] p-2">
                                                    CSS Layout Fallback
                                                </div>
                                            @endif
                                        </div>

                                        @if ($template->is_active)
                                            <span class="absolute top-6 left-2 inline-flex items-center gap-1 rounded-full badge-active px-2.5 py-1 text-xs font-bold shadow-sm">
                                                <span class="h-1.5 w-1.5 rounded-full bg-[#16a34a]"></span> Active
                                            </span>
                                        @else
                                            <span class="absolute top-6 left-2 inline-flex items-center gap-1 rounded-full badge-inactive px-2.5 py-1 text-xs font-bold shadow-sm">
                                                Inactive
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Editable Template Form -->
                                    <form action="{{ route('admin.id-templates.update', $template) }}" method="POST" class="mb-3">
                                        @csrf
                                        @method('PUT')
                                        <div class="flex gap-2">
                                            <input type="text" name="name" value="{{ $template->name }}" class="form-input text-xs font-semibold py-1.5 px-2.5 border-[#e2e8f0] bg-white" required>
                                            <button type="submit" class="btn-light text-xs py-1.5 px-3 flex items-center justify-center shadow-sm" title="Save changes">Save</button>
                                        </div>
                                    </form>

                                    <!-- Action Buttons -->
                                    <div class="mt-auto pt-3 border-t border-[#e2e8f0] flex gap-2 justify-between">
                                        @if (!$template->is_active)
                                            <form action="{{ route('admin.id-templates.activate', $template) }}" method="POST" style="display: contents;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn-green text-xs py-2 px-4 shadow-sm flex-1">Use Template</button>
                                            </form>
                                        @else
                                            <button type="button" class="btn-light text-xs py-2 px-4 shadow-sm flex-1 cursor-default opacity-50" disabled>In Use</button>
                                        @endif

                                        <form action="{{ route('admin.id-templates.destroy', $template) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this template? This will delete both front and back files and cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-light border-[#fecaca] text-[#b91c1c] hover:bg-[#fef2f2] hover:text-[#b91c1c] text-xs py-2 px-3 shadow-sm" title="Delete Template">
                                                <svg viewBox="0 0 16 16" class="h-[14px] w-[14px] stroke-current fill-none" aria-hidden="true">
                                                    <path d="M2.5 4h11M5 4v-1.5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1V4M6.5 7.5v4.5M9.5 7.5v4.5M3.5 4v9a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
