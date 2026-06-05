<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Http\Request;

class AdminOfficeController extends Controller
{
    public function store(Request $request)
    {
        $request->merge(['name' => strtoupper(trim($request->name))]);

        $request->validate([
            'name' => 'required|string|max:255|unique:offices,name',
        ]);

        Office::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Office added successfully.');
    }

    public function update(Request $request, Office $admin_office)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:offices,name,' . $admin_office->id,
        ]);

        $admin_office->update([
            'name' => strtoupper(trim($request->name)),
        ]);

        return redirect()->back()->with('success', 'Office updated successfully.');
    }

    public function destroy(Office $admin_office)
    {
        $admin_office->delete();

        return redirect()->back()->with('success', 'Office deleted successfully.');
    }
}
