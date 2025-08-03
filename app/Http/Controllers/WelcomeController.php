<?php

namespace App\Http\Controllers;

use App\Models\Record;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function  index()
    {
        $record = Record::with('book')
            ->latest()->paginate(10);

        return Inertia::render('Welcome', [
            'records' => $record
        ]);
    }
}
