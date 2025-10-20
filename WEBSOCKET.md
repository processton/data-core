# WebSocket Layer (WSL) Documentation

## Overview

The Data Core application now includes a comprehensive WebSocket Layer (WSL) that mirrors all REST API operations. This enables real-time communication and event broadcasting for all CRUD operations.

## Features

- **Real-time Event Broadcasting**: All REST API operations automatically broadcast events via WebSocket
- **Channel-based Communication**: Separate channels for each resource type
- **Action-based Events**: Events include action type (list, create, get, update, delete)
- **Timestamps**: All events include ISO 8601 timestamps
- **ID-specific Channels**: Individual resource updates broadcast to both general and specific channels

## Available Channels

### Public Channels
- `health` - Health check updates

### Resource Channels
- `accounts` - Account management operations
- `accounts.{id}` - Specific account updates
- `entities` - Entity management operations (Admin)
- `entities.{id}` - Specific entity updates (Admin)
- `entity-fields` - Entity field operations (Admin)
- `entity-fields.{id}` - Specific entity field updates (Admin)
- `entity-triggers` - Entity trigger operations (Admin)
- `entity-triggers.{id}` - Specific entity trigger updates (Admin)
- `roles` - Role management operations (Admin)
- `roles.{id}` - Specific role updates (Admin)
- `permissions` - Permission management operations (Admin)
- `permissions.{id}` - Specific permission updates (Admin)
- `identities` - Identity management operations (Admin)
- `identities.{id}` - Specific identity updates (Admin)
- `cors-settings` - CORS settings operations (Admin)

## Event Structure

All events broadcast with the following structure:

```json
{
  "action": "create|list|get|update|delete",
  "data": {
    "message": "Operation description",
    // ... additional data
  },
  "id": 123,  // Optional, for specific resource operations
  "timestamp": "2025-10-20T11:30:25+00:00"
}
```

## Configuration

### Environment Variables

Add the following to your `.env` file:

```env
# Broadcasting Configuration
BROADCAST_DRIVER=log  # Options: log, pusher, reverb, null

# For Pusher (Production WebSocket)
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=mt1
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https

# For Laravel Reverb (Production WebSocket)
REVERB_APP_ID=your_app_id
REVERB_APP_KEY=your_app_key
REVERB_APP_SECRET=your_app_secret
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http
```

### Broadcast Drivers

1. **log** (Default/Development): Events are logged, no actual broadcasting
2. **pusher**: Use Pusher service for WebSocket
3. **reverb**: Use Laravel Reverb for WebSocket (recommended for production)
4. **null**: Disable broadcasting

## REST Operations with WebSocket Events

### Accounts

| HTTP Method | Endpoint | Action | Event Dispatched |
|-------------|----------|--------|------------------|
| GET | `/api/v1/accounts` | List accounts | `AccountEvent('list', data)` |
| POST | `/api/v1/accounts` | Create account | `AccountEvent('create', data)` |
| GET | `/api/v1/accounts/{id}` | Get account | `AccountEvent('get', data, id)` |
| PUT | `/api/v1/accounts/{id}` | Update account | `AccountEvent('update', data, id)` |
| DELETE | `/api/v1/accounts/{id}` | Delete account | `AccountEvent('delete', data, id)` |

### Entities (Admin)

| HTTP Method | Endpoint | Action | Event Dispatched |
|-------------|----------|--------|------------------|
| GET | `/api/v1/entities` | List entities | `EntityEvent('list', data)` |
| POST | `/api/v1/entities` | Create entity | `EntityEvent('create', data)` |
| GET | `/api/v1/entities/{id}` | Get entity | `EntityEvent('get', data, id)` |
| PUT | `/api/v1/entities/{id}` | Update entity | `EntityEvent('update', data, id)` |
| DELETE | `/api/v1/entities/{id}` | Delete entity | `EntityEvent('delete', data, id)` |

### Entity Fields (Admin)

| HTTP Method | Endpoint | Action | Event Dispatched |
|-------------|----------|--------|------------------|
| GET | `/api/v1/entity-fields` | List fields | `EntityFieldEvent('list', data)` |
| POST | `/api/v1/entity-fields` | Create field | `EntityFieldEvent('create', data)` |
| GET | `/api/v1/entity-fields/{id}` | Get field | `EntityFieldEvent('get', data, id)` |
| PUT | `/api/v1/entity-fields/{id}` | Update field | `EntityFieldEvent('update', data, id)` |
| DELETE | `/api/v1/entity-fields/{id}` | Delete field | `EntityFieldEvent('delete', data, id)` |

### Entity Triggers (Admin)

| HTTP Method | Endpoint | Action | Event Dispatched |
|-------------|----------|--------|------------------|
| GET | `/api/v1/entity-triggers` | List triggers | `EntityTriggerEvent('list', data)` |
| POST | `/api/v1/entity-triggers` | Create trigger | `EntityTriggerEvent('create', data)` |
| GET | `/api/v1/entity-triggers/{id}` | Get trigger | `EntityTriggerEvent('get', data, id)` |
| PUT | `/api/v1/entity-triggers/{id}` | Update trigger | `EntityTriggerEvent('update', data, id)` |
| DELETE | `/api/v1/entity-triggers/{id}` | Delete trigger | `EntityTriggerEvent('delete', data, id)` |

### Roles (Admin)

