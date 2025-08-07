<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function  index(Request $request)
    {
        $record = Record::with('book')
            ->latest()->paginate(10);

        $search_result = null;
        if ($request->search_button)
        {
            if ($request->filled('search')) {
                $search_term = $request->search;

                $search_result = Record::with('book')
                    ->where(function($query) use ($search_term) {
                        $query->where('accession_number', 'LIKE', '%' . $search_term . '%')
                            ->orWhere('title', 'LIKE', '%' . $search_term . '%');
                    })
                    ->paginate(5);

            } else {
                session()->flash('error', 'Enter a search term');
            }
        }

        $user_count = User::count();

        return Inertia::render('Welcome', [
            'records' => $record,
            'search_result' => $search_result,
            'search_term' => $request->search,
            'search_button' => $request->search_button,
            'user_count' => $user_count,
        ]);
    }

}
