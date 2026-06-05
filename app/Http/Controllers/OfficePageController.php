<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Services\PdsDataService;
use Illuminate\View\View;

class OfficePageController extends Controller
{
    public function __invoke(PdsDataService $pds): View
    {
        return view('pds.offices', [
            'dbOffices' => \App\Models\Office::orderBy('name')->get(),
            'officeCounts' => collect($pds->officeCounts())->keyBy('name'),
            'totalOffices' => count($pds->officeOptions()),
            'taggedEmployees' => Employee::query()->whereNotNull('office')->where('office', '<>', '')->count(),
        ]);
    }
}
