<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Health check endpoint
Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
});

// API v1 Routes
Route::prefix('v1')->group(function () {
    
    // Account Management Routes
    Route::prefix('accounts')->group(function () {
        Route::get('/', function () {
            return response()->json(['message' => 'List accounts']);
        });
        Route::post('/', function () {
            return response()->json(['message' => 'Create account']);
        });
        Route::get('/{id}', function ($id) {
            return response()->json(['message' => "Get account {$id}"]);
        });
        Route::put('/{id}', function ($id) {
            return response()->json(['message' => "Update account {$id}"]);
        });
        Route::delete('/{id}', function ($id) {
            return response()->json(['message' => "Delete account {$id}"]);
        });
    });

    // Entity Management Routes (Admin)
    Route::prefix('entities')->group(function () {
        Route::get('/', function () {
            return response()->json(['message' => 'List entities']);
        });
        Route::post('/', function () {
            return response()->json(['message' => 'Create entity']);
        });
        Route::get('/{id}', function ($id) {
            return response()->json(['message' => "Get entity {$id}"]);
        });
        Route::put('/{id}', function ($id) {
            return response()->json(['message' => "Update entity {$id}"]);
        });
        Route::delete('/{id}', function ($id) {
            return response()->json(['message' => "Delete entity {$id}"]);
        });
    });

    // Entity Fields Management Routes (Admin)
    Route::prefix('entity-fields')->group(function () {
        Route::get('/', function () {
            return response()->json(['message' => 'List entity fields']);
        });
        Route::post('/', function () {
            return response()->json(['message' => 'Create entity field']);
        });
        Route::get('/{id}', function ($id) {
            return response()->json(['message' => "Get entity field {$id}"]);
        });
        Route::put('/{id}', function ($id) {
            return response()->json(['message' => "Update entity field {$id}"]);
        });
        Route::delete('/{id}', function ($id) {
            return response()->json(['message' => "Delete entity field {$id}"]);
        });
    });

    // Entity Triggers/Webhooks Management Routes (Admin)
    Route::prefix('entity-triggers')->group(function () {
        Route::get('/', function () {
            return response()->json(['message' => 'List entity triggers']);
        });
        Route::post('/', function () {
            return response()->json(['message' => 'Create entity trigger']);
        });
        Route::get('/{id}', function ($id) {
            return response()->json(['message' => "Get entity trigger {$id}"]);
        });
        Route::put('/{id}', function ($id) {
            return response()->json(['message' => "Update entity trigger {$id}"]);
        });
        Route::delete('/{id}', function ($id) {
            return response()->json(['message' => "Delete entity trigger {$id}"]);
        });
    });

    // Role Management Routes (Admin)
    Route::prefix('roles')->group(function () {
        Route::get('/', function () {
            return response()->json(['message' => 'List roles']);
        });
        Route::post('/', function () {
            return response()->json(['message' => 'Create role']);
        });
        Route::get('/{id}', function ($id) {
            return response()->json(['message' => "Get role {$id}"]);
        });
        Route::put('/{id}', function ($id) {
            return response()->json(['message' => "Update role {$id}"]);
        });
        Route::delete('/{id}', function ($id) {
            return response()->json(['message' => "Delete role {$id}"]);
        });
    });

    // Permission Management Routes (Admin)
    Route::prefix('permissions')->group(function () {
        Route::get('/', function () {
            return response()->json(['message' => 'List permissions']);
        });
        Route::post('/', function () {
            return response()->json(['message' => 'Create permission']);
        });
        Route::get('/{id}', function ($id) {
            return response()->json(['message' => "Get permission {$id}"]);
        });
        Route::put('/{id}', function ($id) {
            return response()->json(['message' => "Update permission {$id}"]);
        });
        Route::delete('/{id}', function ($id) {
            return response()->json(['message' => "Delete permission {$id}"]);
        });
    });

    // Identity Management Routes (Admin)
    Route::prefix('identities')->group(function () {
        Route::get('/', function () {
            return response()->json(['message' => 'List identities']);
        });
        Route::post('/', function () {
            return response()->json(['message' => 'Create identity']);
        });
        Route::get('/{id}', function ($id) {
            return response()->json(['message' => "Get identity {$id}"]);
        });
        Route::put('/{id}', function ($id) {
            return response()->json(['message' => "Update identity {$id}"]);
        });
        Route::delete('/{id}', function ($id) {
            return response()->json(['message' => "Delete identity {$id}"]);
        });
    });

    // CORS Settings Management Routes (Admin)
    Route::prefix('cors-settings')->group(function () {
        Route::get('/', function () {
            return response()->json(['message' => 'Get CORS settings']);
        });
        Route::put('/', function () {
            return response()->json(['message' => 'Update CORS settings']);
        });
    });
});
