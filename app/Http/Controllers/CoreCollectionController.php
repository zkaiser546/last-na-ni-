<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class CoreCollectionController extends Controller
{
    public function index()
    {
        // Calculate years for dropdown
        $currentYear = 2025;
        $years = [];
        for ($i = 5; $i > 0; $i--) {
            $years[] = $i . " years back";
        }

        // Sample data with DDC classifications
        $data = [
            [
                'classification' => 'General Works (000-099)',
                'titles' => 120,
                'volumes' => 150,
                'copyright' => [
                    [
                        'year' => '5 years back',
                        'titles' => 20,
                        'volumes' => 25
                    ],
                    [
                        'year' => '4 years back',
                        'titles' => 25,
                        'volumes' => 30
                    ],
                    [
                        'year' => '3 years back',
                        'titles' => 25,
                        'volumes' => 35
                    ],
                    [
                        'year' => '2 years back',
                        'titles' => 25,
                        'volumes' => 30
                    ],
                    [
                        'year' => '1 years back',
                        'titles' => 25,
                        'volumes' => 30
                    ]
                ]
            ],
            [
                'classification' => 'Philosophy & Psychology (100-199)',
                'titles' => 150,
                'volumes' => 180,
                'copyright' => [
                    [
                        'year' => '5 years back',
                        'titles' => 25,
                        'volumes' => 30
                    ],
                    [
                        'year' => '4 years back',
                        'titles' => 30,
                        'volumes' => 35
                    ],
                    [
                        'year' => '3 years back',
                        'titles' => 30,
                        'volumes' => 35
                    ],
                    [
                        'year' => '2 years back',
                        'titles' => 30,
                        'volumes' => 40
                    ],
                    [
                        'year' => '1 years back',
                        'titles' => 35,
                        'volumes' => 40
                    ]
                ]
            ],
            [
                'classification' => 'Religion (200-299)',
                'titles' => 100,
                'volumes' => 120,
                'copyright' => [
                    [
                        'year' => '5 years back',
                        'titles' => 15,
                        'volumes' => 20
                    ],
                    [
                        'year' => '4 years back',
                        'titles' => 20,
                        'volumes' => 25
                    ],
                    [
                        'year' => '3 years back',
                        'titles' => 20,
                        'volumes' => 25
                    ],
                    [
                        'year' => '2 years back',
                        'titles' => 25,
                        'volumes' => 25
                    ],
                    [
                        'year' => '1 years back',
                        'titles' => 20,
                        'volumes' => 25
                    ]
                ]
            ]
        ];

        // Handle pagination
        $page = request('page', 1);
        $perPage = 10;
        $total = count($data);
        $lastPage = ceil($total / $perPage);
        $items = array_slice($data, ($page - 1) * $perPage, $perPage);

        return Inertia::render('reports/CoreCollection', [
            'years' => $years,
            'data' => [
                'data' => $items,
                'current_page' => $page,
                'per_page' => $perPage,
                'last_page' => $lastPage,
                'total' => $total
            ]
        ]);
    }
}
