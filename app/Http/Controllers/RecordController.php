<?php

namespace App\Http\Controllers;

use App\Models\DdcClassification;
use App\Models\Record;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Inertia\Response
    {
        $perPage = $request->input('per_page', 10);
        $ddc_class_id = $request->input('ddc_class_id', null);
        $sortField = $request->input('sort_field', null);
        $sortDirection = $request->input('sort_direction', 'asc');
        $filters = [];

        if (!empty($ddc_class_id)) {
            $filters[] = [
                'id' => 'ddc_class_id',
                'value' => $ddc_class_id
            ];
        }

        // Capture search parameters
        $searchTerm = $request->input('search');
        if (!empty($searchTerm)) {
            $filters[] = [
                'id' => 'search',
                'value' => $searchTerm
            ];
        }

        // Get all DDC classes for filter dropdown
        $ddcClasses = DdcClassification::select('id', 'name')
            ->orderBy('name')
            ->get();

        $records = Record::query()
            ->with(['book.ddcClassification']) // Load nested relationship
            ->when($ddc_class_id, function ($query, $ddc_class_id) {
                // Handle both single values and arrays
                if (is_array($ddc_class_id) && !empty($ddc_class_id)) {
                    // Convert string values to integers if needed
                    $ddcClassIds = array_map('intval', array_filter($ddc_class_id));
                    if (!empty($ddcClassIds)) {
                        $query->whereHas('book', function ($bookQuery) use ($ddcClassIds) {
                            $bookQuery->whereIn('ddc_class_id', $ddcClassIds);
                        });
                    }
                } elseif (!empty($ddc_class_id)) {
                    $query->whereHas('book', function ($bookQuery) use ($ddc_class_id) {
                        $bookQuery->where('ddc_class_id', (int)$ddc_class_id);
                    });
                }
            })
            ->when($searchTerm, function ($query, $searchTerm) {
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('first_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('library_id', 'like', '%' . $searchTerm . '%');
                });
            })
            ->when($sortField, function ($query, $sortField) use ($sortDirection) {
                $query->orderBy($sortField, $sortDirection);
            })
            ->paginate(perPage: $perPage);

        return Inertia::render('records/Index', [
            'data' => $records,
            'filter' => $filters,
            'ddcClasses' => $ddcClasses,
            'currentSortField' => $sortField,
            'currentSortDirection' => $sortDirection,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Record $record)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Record $record)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Record $record)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Record $record)
    {
        //
    }
}
