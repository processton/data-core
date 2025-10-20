<?php

use App\Http\Controllers\Soap\SoapServerController;
use Illuminate\Support\Facades\Route;

// SOAP Service Endpoint
Route::any('/soap', [SoapServerController::class, 'handle']);

// API Playground (Development Mode Only)
if (config('app.debug')) {
    Route::get('/api/playground', function () {
        return view('playground.index');
    })->middleware('scribe.dev');
}

Route::fallback(function () {
    return response()->json([
        'message' => 'API endpoint not found. Please refer to /api/docs for available endpoints.',
    ], 404);
});
