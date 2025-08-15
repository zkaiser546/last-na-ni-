<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Book;
use App\Models\BorrowingTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month');

        $userCount = User::count();
        $bookCount = Book::count();
        $borrowedBooksCount = BorrowingTransaction::whereYear('created_at', $year)->count();

        // Monthly users borrow
        $monthlyUsersBorrow = null;
        if ($month) {
            $monthlyUsersBorrow = BorrowingTransaction::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->distinct('user_id')
                ->count('user_id');
        }

        // Borrowing trends (monthly for selected year)
        $monthlyTrends = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyTrends[] = BorrowingTransaction::whereYear('created_at', $year)
                ->whereMonth('created_at', $m)
                ->count();
        }

        // Borrowing trends (yearly for last 3 years)
        $yearlyTrends = [];
        $years = [$year, $year - 1, $year - 2];
        foreach ($years as $y) {
            $yearlyTrends[] = BorrowingTransaction::whereYear('created_at', $y)->count();
        }

        $lastYear = $year - 1;

        $bookCountLastYear = Book::whereYear('created_at', $lastYear)->count();
        $userCountLastYear = User::whereYear('created_at', $lastYear)->count();
        $borrowedBooksCountLastYear = BorrowingTransaction::whereYear('created_at', $lastYear)->count();
        $monthlyUsersBorrowLastYear = null;
        if ($month) {
            $monthlyUsersBorrowLastYear = BorrowingTransaction::whereYear('created_at', $lastYear)
                ->whereMonth('created_at', $month)
                ->distinct('user_id')
                ->count('user_id');
        }

        // Helper for percent change
        $percentChange = function ($current, $previous) {
            if ($previous == 0) return 0;
            return round((($current - $previous) / $previous) * 100, 1);
        };

        $bookPercentChange = $percentChange($bookCount, $bookCountLastYear);
        $userPercentChange = $percentChange($userCount, $userCountLastYear);
        $borrowedBooksPercentChange = $percentChange($borrowedBooksCount, $borrowedBooksCountLastYear);
        $monthlyUsersBorrowPercentChange = $percentChange($monthlyUsersBorrow ?? 0, $monthlyUsersBorrowLastYear ?? 0);

//        // Top programs by borrowings
//        $topProgramsRaw = BorrowingTransaction::query()
//            ->join('users', 'users.id', '=', 'borrowing_transactions.user_id')
//            ->selectRaw('users.program_id, COUNT(*) as borrow_count')
//            ->groupBy('users.program_id')
//            ->orderByDesc('borrow_count')
//            ->limit(5)
//            ->get();
//
//        $topPrograms = [];
//        foreach ($topProgramsRaw as $row) {
//            $programName = \App\Models\Program::find($row->program_id)?->name ?? 'Unknown';
//            $topPrograms[] = [
//                'name' => $programName,
//                'count' => $row->borrow_count,
//            ];
//        }

        // Instead of querying the database, create dummy data
        $topPrograms = [
            [
                'name'  => 'BSED BSABE',
                'count' => rand(50, 100),
            ],
            [
                'name'  => 'BSED MATH',
                'count' => rand(40, 90),
            ],
            [
                'name'  => 'BSED ENG',
                'count' => rand(30, 80),
            ],
            [
                'name'  => 'BSED FIL',
                'count' => rand(20, 70),
            ],
            [
                'name'  => 'BSNED',
                'count' => rand(10, 60),
            ],
        ];

        // USEP Programs (preset list)
        $usepPrograms = [
            'BSED BSABE',
            'BSED MATH',
            'BSED ENG',
            'BSED FIL',
            'BSNED',
            'BSABE',
            'Other USEP Programs',
        ];

        return Inertia::render('Dashboard', [
            'userCount'                       => $userCount,
            'bookCount'                       => $bookCount,
            'borrowedBooksCount'              => $borrowedBooksCount,
            'monthlyUsersBorrow'              => $monthlyUsersBorrow,
            'monthlyTrends'                   => $monthlyTrends,
            'yearlyTrends'                    => $yearlyTrends,
            'selectedYear'                    => $year,
            'selectedMonth'                   => $month,
            'bookPercentChange'               => $bookPercentChange,
            'userPercentChange'               => $userPercentChange,
            'borrowedBooksPercentChange'      => $borrowedBooksPercentChange,
            'monthlyUsersBorrowPercentChange' => $monthlyUsersBorrowPercentChange,
            'topPrograms'                     => $topPrograms,
            'usepPrograms'                    => $usepPrograms,
        ]);
    }
}
