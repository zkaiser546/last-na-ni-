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
    public function index(Request $request): \Inertia\Response
    {
        $perPage = $request->input('per_page', 10);
        $sortField = $request->input('sort_field', 'entry_time');
        $sortDirection = $request->input('sort_direction', 'desc');
        $filters = [];

        // Capture search parameters
        $searchTerm = $request->input('search');
        if (!empty($searchTerm)) {
            $filters[] = [
                'id' => 'search',
                'value' => $searchTerm
            ];
        }

        $visits = LibraryVisit::with(['user' => function($query) {
                $query->select('users.id', 'users.first_name', 'users.last_name', 'users.library_id', 'programs.name as program')
                    ->leftJoin('students', 'users.id', '=', 'students.user_id')
                    ->leftJoin('programs', 'students.program_id', '=', 'programs.id');
            }])
            ->when($searchTerm, function ($query, $searchTerm) {
                $query->where(function ($q) use ($searchTerm) {
                    $q->whereHas('user', function ($userQuery) use ($searchTerm) {
                        $userQuery->where('library_id', 'like', '%' . $searchTerm . '%')
                            ->orWhere('first_name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                    });
                });
            })
            ->when($sortField, function ($query, $sortField) use ($sortDirection) {
                $query->orderBy($sortField, $sortDirection);
            }, function ($query) {
                $query->latest('entry_time');
            })
            ->paginate(perPage: $perPage);

        return Inertia::render('logger/Index', [
            'data' => $visits,
            'filter' => $filters,
            'currentSortField' => $sortField,
            'currentSortDirection' => $sortDirection,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $patron = null;
        $user_entry = null;
        $is_logout = false;

        if ($request->search_button) {
            try {
                $patron = User::where('library_id', $request->search)->first();

                if ($patron) {
                    $user_entry = LibraryVisit::where('user_id', $patron->id)
                        ->whereNull('exit_time')
                        ->first();

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
            'search_button' => (boolean)$request->search_button,
            'is_logout' => $is_logout,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $user = User::findOrFail($request->patron_id);
            $user_entry = LibraryVisit::where('user_id', $user->id)
                ->whereNull('exit_time')
                ->first();

            // Get entry method and format it appropriately
            $entry_method = strtolower($request->input('entry_method', 'manual'));

            // Standardize entry method format
            if (stripos($entry_method, 'barcode') !== false) {
                $entry_method = 'barCode';
            } elseif (stripos($entry_method, 'rfid') !== false) {
                $entry_method = 'Rfid';
            } elseif (stripos($entry_method, 'card') !== false) {
                $entry_method = 'barCode'; // Card scan is treated as barCode
            } else {
                $entry_method = 'manual';
            }

            if ($user_entry) {
                // User is logging out
                $user_entry->update([
                    'exit_time' => now(),
                    'entry_method' => $entry_method
                ]);
                $message = 'Goodbye ' . $user->first_name . '! Thank you for visiting USeP Library!';
            } else {
                // User is logging in
                LibraryVisit::create([
                    'user_id' => $user->id,
                    'entry_time' => now(),
                    'entry_method' => $entry_method
                ]);
                $message = 'Welcome to USeP Library, ' . $user->first_name . '!';
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Error: ' . $e->getMessage(), [
                'user_id' => $request->patron_id,
                'exception' => $e
            ]);

            return back()->with('error', 'Something went wrong');
        }
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
