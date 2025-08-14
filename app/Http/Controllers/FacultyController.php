<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class FacultyController extends Controller
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

        // Get the admin user type ID by key
        $adminUserType = UserType::where('key', 'faculty')->first();
        $adminUserTypeId = $adminUserType ? $adminUserType->id : null;

        // Set default filter to admin user type, or use request parameter
        $user_type_id = $request->input('user_type_id', $adminUserTypeId);

        // Handle user_type_id filter (can be single value or array)
        if (!empty($user_type_id)) {
            $filters[] = [
                'id' => 'user_type_id',
                'value' => $user_type_id
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

        // Get all user types for filter dropdown
        $userTypes = UserType::select('id', 'name')
            ->orderBy('name')
            ->get();

        $users = User::query()
            ->with('userType')
            ->when($user_type_id, function ($query, $user_type_id) {
                // Handle both single values and arrays
                if (is_array($user_type_id) && !empty($user_type_id)) {
                    // Convert string values to integers if needed
                    $userTypeIds = array_map('intval', array_filter($user_type_id));
                    if (!empty($userTypeIds)) {
                        $query->whereIn('user_type_id', $userTypeIds);
                    }
                } elseif (!empty($user_type_id)) {
                    $query->where('user_type_id', intval($user_type_id));
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

        return Inertia::render('faculties/Index', [
            'data' => $users,
            'filter' => $filters,
            'userTypes' => $userTypes,
            'currentSortField' => $sortField,
            'currentSortDirection' => $sortDirection,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $offices = \App\Models\Office::select('id', 'acronym', 'name')->get();

        return Inertia::render('faculties/Create', [
            'offices' => $offices
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        // 1. Validation
        try {
            $request->validate([
                'library_id'     => 'required|integer|digits_between:1,10|unique:users,library_id',
                'first_name'     => 'required|string|max:50',
                'middle_initial' => 'nullable|string|max:1',
                'last_name'      => 'required|string|max:50',
                'sex'            => 'required|in:m,f',
                'contact_number' => 'nullable|string|max:20',
                'role_title'     => 'required|string|max:50',
                'email'          => 'required|string|lowercase|email|max:255|unique:users,email',
                'office_id'      => 'required|exists:offices,id',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('error', 'Please fix the validation errors below.');
            throw $e; // Let Laravel redirect back with errors
        }

        // 2. Database operations with error handling
        try {
            // You could fetch user type ID dynamically instead of hardcoding string
            $facultyType = UserType::where('key', 'faculty')->firstOrFail();

            // Create the User
            $user = User::create([
                'library_id'     => $request->library_id,
                'first_name'     => $request->first_name,
                'middle_initial' => $request->middle_initial,
                'last_name'      => $request->last_name,
                'sex'            => $request->sex,
                'contact_number' => $request->contact_number,
                'email'          => $request->email,
                'user_type_id'   => $facultyType->id,
            ]);

            // Create the Faculty profile linked to the user
            $user->faculty()->create([
                'role_title' => $request->role_title,
                'office_id'  => $request->office_id,
            ]);

            return to_route('faculties.index')
                ->with('success', 'You successfully created a new Faculty');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Faculty user type not found: ' . $e->getMessage(), [
                'request_data' => $request->except([]),
                'exception'    => $e
            ]);
            return back()->withInput()
                ->with('error', 'Faculty user type not found. Please contact the system administrator.');
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Database error creating Faculty: ' . $e->getMessage(), [
                'request_data' => $request->except([]),
                'exception'    => $e
            ]);
            return back()->withInput()
                ->with('error', 'Database error occurred while creating the faculty. Please try again.');
        } catch (\Exception $e) {
            \Log::error('Unexpected error creating Faculty: ' . $e->getMessage(), [
                'request_data' => $request->except([]),
                'exception'    => $e
            ]);
            return back()->withInput()
                ->with('error', 'An unexpected error occurred. Please try again or contact support.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Faculty $faculty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faculty $faculty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faculty $faculty)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faculty $faculty)
    {
        //
    }
}
