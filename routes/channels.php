<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Public health channel - no auth required
Broadcast::channel('health', function () {
    return true;
});

// Account management channels
Broadcast::channel('accounts', function ($user) {
    return true; // In production, add proper auth
});

Broadcast::channel('accounts.{id}', function ($user, $id) {
    return true; // In production, add proper auth
});

// Entity management channels (Admin only)
Broadcast::channel('entities', function ($user) {
    return true; // In production, check admin role
});

Broadcast::channel('entities.{id}', function ($user, $id) {
    return true; // In production, check admin role
});

// Entity fields management channels (Admin only)
Broadcast::channel('entity-fields', function ($user) {
    return true; // In production, check admin role
});

Broadcast::channel('entity-fields.{id}', function ($user, $id) {
    return true; // In production, check admin role
});

// Entity triggers management channels (Admin only)
Broadcast::channel('entity-triggers', function ($user) {
    return true; // In production, check admin role
});

Broadcast::channel('entity-triggers.{id}', function ($user, $id) {
    return true; // In production, check admin role
});

// Role management channels (Admin only)
Broadcast::channel('roles', function ($user) {
    return true; // In production, check admin role
});

Broadcast::channel('roles.{id}', function ($user, $id) {
    return true; // In production, check admin role
});

// Permission management channels (Admin only)
Broadcast::channel('permissions', function ($user) {
    return true; // In production, check admin role
});

Broadcast::channel('permissions.{id}', function ($user, $id) {
    return true; // In production, check admin role
});

// Identity management channels (Admin only)
Broadcast::channel('identities', function ($user) {
    return true; // In production, check admin role
});

Broadcast::channel('identities.{id}', function ($user, $id) {
    return true; // In production, check admin role
});

// CORS settings channel (Admin only)
Broadcast::channel('cors-settings', function ($user) {
    return true; // In production, check admin role
});
