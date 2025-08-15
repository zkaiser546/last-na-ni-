<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ScannerController extends Controller
{
    /**
     * Show the scanner interface.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Scanner/Index');
    }

    /**
     * Process a scan request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processScan(Request $request)
    {
        // Here you would implement the logic to interact with the ScanSnap SV600
        // For now, we'll just return a success message

        return redirect()->route('scanner.index')->with('success', 'Scan completed successfully!');
    }
}
