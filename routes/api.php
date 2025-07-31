<?php

use Illuminate\Support\Facades\Route;

Route::get('/users', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'index']);

Route::get('/sample', function () {
    return response()->json([
        'message' => 'This data came from Laravel!',
    ]);
});
