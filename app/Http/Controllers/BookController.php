<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

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
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
    }

    public function import()
    {
        return Inertia::render('records/BookImport');
    }

    public function importStore(Request $request)
    {
        try {

            $request->validate([
                'csv_file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max, 10240
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('error', 'Please fix the validation errors below.');
            throw $e; // Re-throw to let Laravel handle the redirect with errors
        }

        try {
            $csv_path = $request->csv_file->store('temp/csv_imports');

            if ($csv_path && Storage::exists($csv_path)) {
                $csv_content = Storage::get($csv_path);
                $csv_data = array_map('str_getcsv', explode("\n", $csv_content));

                // Remove header row if it exists
                $headers = array_shift($csv_data);

                $failed_count = 0;
                $errors = [];

                foreach ($csv_data as $row_index => $row) {
                    try {
                        // Trim all values in the row and check for emptiness
                        $row = array_map('trim', $row); // Trim all values
                        if (empty(array_filter($row, fn($value) => $value !== '' && $value !== null))) {
                            \Log::warning('Skipping empty row ' . ($row_index + 1) . ':', $row);
                            continue;
                        }

                        $date_received = null;
                        if (!empty($row[2]) && is_string($row[2])) {
                            try {
                                $date_received = Carbon::createFromFormat('m/d/Y', $row[2])->format('Y-m-d');
                            } catch (\Exception $e) {
                                $failed_count++;
                                $errors[] = "Row " . ($row_index + 1) . ": Invalid date format in date_received: " . $row[2];
                                \Log::info('Processing row', ['row_index' => $row_index + 1, 'data' => $row]);
                                continue;
                            }
                        }

                        $subject_headings = null;
                        if (!empty($row[13])) {
                            $subject_headings = $row[13];
                        }

                        $record_data = [
                            'accession_number' => $row[1] ?? null,
                            'date_received' => $date_received,
                            'title' => $row[5] ?? null,
                            'acquisition_status' => AcquisitionStatus::where('key', 'available')->first()->name,
                            'imported_by' => Auth::user()->id,
                            'subject_headings' => $subject_headings,
                        ];

                        $volume = null;
                        if (!empty($row[0])) {
                            $volume = $row[0];
                        }


                    } catch (\Exception $e) {
                        $failed_count++;
                        $errors[] = "Row " . ($row_index + 1) . ": " . $e->getMessage();
                        Log::info('Processing row', ['row_index' => $row_index + 1, 'data' => $row]);
                    }
                }
            }

            Storage::delete($csv_path);

        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database-specific errors
            $message = app()->environment('production')
                ? 'Failed to add record. Please try again.'
                : 'Failed to add record: ' . $e->getMessage();
            session()->flash('error', $message);
        } catch (\Exception $e) {
            // Handle any other unexpected errors
            $message = app()->environment('production')
                ? 'An unexpected error occurred. Please try again.'
                : 'An unexpected error occurred: ' . $e->getMessage();
            session()->flash('error', $message);
        }

    }
}
