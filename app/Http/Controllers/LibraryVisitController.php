<?php

namespace App\Http\Controllers;

use App\Models\LibraryVisit;
use App\Models\User;
use App\Models\VisitPurpose;
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
        $visits = LibraryVisit::latest()->paginate(10);

        return Inertia::render('logger/Index', [
            'visits' => $visits
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $patron = null;
        $purposes = null;
        $user_entry = null;
        if ($request->search_button) {

            try {
                $patron = User::where('library_id', $request->search)->first();
                $purposes = VisitPurpose::all()->sortBy('sort_order');

                $user_entry = LibraryVisit::where('user_id', $patron->id)->whereNull('exit_time')->first();

            } catch (ModelNotFoundException $e) {
                session()->flash('error', 'User not found');
            }
        }

        if ($user_entry)
        {
            LibraryVisit::update([
                'exit_time' => now(),
            ]);
            $success_message = 'Thank you for visiting USeP library.';
        }

        return Inertia::render('library-visit/Create', [
            'patron' => $patron,
            'purposes' => $purposes,
            'search_term' => $request->search,
            'search_button' => (boolean)$request->search_button,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = null;
        $purpose = null;
        try {
            $user = User::where('id', $request->patron_id)->first();
            $purpose = VisitPurpose::where('id', $request->purpose_id)->first();
        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong');
            // Log the error
            \Log::error('Error in user or book retrieval: ' . $e->getMessage(), [
                'user_id' => $request->user_id,
                'book_accession' => $request->book_accession,
                'exception' => $e
            ]);
        }

        LibraryVisit::create([
            'user_id' => $user->id,
            'entry_time' => now(),
            'visit_purpose_id' => $purpose->id,
        ]);

        return to_route('logger.create')
            ->with('success', 'Welcome to USeP Library!');

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
