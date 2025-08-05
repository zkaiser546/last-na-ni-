<?php

namespace App\Http\Controllers;

use App\Models\LibraryVisit;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LibraryVisitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $patron = null;
        if ($request->search_button) {

            try {
                $patron = User::findOrFail($request->search);
            } catch (ModelNotFoundException $e) {
                session()->flash('error', 'User not found');
            }
        }

        return Inertia::render('library-visit/Create', [
            'patron' => $patron,
            'search_term' => $request->search,
            'search_button' => (boolean)$request->search_button,
        ]);
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
    public function show(LibraryVisit $libraryVisit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LibraryVisit $libraryVisit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LibraryVisit $libraryVisit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LibraryVisit $libraryVisit)
    {
        //
    }
}
