# Data Core Modules

This directory contains optional modules that extend Data Core functionality. Modules provide a clean way to add custom entities, compliance features, and API endpoints.

## What is a Module?

A module is a self-contained package that can:

1. **Register Entity Types** - Define new entity schemas
2. **Register Compliance Features** - Add compliance requirements that affect behavior
3. **Provide Custom APIs** - Expose module-specific REST endpoints
4. **Register Entity Identities** - Define alternate identification methods for entities

## Directory Structure

```
modules/
├── README.md (this file)
└── ExampleModule/          # Sample module demonstrating the system
    ├── Controllers/        # Module controllers
    ├── README.md          # Module documentation
    └── ExampleModuleServiceProvider.php  # Module registration
```

## Creating a New Module

### 1. Create Module Directory

```bash
mkdir -p modules/YourModule/Controllers
```

### 2. Create Service Provider

Create `modules/YourModule/YourModuleServiceProvider.php`:

```php
<?php

namespace Modules\YourModule;

use App\Services\Module\ModuleRegistrationService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class YourModuleServiceProvider extends ServiceProvider
{
    public function boot(ModuleRegistrationService $moduleService): void
    {
        $moduleService->registerModule('your_module', [
            'entities' => [
                // Define your entity types
            ],
            'compliance_features' => [
                // Define compliance features
            ],
            'routes' => function () {
                // Define module routes
            },
        ]);
    }
}
```

### 3. Register Service Provider

Add to `config/app.php`:

```php
'providers' => ServiceProvider::defaultProviders()->merge([
    // ...
    Modules\YourModule\YourModuleServiceProvider::class,
])->toArray(),
```

### 4. Update Autoloading

The `Modules\` namespace is already configured in `composer.json`. After creating your module, run:

```bash
composer dump-autoload
```

## Example Module

See `ExampleModule/` for a complete working example that demonstrates:

- Entity registration
- Compliance feature registration
- Custom API routes
- Controller implementation

## Module API Routes

All module routes are automatically prefixed:

- Module routes: `/api/v1/modules/{module_name}/...`
- Example: `/api/v1/modules/example/items`

## Documentation

See the main [DEVELOPMENT.md](../DEVELOPMENT.md) for comprehensive documentation on:

- Module registration details
- Entity identity service usage
- Compliance feature management
- API permissions and access control

## Best Practices

1. **Unique Names**: Use unique entity names and compliance codes
2. **Namespace**: Use the `Modules\YourModule` namespace
3. **Documentation**: Include a README.md in your module directory
4. **Testing**: Write tests for your module functionality
5. **Isolation**: Keep module code self-contained
6. **Dependencies**: Document any external dependencies

## Compliance Features

Modules can register compliance features that affect behavior:

```php
'compliance_features' => [
    [
        'code' => 'YOUR_COMPLIANCE_CODE',
        'name' => 'Your Compliance Feature',
        'description' => 'What this compliance feature does',
        'is_enabled' => false,
    ],
]
```

Check if enabled in your code:

```php
use App\Models\ComplianceFeature;

if (ComplianceFeature::where('code', 'YOUR_COMPLIANCE_CODE')->where('is_enabled', true)->exists()) {
    // Apply compliance-specific logic
}
```

## Entity Identities

Register alternate identifiers for entities:

```php
use App\Services\Entity\EntityIdentityService;

$identityService = app(EntityIdentityService::class);
$identityService->registerIdentity($entityId, 'uuid', 'unique-id-here');
```

Find entities by their identities:

```php
$entity = $identityService->findEntityByIdentity('uuid', 'unique-id-here');
```

## Support

For questions or issues:

1. Check the [DEVELOPMENT.md](../DEVELOPMENT.md) documentation
2. Review the ExampleModule implementation
3. Open an issue on the repository
