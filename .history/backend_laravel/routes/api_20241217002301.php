<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function() {
    return response()->json(['message' => 'API is working']);
});

// Comment out everything else temporarily
/*
Route::prefix('v1')->group(function () {
    // ... your other routes
});
*/ 