| HTTP Method | Endpoint | Action | Event Dispatched |
|-------------|----------|--------|------------------|
| GET | `/api/v1/roles` | List roles | `RoleEvent('list', data)` |
| POST | `/api/v1/roles` | Create role | `RoleEvent('create', data)` |
| GET | `/api/v1/roles/{id}` | Get role | `RoleEvent('get', data, id)` |
| PUT | `/api/v1/roles/{id}` | Update role | `RoleEvent('update', data, id)` |
| DELETE | `/api/v1/roles/{id}` | Delete role | `RoleEvent('delete', data, id)` |

### Permissions (Admin)

| HTTP Method | Endpoint | Action | Event Dispatched |
|-------------|----------|--------|------------------|
| GET | `/api/v1/permissions` | List permissions | `PermissionEvent('list', data)` |
| POST | `/api/v1/permissions` | Create permission | `PermissionEvent('create', data)` |
| GET | `/api/v1/permissions/{id}` | Get permission | `PermissionEvent('get', data, id)` |
| PUT | `/api/v1/permissions/{id}` | Update permission | `PermissionEvent('update', data, id)` |
| DELETE | `/api/v1/permissions/{id}` | Delete permission | `PermissionEvent('delete', data, id)` |

### Identities (Admin)

| HTTP Method | Endpoint | Action | Event Dispatched |
|-------------|----------|--------|------------------|
| GET | `/api/v1/identities` | List identities | `IdentityEvent('list', data)` |
| POST | `/api/v1/identities` | Create identity | `IdentityEvent('create', data)` |
| GET | `/api/v1/identities/{id}` | Get identity | `IdentityEvent('get', data, id)` |
| PUT | `/api/v1/identities/{id}` | Update identity | `IdentityEvent('update', data, id)` |
| DELETE | `/api/v1/identities/{id}` | Delete identity | `IdentityEvent('delete', data, id)` |

### CORS Settings (Admin)

| HTTP Method | Endpoint | Action | Event Dispatched |
|-------------|----------|--------|------------------|
| GET | `/api/v1/cors-settings` | Get settings | `CorsSettingEvent('get', data)` |
| PUT | `/api/v1/cors-settings` | Update settings | `CorsSettingEvent('update', data)` |

## Client Integration Examples

### JavaScript (Laravel Echo)

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});

// Listen to account events
window.Echo.channel('accounts')
    .listen('.AccountEvent', (e) => {
        console.log('Account event:', e);
    });

// Listen to specific account events
window.Echo.channel('accounts.1')
    .listen('.AccountEvent', (e) => {
        console.log('Account 1 event:', e);
    });
```

### Python (Socket.IO Client)

```python
from socketio import Client

sio = Client()

@sio.event
def connect():
    print('Connected to WebSocket')
    sio.emit('subscribe', {'channel': 'accounts'})

@sio.on('AccountEvent')
def on_account_event(data):
    print('Account event:', data)

sio.connect('http://localhost:6001')
```

### Node.js

```javascript
const io = require('socket.io-client');
const socket = io('http://localhost:6001');

socket.on('connect', () => {
    console.log('Connected to WebSocket');
    socket.emit('subscribe', { channel: 'accounts' });
});

socket.on('AccountEvent', (data) => {
    console.log('Account event:', data);
});
```

## Production Setup

### Using Laravel Reverb (Recommended)

1. Install Laravel Reverb:
```bash
composer require laravel/reverb
php artisan reverb:install
```

2. Configure `.env`:
```env
BROADCAST_DRIVER=reverb
REVERB_APP_ID=your_app_id
REVERB_APP_KEY=your_app_key
REVERB_APP_SECRET=your_app_secret
```

3. Start Reverb server:
```bash
php artisan reverb:start
```

### Using Pusher

1. Sign up for [Pusher](https://pusher.com)

2. Get your credentials and update `.env`:
```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=mt1
```

3. Install Pusher PHP SDK:
```bash
composer require pusher/pusher-php-server
```

## Testing

Run the WebSocket tests:

```bash
php artisan test --filter=WebSocketTest
```

All tests verify that:
- REST API calls return correct responses
- Events are dispatched for each operation
- Event data includes action, data, id (where applicable), and timestamp

## Development Mode

In development mode with `BROADCAST_DRIVER=log`, events are logged to your Laravel log file:

```bash
# Watch logs in real-time
php artisan pail

# Or tail the log file
tail -f storage/logs/laravel.log
```

## Security Considerations

1. **Channel Authorization**: Update `routes/channels.php` to add proper authentication checks
2. **Role-based Access**: Implement admin role checks for admin-only channels
3. **Rate Limiting**: Apply rate limiting to prevent abuse
4. **Data Sanitization**: Ensure broadcasted data doesn't include sensitive information

## Troubleshooting

### Events Not Broadcasting

1. Check `BROADCAST_DRIVER` in `.env`
2. Verify queue workers are running: `php artisan queue:work`
3. Check Laravel logs: `php artisan pail` or `storage/logs/laravel.log`

### Connection Issues

1. Verify WebSocket server is running (Reverb/Pusher)
2. Check firewall settings
3. Verify client configuration matches server settings

### Authentication Issues

1. Ensure broadcasting routes are registered in `bootstrap/app.php`
2. Check channel authorization in `routes/channels.php`
3. Verify authentication middleware is applied

## Future Enhancements

- [ ] Add presence channels for user tracking
- [ ] Implement private channels with authentication
- [ ] Add message queuing for reliability
- [ ] Create WebSocket client library
- [ ] Add metrics and monitoring
- [ ] Implement event replay functionality
