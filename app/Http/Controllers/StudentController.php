<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\Major;
use App\Models\Program;
use App\Models\Student;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Validation\Rules;

class StudentController extends Controller
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
        $studentUserType = UserType::where('key', 'student')->first();
        $studentUserTypeId = $studentUserType ? $studentUserType->id : null;

        // Set default filter to admin user type, or use request parameter
        $user_type_id = $request->input('user_type_id', $studentUserTypeId);

        // Handle user_type_id filter
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

        // --- ADDED: Load data for frontend filters ---
        $programs = Program::select('id', 'code', 'name')->orderBy('name')->get();
        $majors = Major::select('id', 'name')->orderBy('name')->get();
        $colleges = College::select('id', 'code', 'name')->orderBy('name')->get();
        // ---------------------------------------------

        $users = User::query()
            ->with('userType')
            ->when($user_type_id, function ($query, $user_type_id) {
                if (is_array($user_type_id) && !empty($user_type_id)) {
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

        return Inertia::render('students/Index', [
            'data' => $users,
            'filter' => $filters,
            'userTypes' => $userTypes,
            'programs' => $programs, // Added prop
            'majors' => $majors,     // Added prop
            'colleges' => $colleges,   // Added prop
            'currentSortField' => $sortField,
            'currentSortDirection' => $sortDirection,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('students/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'student_id' => 'required|integer|unique:'.Student::class,
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors below.');
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'user_type' => 'student',
        ]);

        $user->student()->create([
            'student_id' => $request->student_id,
        ]);

        return to_route('users.index')->with('success', 'You successfully created a new Student');

    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
    }
}
