<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingTransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\GradSchoolStudentController;
use App\Http\Controllers\LibraryVisitController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [WelcomeController::class, 'index'])->name('home');
Route::get('/logger', [LibraryVisitController::class, 'index'])->name('logger.index');
Route::get('/logger/create', [LibraryVisitController::class, 'create'])->name('logger.create');
Route::post('/logger', [LibraryVisitController::class, 'store'])->name('logger.store');

// Routes that require authentication and verification
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/import', [UserController::class, 'import'])->name('users.import');
        Route::post('/import', [UserController::class, 'importStore'])->name('users.import.store');
        Route::get('/admins/create', [AdminController::class, 'create'])->name('admins.create');
        Route::post('/admins', [AdminController::class, 'store'])->name('admins.store');
        Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [StudentController::class, 'store'])->name('students.store');
        Route::get('/faculties/create', [FacultyController::class, 'create'])->name('faculties.create');
        Route::post('/faculties', [FacultyController::class, 'store'])->name('faculties.store');
    });

    Route::get('/records', [RecordController::class, 'index'])->name('records.index');
    Route::get('/records/books/import', [BookController::class, 'import'])->name('books.import');
    Route::post('/records/books/import', [BookController::class, 'importStore'])->name('books.import.store');

    Route::get('/borrowings', [BorrowingTransactionController::class, 'index'])->name('borrowings.index');
    Route::get('/borrowings/create', [BorrowingTransactionController::class, 'create'])->name('borrowings.create');
    Route::post('/borrowings', [BorrowingTransactionController::class, 'store'])->name('borrowings.store');
    Route::get('/borrowings/users/search', [BorrowingTransactionController::class, 'searchUser'])->name('borrowings.users.search');
    Route::post('/borrowings/borrow', [BorrowingTransactionController::class, 'borrow'])->name('borrowings.borrow');
});

// Test routes (no middleware)
Route::get('/test', function () {
    return Inertia::render('Test');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
