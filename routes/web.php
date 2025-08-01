<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\GradSchoolStudentController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Models\GradSchoolStudent;
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
    Route::post('/users/admins', [AdminController::class, 'store'])->name('admins.store');
    Route::get('/users/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/users/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/users/faculties/create', [FacultyController::class, 'create'])->name('faculties.create');
    Route::post('/users/faculties', [FacultyController::class, 'store'])->name('faculties.store');
    Route::get('/users/grad-students/create', [GradSchoolStudentController::class, 'create'])->name('grad-students.create');
    Route::post('/users/grad-students', [GradSchoolStudentController::class, 'store'])->name('grad-students.store');

    Route::get('/records', [RecordController::class, 'index'])->name('records.index');
    Route::get('/records/books/import', [BookController::class, 'import'])->name('books.import');
    Route::post('/records/books/import', [BookController::class, 'importStore'])->name('books.import.store');
});

// Test routes (no middleware)
Route::get('/test', function () {
    return Inertia::render('Test');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
