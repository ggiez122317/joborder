<?php

namespace App\Http\Controllers;

use App\Models\IdTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class IdTemplateController extends Controller
{
    /**
     * Display a listing of the ID templates.
     */
    public function index(): View
    {
        $templates = IdTemplate::orderBy('created_at', 'desc')->get();
        $activeTemplate = IdTemplate::where('is_active', true)->first();

        return view('admin.id-templates.index', compact('templates', 'activeTemplate'));
    }

    /**
     * Store a newly created ID template in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'template_file' => 'required|image|mimes:jpeg,png,jpg|max:4096', // Max 4MB
            'back_template_file' => 'nullable|image|mimes:jpeg,png,jpg|max:4096', // Max 4MB
        ]);

        if ($request->hasFile('template_file')) {
            $path = $request->file('template_file')->store('id-templates', 'public');
            
            $backPath = null;
            if ($request->hasFile('back_template_file')) {
                $backPath = $request->file('back_template_file')->store('id-templates', 'public');
            }

            // If there is no other template, make this one active by default
            $isFirst = IdTemplate::count() === 0;

            if ($isFirst || $request->boolean('is_active')) {
                // Deactivate any previous templates
                IdTemplate::query()->update(['is_active' => false]);
                $isActive = true;
            } else {
                $isActive = false;
            }

            IdTemplate::create([
                'name' => $request->input('name'),
                'image_path' => $path,
                'back_image_path' => $backPath,
                'is_active' => $isActive,
            ]);

            return redirect()->route('admin.id-templates.index')
                ->with('success', 'ID Template uploaded successfully!');
        }

        return redirect()->back()->with('error', 'Please upload a valid template file.');
    }

    /**
     * Activate a specific ID template.
     */
    public function activate(IdTemplate $template): RedirectResponse
    {
        // Deactivate all first
        IdTemplate::query()->update(['is_active' => false]);

        // Activate the selected template
        $template->update(['is_active' => true]);

        return redirect()->route('admin.id-templates.index')
            ->with('success', "Template '{$template->name}' is now set as the active template!");
    }

    /**
     * Update the name of a specific ID template.
     */
    public function update(Request $request, IdTemplate $template): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $template->update([
            'name' => $request->input('name'),
        ]);

        return redirect()->route('admin.id-templates.index')
            ->with('success', 'ID Template name updated successfully!');
    }

    /**
     * Remove the specified ID template from storage and database.
     */
    public function destroy(IdTemplate $template): RedirectResponse
    {
        $wasActive = $template->is_active;

        // Delete the physical front file from storage
        if ($template->image_path && Storage::disk('public')->exists($template->image_path)) {
            Storage::disk('public')->delete($template->image_path);
        }

        // Delete the physical back file from storage
        if ($template->back_image_path && Storage::disk('public')->exists($template->back_image_path)) {
            Storage::disk('public')->delete($template->back_image_path);
        }

        // Delete the database record
        $template->delete();

        // If the deleted template was active, activate the next available template if one exists
        if ($wasActive) {
            $next = IdTemplate::first();
            if ($next) {
                $next->update(['is_active' => true]);
            }
        }

        return redirect()->route('admin.id-templates.index')
            ->with('success', 'ID Template deleted successfully!');
    }
}
