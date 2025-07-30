<?php

use Illuminate\Support\Facades\Route;

Route::get('/sample', function () {
    return response()->json([
        'message' => 'This data came from Laravel!',
    ]);
});
