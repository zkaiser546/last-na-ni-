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
    public function index(Request $request)
    {
        $visits = LibraryVisit::with('user')->latest()->paginate(10);

        return Inertia::render('logger/Index', [
            'data' => $visits
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
        $is_logout = false;


        if ($request->search_button) {

            try {

                $patron = User::where('library_id', $request->search)->first();

                if ($patron) {
                    $purposes = VisitPurpose::all()->sortBy('sort_order');

                    $user_entry = LibraryVisit::where('user_id', $patron->id)->whereNull('exit_time')->first();
                    if ($user_entry) {
                        $is_logout = true;
                    }
                } else {
                    session()->flash('error', 'User not found');
                }

            } catch (ModelNotFoundException $e) {
                \Log::error('Error: ' . $e->getMessage());
            }
        }

        return Inertia::render('library-visit/Create', [
            'patron' => $patron,
            'purposes' => $purposes,
            'search_button' => (boolean)$request->search_button,
            'is_logout' => $is_logout,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $success_message = '';

        $user = User::where('id', $request->patron_id)->first();

        try {

            if (!$request->purpose_id) {
                $user_entry = LibraryVisit::where('user_id', $request->patron_id)->whereNull('exit_time')->first();

                $user_entry->update([
                    'exit_time' => now(),
                ]);
                $success_message = 'Thank you for visiting USeP Library!';

            } else {

                $purpose = VisitPurpose::where('id', $request->purpose_id)->first();
                if ($purpose) {
                    LibraryVisit::create([
                        'user_id' => $user->id,
                        'entry_time' => now(),
                        'visit_purpose_id' => $purpose->id,
                    ]);
                    $success_message = 'Welcome to USeP Library!';
                }
            }

            session()->flash('success', $success_message);

        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong');
            // Log the error
            \Log::error('Error: ' . $e->getMessage(), [
                'user_id' => $request->patron_id,
                'purpose_id' => $request->purpose_id,
                'exception' => $e
            ]);
        }

        return to_route('logger.create');

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
