# Example Module

This is a demonstration module showing how to extend Data Core with custom functionality.

## Structure

```
ExampleModule/
├── Controllers/
│   └── ExampleController.php    # Module-specific controllers
└── ExampleModuleServiceProvider.php  # Module registration and configuration
```

## Features

- **Entity Registration**: Automatically creates `example_entity` entity type
- **Compliance Features**: Registers `EXAMPLE_COMPLIANCE` feature
- **Custom Routes**: Provides REST API endpoints under `/api/v1/modules/example`

## API Endpoints

All routes are automatically prefixed with `/api/v1/modules/example`:

- `GET /api/v1/modules/example/items` - List example items
- `GET /api/v1/modules/example/items/{id}` - Get a specific item
- `POST /api/v1/modules/example/items` - Create a new item

## Usage

### 1. Register the Module

Add the service provider to your `config/app.php`:

```php
'providers' => ServiceProvider::defaultProviders()->merge([
    // ... other providers
    Modules\ExampleModule\ExampleModuleServiceProvider::class,
])->toArray(),
```

### 2. Enable Autoloading

Update `composer.json` to include the modules directory:

```json
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Modules\\": "modules/",
        "Database\\Factories\\": "database/factories/",
        "Database\\Seeders\\": "database/seeders/"
    }
}
```

Run `composer dump-autoload` after updating.

### 3. Test the Module

```bash
# Generate a token
TOKEN=$(php artisan token:generate --role=user | grep "Token:" | cut -d' ' -f2)

# Test the module API
curl -H "Authorization: Bearer $TOKEN" http://localhost:8000/api/v1/modules/example/items
```

## Customization

### Adding More Routes

Edit `ExampleModuleServiceProvider.php` and add routes in the `routes` callback:

```php
'routes' => function () {
    Route::get('/items', [ExampleController::class, 'index']);
    Route::post('/items', [ExampleController::class, 'store']);
    Route::get('/custom', [ExampleController::class, 'custom']);
},
```

### Using Entity Identities

Register identities for your entities:

```php
use App\Services\Entity\EntityIdentityService;

$identityService = app(EntityIdentityService::class);

// Find the entity by name
$entity = Entity::where('name', 'example_entity')->first();

// Register multiple identities
$identityService->registerMultipleIdentities($entity->id, [
    ['key' => 'uuid', 'value' => 'unique-uuid-here', 'metadata' => null],
    ['key' => 'external_id', 'value' => 'ext-12345', 'metadata' => ['source' => 'external_system']],
]);
```

### Compliance-Aware Behavior

Check compliance features and modify behavior:

```php
use App\Models\ComplianceFeature;

public function store(Request $request)
{
    $complianceEnabled = ComplianceFeature::where('code', 'EXAMPLE_COMPLIANCE')
        ->where('is_enabled', true)
        ->exists();
    
    if ($complianceEnabled) {
        // Apply stricter validation
        $request->validate([
            'title' => 'required|string|max:100',
            'audit_trail' => 'required',
        ]);
    }
    
    // ... rest of logic
}
```

## Best Practices

1. **Prefix all routes** - Module routes are automatically prefixed, don't add additional prefixes
2. **Use descriptive entity names** - Entity names should be unique across all modules
3. **Document compliance features** - Clearly explain what each compliance feature affects
4. **Follow naming conventions** - Use snake_case for entity names, SCREAMING_SNAKE_CASE for compliance codes
