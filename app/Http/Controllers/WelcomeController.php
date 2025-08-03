<?php

namespace App\Http\Controllers;

use App\Models\Record;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function  index(Request $request)
    {
        $record = Record::with('book')
            ->latest()->paginate(10);

        $search_result = null;
        if ($request->filled('search')) {
            $search_result = Record::with('book')
            ->where('accession_number', 'LIKE', '%' . $request->search . '%')
                ->paginate(5);

        } else {
            session()->flash('error', 'Enter a search term');
        }

        return Inertia::render('Welcome', [
            'records' => $record,
            'search_result' => $search_result,
        ]);
    }

    public function search(Request $request)
    {


        return Inertia::render('Welcome', [
            'search_result' => $result,
        ]);
    }
}
