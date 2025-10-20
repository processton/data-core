<?php

use Illuminate\Support\Facades\Route;

// API Documentation and Playground (Development Mode Only)
if (config('app.debug')) {
    Route::get('/api/docs', function () {
        return response()->json([
            'message' => 'API Documentation',
            'version' => '1.0.0',
            'endpoints' => [
                '/api/accounts' => 'Account management',
                '/api/entities' => 'Entity management',
                '/api/roles' => 'Role management',
                '/api/permissions' => 'Permission management',
            ],
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
