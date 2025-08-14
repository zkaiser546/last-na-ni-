<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;

class ReportsController extends Controller
{
    public function index()
    {
        // Add any data needed for both library and borrowed items here
        return Inertia::render('Reports');
    }
}
