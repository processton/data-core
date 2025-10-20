<?php

use App\Http\Controllers\Soap\SoapServerController;
use Illuminate\Support\Facades\Route;

// SOAP Service Endpoint
Route::any('/soap', [SoapServerController::class, 'handle']);

// API Documentation and Playground (Development Mode Only)
if (config('app.debug')) {
    Route::get('/api/docs', function () {
        return response()->json([
            'message' => 'API Documentation',
            'version' => '1.0.0',
            'rest_endpoints' => [
                '/api/v1/accounts' => 'Account management (REST)',
                '/api/v1/entities' => 'Entity management (REST)',
            ],
            'soap_endpoint' => '/soap',
            'wsdl' => '/soap?wsdl',
        ]);
    });

    Route::get('/api/playground', function () {
        return response()->json([
            'message' => 'API Playground - Swagger UI will be mounted here',
            'note' => 'This endpoint is only available in development mode',
        ]);
    });
}

Route::fallback(function () {
    return response()->json([
        'message' => 'API endpoint not found. Please refer to /api/docs for available endpoints.',
    ], 404);
});
