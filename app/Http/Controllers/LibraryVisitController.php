<?php

namespace App\Http\Controllers;

use App\Models\LibraryVisit;
use App\Models\User;
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

            $patron = User::findOrFail($request->search);
            if (!$patron) {
                session()->flash('error', "<UNK> <UNK> <UNK> <UNK> <UNK> <UNK> <UNK> <UNK>");
            }

        }
        return Inertia::render('library-visit/Create', [
            'patron' => $patron,
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
