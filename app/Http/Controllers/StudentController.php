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
use Illuminate\Support\Facades\DB;

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
            'currentSortField' => $sortField,
            'currentSortDirection' => $sortDirection,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Inertia\Response
    {
        $programs = Program::select('id', 'code', 'name')->orderBy('name')->get();
        $majors = Major::select('id', 'name')->orderBy('name')->get();
        $colleges = College::select('id', 'code', 'name')->orderBy('name')->get();

        return Inertia::render('students/Create', [
            'programs' => $programs,
            'majors' => $majors,
            'colleges' => $colleges,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate based exactly on your front-end inputs
        try {
            $request->validate([
                // Basic Information
                'accession_number'      => 'required|string|max:50|unique:records,accession_number',
                'title'                 => 'required|string|max:255',
                'authors'               => 'required|array|min:1',
                'authors.*'             => 'string|max:255',
                'editors'               => 'nullable|array',
                'editors.*'             => 'string|max:255',
                'publication_year'      => 'required|integer|min:1000|max:' . date('Y'),
                'publisher'             => 'required|string|max:255',
                'publication_place'     => 'required|string|max:255',
                'isbn'                  => 'required|string|max:20|unique:books,isbn',

                // Classification & Location
                'call_number'           => 'nullable|string|max:50',
                'ddc_class_id'          => 'nullable|exists:ddc_classifications,id|required_without:lc_class_id',
                'lc_class_id'           => 'nullable|exists:lc_classifications,id|required_without:ddc_class_id',
                'physical_location_id'  => 'required|exists:physical_locations,id',

                // Physical Description
                'cover_image'           => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
                'status'                => 'required|in:available,damaged,missing,borrowed,discarded', // Added status validation

                // Administrative Information
                'ics_number'            => 'nullable|string|max:50',
                'ics_date'              => 'nullable|date',
                'pr_number'             => 'nullable|string|max:50',
                'pr_date'               => 'nullable|date',
                'po_number'             => 'nullable|string|max:50',
                'po_date'               => 'nullable|date',
                'source'                => 'required|in:purchase,donation',
                'purchase_amount'       => 'nullable|numeric|min:0',
                'lot_cost'              => 'nullable|numeric|min:0',
                'supplier'              => 'nullable|string|max:255',
                'donated_by'            => 'nullable|string|max:255',
                'cover_type'            => 'nullable|in:hardcover,paperback',

                // Content Description
                'table_of_contents'     => 'nullable|string',
                'subject_headings'      => 'nullable|array',
                'subject_headings.*'    => 'string|max:255',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('error', 'Please fix the validation errors below.');
            throw $e;
        }

        // 2. Database Transaction
        try {
            DB::beginTransaction();

            // Handle file upload
            $coverImagePath = null;
            if ($request->hasFile('cover_image')) {
                $coverImagePath = $request->file('cover_image')->store('uploads/covers', 'public');
            }

            // Create main record
            $record = Record::create([
                'accession_number' => $request->accession_number,
                'title'            => $request->title,
                'subject_headings' => $request->subject_headings,
                'status'           => $request->status, // Added status field
                'added_by'         => auth()->id(),
            ]);

            // Create related book record
            $record->book()->create([
                'authors'              => $request->authors,
                'editors'              => $request->editors,
                'publication_year'     => $request->publication_year,
                'publisher'            => $request->publisher,
                'publication_place'    => $request->publication_place,
                'isbn'                 => $request->isbn,
                'call_number'          => $request->call_number,
                'ddc_class_id'         => $request->ddc_class_id,
                'lc_class_id'          => $request->lc_class_id,
                'physical_location_id' => $request->physical_location_id,
                'cover_type'           => $request->cover_type,
                'cover_image'          => $coverImagePath,
                'ics_number'           => $request->ics_number,
                'ics_date'             => $request->ics_date,
                'pr_number'            => $request->pr_number,
                'pr_date'              => $request->pr_date,
                'po_number'            => $request->po_number,
                'po_date'              => $request->po_date,
                'source'               => $request->source,
                'purchase_amount'      => $request->purchase_amount,
                'lot_cost'             => $request->lot_cost,
                'supplier'             => $request->supplier,
                'donated_by'           => $request->donated_by,
                'table_of_contents'    => $request->table_of_contents,
            ]);

            DB::commit();

            return to_route('books.index')
                ->with('success', 'Book record created successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            Log::error('Database error creating Book: ' . $e->getMessage(), [
                'request_data' => $request->except(['cover_image']),
                'exception'    => $e
            ]);
            return back()->withInput()->with('error', 'Database error occurred while creating the book.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Unexpected error creating Book: ' . $e->getMessage(), [
                'request_data' => $request->except(['cover_image']),
                'exception'    => $e
            ]);
            return back()->withInput()->with('error', 'An unexpected error occurred. Please try again.');
        }
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
