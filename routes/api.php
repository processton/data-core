<?php

use App\Events\AccountEvent;
use App\Events\CorsSettingEvent;
use App\Events\EntityEvent;
use App\Events\EntityFieldEvent;
use App\Events\EntityTriggerEvent;
use App\Events\IdentityEvent;
use App\Events\PermissionEvent;
use App\Events\RoleEvent;
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
            $data = ['message' => 'List accounts'];
            AccountEvent::dispatch('list', $data);

            return response()->json($data);
        });
        Route::post('/', function () {
            $data = ['message' => 'Create account'];
            AccountEvent::dispatch('create', $data);

            return response()->json($data);
        });
        Route::get('/{id}', function ($id) {
            $data = ['message' => "Get account {$id}"];
            AccountEvent::dispatch('get', $data, (int) $id);

            return response()->json($data);
        });
        Route::put('/{id}', function ($id) {
            $data = ['message' => "Update account {$id}"];
            AccountEvent::dispatch('update', $data, (int) $id);

            return response()->json($data);
        });
        Route::delete('/{id}', function ($id) {
            $data = ['message' => "Delete account {$id}"];
            AccountEvent::dispatch('delete', $data, (int) $id);

            return response()->json($data);
        });
    });

    // Entity Management Routes (Admin)
    Route::prefix('entities')->group(function () {
        Route::get('/', function () {
            $data = ['message' => 'List entities'];
            EntityEvent::dispatch('list', $data);

            return response()->json($data);
        });
        Route::post('/', function () {
            $data = ['message' => 'Create entity'];
            EntityEvent::dispatch('create', $data);

            return response()->json($data);
        });
        Route::get('/{id}', function ($id) {
            $data = ['message' => "Get entity {$id}"];
            EntityEvent::dispatch('get', $data, (int) $id);

            return response()->json($data);
        });
        Route::put('/{id}', function ($id) {
            $data = ['message' => "Update entity {$id}"];
            EntityEvent::dispatch('update', $data, (int) $id);

            return response()->json($data);
        });
        Route::delete('/{id}', function ($id) {
            $data = ['message' => "Delete entity {$id}"];
            EntityEvent::dispatch('delete', $data, (int) $id);

            return response()->json($data);
        });
    });

    // Entity Fields Management Routes (Admin)
    Route::prefix('entity-fields')->group(function () {
        Route::get('/', function () {
            $data = ['message' => 'List entity fields'];
            EntityFieldEvent::dispatch('list', $data);

            return response()->json($data);
        });
        Route::post('/', function () {
            $data = ['message' => 'Create entity field'];
            EntityFieldEvent::dispatch('create', $data);

            return response()->json($data);
        });
        Route::get('/{id}', function ($id) {
            $data = ['message' => "Get entity field {$id}"];
            EntityFieldEvent::dispatch('get', $data, (int) $id);

            return response()->json($data);
        });
        Route::put('/{id}', function ($id) {
            $data = ['message' => "Update entity field {$id}"];
            EntityFieldEvent::dispatch('update', $data, (int) $id);

            return response()->json($data);
        });
        Route::delete('/{id}', function ($id) {
            $data = ['message' => "Delete entity field {$id}"];
            EntityFieldEvent::dispatch('delete', $data, (int) $id);

            return response()->json($data);
        });
    });

    // Entity Triggers/Webhooks Management Routes (Admin)
    Route::prefix('entity-triggers')->group(function () {
        Route::get('/', function () {
            $data = ['message' => 'List entity triggers'];
            EntityTriggerEvent::dispatch('list', $data);

            return response()->json($data);
        });
        Route::post('/', function () {
            $data = ['message' => 'Create entity trigger'];
            EntityTriggerEvent::dispatch('create', $data);

            return response()->json($data);
        });
        Route::get('/{id}', function ($id) {
            $data = ['message' => "Get entity trigger {$id}"];
            EntityTriggerEvent::dispatch('get', $data, (int) $id);

            return response()->json($data);
        });
        Route::put('/{id}', function ($id) {
            $data = ['message' => "Update entity trigger {$id}"];
            EntityTriggerEvent::dispatch('update', $data, (int) $id);

            return response()->json($data);
        });
        Route::delete('/{id}', function ($id) {
            $data = ['message' => "Delete entity trigger {$id}"];
            EntityTriggerEvent::dispatch('delete', $data, (int) $id);

            return response()->json($data);
        });
    });

    // Role Management Routes (Admin)
    Route::prefix('roles')->group(function () {
        Route::get('/', function () {
            $data = ['message' => 'List roles'];
            RoleEvent::dispatch('list', $data);

            return response()->json($data);
        });
        Route::post('/', function () {
            $data = ['message' => 'Create role'];
            RoleEvent::dispatch('create', $data);

            return response()->json($data);
        });
        Route::get('/{id}', function ($id) {
            $data = ['message' => "Get role {$id}"];
            RoleEvent::dispatch('get', $data, (int) $id);

            return response()->json($data);
        });
        Route::put('/{id}', function ($id) {
            $data = ['message' => "Update role {$id}"];
            RoleEvent::dispatch('update', $data, (int) $id);

            return response()->json($data);
        });
        Route::delete('/{id}', function ($id) {
            $data = ['message' => "Delete role {$id}"];
            RoleEvent::dispatch('delete', $data, (int) $id);

            return response()->json($data);
        });
    });

    // Permission Management Routes (Admin)
    Route::prefix('permissions')->group(function () {
        Route::get('/', function () {
            $data = ['message' => 'List permissions'];
            PermissionEvent::dispatch('list', $data);

            return response()->json($data);
        });
        Route::post('/', function () {
            $data = ['message' => 'Create permission'];
            PermissionEvent::dispatch('create', $data);

            return response()->json($data);
        });
        Route::get('/{id}', function ($id) {
            $data = ['message' => "Get permission {$id}"];
            PermissionEvent::dispatch('get', $data, (int) $id);

            return response()->json($data);
        });
        Route::put('/{id}', function ($id) {
            $data = ['message' => "Update permission {$id}"];
            PermissionEvent::dispatch('update', $data, (int) $id);

            return response()->json($data);
        });
        Route::delete('/{id}', function ($id) {
            $data = ['message' => "Delete permission {$id}"];
            PermissionEvent::dispatch('delete', $data, (int) $id);

            return response()->json($data);
        });
    });

    // Identity Management Routes (Admin)
    Route::prefix('identities')->group(function () {
        Route::get('/', function () {
            $data = ['message' => 'List identities'];
            IdentityEvent::dispatch('list', $data);

            return response()->json($data);
        });
        Route::post('/', function () {
            $data = ['message' => 'Create identity'];
            IdentityEvent::dispatch('create', $data);

            return response()->json($data);
        });
        Route::get('/{id}', function ($id) {
            $data = ['message' => "Get identity {$id}"];
            IdentityEvent::dispatch('get', $data, (int) $id);

            return response()->json($data);
        });
        Route::put('/{id}', function ($id) {
            $data = ['message' => "Update identity {$id}"];
            IdentityEvent::dispatch('update', $data, (int) $id);

            return response()->json($data);
        });
        Route::delete('/{id}', function ($id) {
            $data = ['message' => "Delete identity {$id}"];
            IdentityEvent::dispatch('delete', $data, (int) $id);

            return response()->json($data);
        });
    });

    // CORS Settings Management Routes (Admin)
    Route::prefix('cors-settings')->group(function () {
        Route::get('/', function () {
            $data = ['message' => 'Get CORS settings'];
            CorsSettingEvent::dispatch('get', $data);

            return response()->json($data);
        });
        Route::put('/', function () {
            $data = ['message' => 'Update CORS settings'];
            CorsSettingEvent::dispatch('update', $data);

            return response()->json($data);
        });
    });
});
