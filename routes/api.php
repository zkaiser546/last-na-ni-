<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ScanController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index']);
    Route::post('/users', [\App\Http\Controllers\UserController::class, 'store']);
});

// Scan API routes
Route::get('/scans', [ScanController::class, 'index']);
Route::delete('/scans/{scan}', [ScanController::class, 'destroy']);
Route::post('/scan/import', [ScanController::class, 'import']);

Route::get('/sample', function () {
    return response()->json([
        'message' => 'This data came from Laravel!',
    ]);
});
