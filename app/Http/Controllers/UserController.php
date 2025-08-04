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

                        $library_id = null;
                        if (!empty($row[0]) && is_numeric($row[0]) && (int)$row[0] !== 0) {
                            $library_id = (int)$row[0];
                        }

                        $card_id = null;
                        if (!empty($row[1]) && is_numeric($row[1]) && (int)$row[1] !== 0) {
                            $card_id = (int)$row[1];
                        }

                        $school_id = null;
                        if (!empty($row[2]) && is_numeric($row[2]) && (int)$row[2] !== 0) {
                            $school_id = (int)$row[2];
                        }

                        $first_name = null;
                        if (!empty($row[3]) && is_string($row[3]) && trim($row[3]) !== '') {
                            $first_name = trim($row[3]);
                        }

                        $middle_initial = null;
                        if (!empty($row[4]) && is_string($row[4])) {
                            $clean = preg_replace('/[^\p{L}]/u', '', trim($row[4]));
                            if (strlen($clean) >= 1) {
                                $middle_initial = strtoupper($clean[0]);
                            }
                        }

                        $last_name = null;
                        if (!empty($row[5]) && is_string($row[5]) && trim($row[5]) !== '') {
                            $last_name = trim($row[5]);
                        }

                        $sex = null;
                        if (!empty($row[6]) && is_string($row[6]) && trim($row[6]) !== '') {
                            $sex_value = trim($row[6]);
                            if (strtolower($sex_value) === 'female') {
                                $sex = 'F';
                            } elseif (strtolower($sex_value) === 'male') {
                                $sex = 'M';
                            }
                        }

                        $contact_number = null;
                        if (!empty($row[7]) && is_string($row[7]) && trim($row[7]) !== '') {
                            $contact_value = trim($row[7]);
                            $digits_only = preg_replace('/\D/', '', $contact_value);
                            if (strlen($digits_only) === 10) {
                                $contact_number = $digits_only;
                            }
                        }

                        $email = null;
                        if (!empty($row[8]) && is_string($row[8]) && trim($row[8]) !== '') {
                            $email_value = trim($row[8]);
                            if (filter_var($email_value, FILTER_VALIDATE_EMAIL)) {
                                $email = $email_value;
                            }
                        }

                        $user_profile = null;
                        if (!empty($row[10]) && is_string($row[10]) && trim($row[10]) !== '') {
                            $user_profile = trim($row[10]);
                        }

                        $user_type = null;
                        if (!empty($row[11]) && is_string($row[11]) && trim($row[11]) !== '') {
                            $user_type = trim($row[11]);
                            // relation nalang here:
                        }




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
