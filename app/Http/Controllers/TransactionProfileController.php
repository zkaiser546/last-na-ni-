<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionProfileController extends Controller
{
    public function index(Request $request)
    {
        // Default empty state
        $profileData = null;
        $transactions = [];

        // If search query exists, return dummy data
        if ($request->has('search')) {
            $profileData = [
                'name' => 'Juan Dela Cruz',
                'student_id' => '2023-12345',
                'college' => 'College of Engineering',
                'program' => 'Bachelor of Science in Computer Science',
                'email' => 'juan.delacruz@usep.edu.ph',
                'phone' => '+63 912 345 6789',
                'profile_picture' => null // null will show default icon
            ];

            $transactions = [
                [
                    'date_borrowed' => '2023-05-15',
                    'accession_number' => 'ACC-001',
                    'title' => 'Introduction to Computer Science',
                    'due_date' => '2023-06-01',
                    'date_returned' => '2023-05-30'
                ],
                [
                    'date_borrowed' => '2023-05-20',
                    'accession_number' => 'ACC-002',
                    'title' => 'Advanced Programming Concepts',
                    'due_date' => '2023-06-06',
                    'date_returned' => null
                ],
                [
                    'date_borrowed' => '2023-04-10',
                    'accession_number' => 'ACC-003',
                    'title' => 'Database Management Systems',
                    'due_date' => '2023-04-25',
                    'date_returned' => '2023-04-22'
                ],
                [
                    'date_borrowed' => '2023-03-15',
                    'accession_number' => 'ACC-004',
                    'title' => 'Data Structures and Algorithms',
                    'due_date' => '2023-03-30',
                    'date_returned' => '2023-03-28'
                ],
                [
                    'date_borrowed' => '2023-02-28',
                    'accession_number' => 'ACC-005',
                    'title' => 'Computer Networks',
                    'due_date' => '2023-03-15',
                    'date_returned' => '2023-03-10'
                ]
            ];
        }

        return Inertia::render('reports/TransactionProfile', [
            'profileData' => $profileData,
            'transactions' => $transactions
        ]);
    }
}
