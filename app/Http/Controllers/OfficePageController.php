<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Office;
use App\Services\PdsDataService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OfficePageController extends Controller
{
    public function __invoke(Request $request, PdsDataService $pds): View
    {
        $search = trim((string) $request->query('search'));

        $dbOffices = Office::orderBy('name')
            ->when($search !== '', fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->get();

        return view('pds.offices', [
            'dbOffices' => $dbOffices,
            'officeCounts' => collect($pds->officeCounts())->keyBy('name'),
            'totalOffices' => count($pds->officeOptions()),
            'taggedEmployees' => Employee::query()->whereNotNull('office')->where('office', '<>', '')->count(),
            'search' => $search,
        ]);
    }
}
