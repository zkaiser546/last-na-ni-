<?php

namespace App\Http\Controllers;

use App\Models\DigitalResource;
use App\Models\Record;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DigitalResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Inertia\Response
    {
        $perPage = $request->input('per_page', 10);
        $sortField = $request->input('sort_field', null);
        $sortDirection = $request->input('sort_direction', 'asc');
        $filters = [];

        // Capture search parameters
        $searchTerm = $request->input('search');
        if (!empty($searchTerm)) {
            $filters[] = [
                'id' => 'search',
                'value' => $searchTerm
            ];
        }

        $records = Record::query()
            ->with('digitalResource')
            ->when($searchTerm, function ($query, $searchTerm) {
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('accession_number', 'like', '%' . $searchTerm . '%')
                        ->orWhere('title', 'like', '%' . $searchTerm . '%');
                });
            })
            ->when($sortField, function ($query, $sortField) use ($sortDirection) {
                $query->orderBy($sortField, $sortDirection);
            })
            ->paginate(perPage: $perPage);

        return Inertia::render('multimedia/Index', [
            'data' => $records,
            'filter' => $filters,
            'currentSortField' => $sortField,
            'currentSortDirection' => $sortDirection,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('multimedia/Create');
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
    public function show(DigitalResource $digitalResource)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DigitalResource $digitalResource)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DigitalResource $digitalResource)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DigitalResource $digitalResource)
    {
        //
    }
}
