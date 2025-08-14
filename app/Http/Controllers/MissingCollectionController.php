<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;

class MissingCollectionController extends Controller
{
    public function index()
    {
        // Preset data for each category
        $data = [
            'bookData' => [
                [
                    'accession_number' => 'ACC001',
                    'call_number' => 'CN001',
                    'author' => 'John Doe',
                    'title' => 'Programming Basics',
                    'program' => 'BSCS',
                    'location' => 'Main Library'
                ]
            ],
            'electronicData' => [
                [
                    'accession_number' => 'E001',
                    'author' => 'Jane Smith',
                    'title' => 'Digital Systems'
                ]
            ],
            'periodicalData' => [
                [
                    'accession_number' => 'P001',
                    'author' => 'Tech Magazine',
                    'title' => 'Latest Technologies'
                ]
            ],
            'thesisData' => [
                [
                    'accession_number' => 'T001',
                    'author' => 'Mike Johnson',
                    'title' => 'Advanced Computing Systems'
                ]
            ]
        ];

        return Inertia::render('reports/MissingCollection', $data);
    }
}
