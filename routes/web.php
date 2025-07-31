<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Routes that require authentication and verification
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/admins/create', [AdminController::class, 'create'])->name('admins.create');
    Route::post('/users/admins/', [AdminController::class, 'store'])->name('admins.store');

    Route::get('/records', function () {
        return Inertia::render('Records');
    })->name('records');
});

// Test routes (no middleware)
Route::get('/test', function () {
    return Inertia::render('Test');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
