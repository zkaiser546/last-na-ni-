<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(): \Inertia\Response
    {
        $users = User::latest()->get();

        return Inertia::render('users/Index', [
            'users' => $users
        ]);
    }

    public function import()
    {
        return Inertia::render('users/Import');
    }

    public function importStore(Request $request)
    {
        $success_message = "";

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
                $imported_count = 0;

                foreach ($csv_data as $row_index => $row) {
                    try {
                        // Trim all values in the row and check for emptiness
                        $row = array_map('trim', $row); // Trim all values
                        if (empty(array_filter($row, fn($value) => $value !== '' && $value !== null))) {
                            \Log::warning('Skipping empty row ' . ($row_index + 1) . ':', $row);
                            continue;
                        }

                        //


                    } catch (\Exception $e) {
                        $failed_count++;
                        $errors[] = "Row " . ($row_index + 1) . ": " . $e->getMessage();
                        Log::info('Processing row', ['row_index' => $row_index + 1, 'data' => $row]);
                        foreach ($errors as $error) {
                            Log::error($error);
                        }
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

        return to_route('users.import')->with('success', $success_message);

    }
}
