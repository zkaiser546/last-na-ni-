<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingTransactionController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\GradSchoolStudentController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [WelcomeController::class, 'index'])->name('home');

// Routes that require authentication and verification
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/import', [UserController::class, 'import'])->name('users.import');
    Route::post('/users/import', [UserController::class, 'importStore'])->name('users.import.store');

    Route::get('/users/admins/create', [AdminController::class, 'create'])->name('admins.create');
    Route::post('/users/admins', [AdminController::class, 'store'])->name('admins.store');
    Route::get('/users/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/users/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/users/faculties/create', [FacultyController::class, 'create'])->name('faculties.create');
    Route::post('/users/faculties', [FacultyController::class, 'store'])->name('faculties.store');

    Route::get('/records', [RecordController::class, 'index'])->name('records.index');
    Route::get('/records/books/import', [BookController::class, 'import'])->name('books.import');
    Route::post('/records/books/import', [BookController::class, 'importStore'])->name('books.import.store');

    Route::get('/borrowings', [BorrowingTransactionController::class, 'index'])->name('borrowings.index');
    Route::get('/borrowings/create', [BorrowingTransactionController::class, 'create'])->name('borrowings.create');
    Route::post('/borrowings', [BorrowingTransactionController::class, 'store'])->name('borrowings.store');
    Route::get('/users/search', [BorrowingTransactionController::class, 'searchUser'])->name('borrowings.users.search');
    Route::post('/borrowings/borrow', [BorrowingTransactionController::class, 'borrow'])->name('borrowings.borrow');
});

// Test routes (no middleware)
Route::get('/test', function () {
    return Inertia::render('Test');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
