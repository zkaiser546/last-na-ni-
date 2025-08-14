<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;

class ReportsPenaltyController extends Controller
{
    public function index()
    {
        // Preset data for demonstration
        $penaltyData = [
            [
                'student_id' => '2023-12345',
                'name' => 'John Doe',
                'penalty_type' => 'Late Return',
                'amount' => 5.00,
                'status' => 'Unpaid',
                'date' => '2023-10-01',
            ],
            [
                'student_id' => '2023-67890',
                'name' => 'Jane Smith',
                'penalty_type' => 'Damaged Book',
                'amount' => 10.00,
                'status' => 'Unpaid',
                'date' => '2023-10-02',
            ],
            [
                'student_id' => '2023-11111',
                'name' => 'Mike Johnson',
                'penalty_type' => 'Lost Book',
                'amount' => 25.00,
                'status' => 'Unpaid',
                'date' => '2023-10-03',
            ],
        ];

        return Inertia::render('reports/ReportsPenalty', [
            'penaltyData' => $penaltyData
        ]);
    }
}
