# Implementation and Development Protocol

This document outlines the standards, patterns, and best practices for developing and contributing to the Data Core project.

## Table of Contents

1. [Architecture Overview](#architecture-overview)
2. [Code Organization](#code-organization)
3. [Development Standards](#development-standards)
4. [API Development](#api-development)
5. [Database Management](#database-management)
6. [Testing Requirements](#testing-requirements)
7. [Security Guidelines](#security-guidelines)
8. [Documentation Standards](#documentation-standards)
9. [Git Workflow](#git-workflow)
10. [Deployment Process](#deployment-process)

---

## Architecture Overview

### System Design

Data Core follows a **multi-layered architecture**:

```
┌─────────────────────────────────────────┐
│         API Layer (REST/SOAP)           │
│  ┌──────────────┐   ┌──────────────┐   │
│  │ REST API     │   │ SOAP API     │   │
│  │ Controllers  │   │ Controllers  │   │
│  └──────────────┘   └──────────────┘   │
└─────────────┬───────────────────────────┘
              │
┌─────────────▼───────────────────────────┐
│          Action Layer                   │
│     (Unified Business Logic)            │
│  ┌──────────────┐   ┌──────────────┐   │
│  │ Account      │   │ Entity       │   │
│  │ Actions      │   │ Actions      │   │
│  └──────────────┘   └──────────────┘   │
└─────────────┬───────────────────────────┘
              │
┌─────────────▼───────────────────────────┐
│          Model Layer                    │
│  (Eloquent Models + Relationships)      │
└─────────────┬───────────────────────────┘
              │
┌─────────────▼───────────────────────────┐
│        Data Storage Layer               │
│  ┌───────────┐  ┌─────────────────┐    │
│  │  MySQL 8  │  │    MongoDB      │    │
│  │ (Schema)  │  │ (Entity Data)   │    │
│  └───────────┘  └─────────────────┘    │
└─────────────────────────────────────────┘
```

### Key Design Principles

1. **Action-Based Architecture**: Business logic is centralized in Action classes, not controllers
2. **Protocol Agnostic**: REST and SOAP share the same business logic through Actions
3. **Separation of Concerns**: Controllers handle protocol specifics, Actions handle business logic
4. **Single Responsibility**: Each Action performs one specific operation
5. **Dependency Injection**: Actions and services are injected into controllers

---

## Code Organization

### Directory Structure

```
app/
├── Actions/                    # Business logic (single source of truth)
│   ├── Account/               # Account-related actions
│   │   ├── GetAccountsAction.php
│   │   ├── GetAccountAction.php
│   │   ├── CreateAccountAction.php
│   │   ├── UpdateAccountAction.php
│   │   └── DeleteAccountAction.php
│   └── Entity/                # Entity-related actions
│       ├── GetEntitiesAction.php
│       ├── GetEntityAction.php
│       ├── CreateEntityAction.php
│       ├── UpdateEntityAction.php
│       └── DeleteEntityAction.php
├── Http/
│   ├── Controllers/
│   │   ├── Api/               # REST API controllers
│   │   │   ├── ApiController.php
│   │   │   ├── AccountController.php
│   │   │   └── EntityController.php
│   │   └── Soap/              # SOAP API controllers
│   │       ├── SoapService.php
│   │       ├── SoapServerController.php
│   │       ├── AccountSoapController.php
│   │       └── EntitySoapController.php
│   └── Middleware/
│       └── DevOnly.php        # Development-only access
└── Models/                    # Eloquent models
    ├── Account.php
    ├── Entity.php
    └── ...
```

### Naming Conventions

- **Actions**: `{Verb}{Resource}Action.php` (e.g., `CreateAccountAction.php`)
- **Controllers**: `{Resource}Controller.php` (e.g., `AccountController.php`)
- **Models**: `{Resource}.php` (e.g., `Account.php`)
- **Middleware**: `{Purpose}.php` (e.g., `DevOnly.php`)

---

## Development Standards

### PHP Standards

- **Version**: PHP 8.2+
- **Framework**: Laravel 12
- **Code Style**: PSR-12 compliant (enforced by Laravel Pint)
- **Type Declarations**: Use strict types and return type declarations

### Code Style Guidelines

1. **Always use type hints**:
   ```php
   public function execute(int $id): ?Account
   {
       return Account::find($id);
   }
   ```

2. **Use dependency injection**:
   ```php
   // Good
   public function store(Request $request, CreateAccountAction $action): JsonResponse
   
   // Bad
   public function store(Request $request): JsonResponse
   {
       $action = new CreateAccountAction();
   }
   ```

3. **Keep methods focused**:
   - Each method should do one thing
   - Controller methods should delegate to Actions
   - Actions should handle business logic only

4. **Use descriptive variable names**:
   ```php
   // Good
   $account = $action->execute($id);
   
   // Bad
   $a = $action->execute($id);
   ```

### Running Code Quality Tools

```bash
# Fix code style issues
./vendor/bin/pint

# Check code style without fixing
./vendor/bin/pint --test

# Run all tests
php artisan test

# Run specific test
php artisan test --filter=AccountTest
```

---

## API Development

### Creating New Endpoints

To add a new resource to the API, follow these steps:

#### 1. Create the Model

```bash
php artisan make:model Product -m
```

#### 2. Create Actions

Create Action classes for each operation:

```php
// app/Actions/Product/GetProductsAction.php
namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class GetProductsAction
{
    public function execute(): Collection
    {
        return Product::all();
    }
}
```

#### 3. Create REST API Controller

```php
// app/Http/Controllers/Api/ProductController.php
namespace App\Http\Controllers\Api;

use App\Actions\Product\GetProductsAction;

class ProductController extends ApiController
{
    public function index(GetProductsAction $action): JsonResponse
    {
        $products = $action->execute();
        return $this->success($products, 'Products retrieved successfully');
    }
}
```

#### 4. Create SOAP Controller

```php
// app/Http/Controllers/Soap/ProductSoapController.php
namespace App\Http\Controllers\Soap;

use App\Actions\Product\GetProductsAction;

class ProductSoapController
{
    public function getProducts()
    {
        $action = new GetProductsAction;
        $products = $action->execute();
        
        return [
            'success' => true,
            'message' => 'Products retrieved successfully',
            'data' => $products->toArray(),
        ];
    }
}
```

#### 5. Register Routes

```php
// routes/api.php
Route::apiResource('products', ProductController::class);
```

#### 6. Add to SOAP Service

```php
// app/Http/Controllers/Soap/SoapService.php
protected ProductSoapController $productController;

public function __construct()
{
    $this->productController = new ProductSoapController;
}

public function getProducts()
{
    return $this->productController->getProducts();
}
```

#### 7. Add API Documentation

Add Scribe annotations to the REST controller:

```php
/**
 * @group Product Management
 *
 * APIs for managing products
 */
class ProductController extends ApiController
{
    /**
     * List products
     *
     * Get a list of all products.
     *
     * @response {
     *   "success": true,
     *   "message": "Products retrieved successfully",
     *   "data": []
     * }
     */
    public function index(GetProductsAction $action): JsonResponse
}
```

#### 8. Generate Documentation

```bash
php artisan scribe:generate
```

---

## Database Management

### Database Connections

Data Core uses multiple database connections:

1. **mysql** (Primary): Structural data (accounts, roles, entities)
2. **mysql_secondary**: Additional structural data (optional)
3. **mongodb**: Entity records and document storage

### Migration Standards

1. **Naming**: Use descriptive names
   ```bash
   php artisan make:migration create_products_table
   php artisan make:migration add_status_to_products_table
   ```

2. **Rollback Support**: Always implement `down()` method
   ```php
   public function up()
   {
       Schema::create('products', function (Blueprint $table) {
           $table->id();
           $table->string('name');
           $table->timestamps();
       });
   }
   
   public function down()
   {
       Schema::dropIfExists('products');
   }
   ```

3. **Running Migrations**:
   ```bash
   php artisan migrate              # Run pending migrations
   php artisan migrate:fresh        # Drop all tables and re-run
   php artisan migrate:rollback     # Rollback last batch
   ```

### Model Standards

1. **Define fillable fields**:
   ```php
   protected $fillable = ['name', 'email', 'role'];
   ```

2. **Define relationships**:
   ```php
   public function usernames()
   {
       return $this->hasMany(AccountUsername::class);
   }
   ```

3. **Use type casting**:
   ```php
   protected $casts = [
       'is_active' => 'boolean',
       'schema' => 'array',
   ];
   ```

---

## Testing Requirements

### Test Structure

```
tests/
├── Unit/                      # Unit tests (models, actions)
│   ├── AccountTest.php
│   └── EntityTest.php
├── Feature/                   # Integration tests (API endpoints)
│   ├── RestApiTest.php
│   ├── SoapApiTest.php
│   └── EntityManagementTest.php
└── TestCase.php
```

### Writing Tests

#### Unit Tests

Test individual classes in isolation:

```php
public function test_account_can_be_created(): void
{
    $account = Account::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    $this->assertDatabaseHas('accounts', [
        'email' => 'test@example.com',
    ]);
}
```

#### Feature Tests

Test entire API flows:

```php
public function test_can_create_account(): void
{
    $response = $this->postJson('/api/v1/accounts', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'role' => 'user',
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'Account created successfully',
        ]);
}
```

### Test Coverage Requirements

- **Minimum**: 80% code coverage
- **Critical Paths**: 100% coverage for Actions and Controllers
- **All new features**: Must include tests

### Running Tests

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/RestApiTest.php

# Run specific test method
php artisan test --filter=test_can_create_account
```

---

## Security Guidelines

### Authentication

1. **Development Mode**:
   - Generate tokens: `php artisan token:generate --role=admin`
   - Include in requests: `Authorization: Bearer {token}`

2. **Production Mode**:
   - Authenticate via Keycloak
   - Validate JWT tokens
   - Link accounts via `identities` table

### Input Validation

Always validate user input:

```php
$validated = $request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:accounts,email',
    'role' => 'nullable|string|max:50',
]);
```

### Authorization

1. **Admin-only endpoints**: Implement middleware checks
2. **Resource ownership**: Verify user has access to resource
3. **Role-based access**: Check user role before operations

### Security Best Practices

1. **Never commit secrets**: Use `.env` for sensitive data
2. **Sanitize output**: Use Laravel's built-in escaping
3. **Use prepared statements**: Eloquent handles this automatically
4. **Rate limiting**: Implement for API endpoints
5. **HTTPS only**: In production environments

### Running Security Scans

```bash
# Run CodeQL security scan (in CI/CD)
# Automatically checks for vulnerabilities in code
```

---

## Documentation Standards

### Code Documentation

1. **PHPDoc blocks**: Add to all classes and public methods
   ```php
   /**
    * Execute the action to create a new account.
    *
    * @param array $data The account data
    * @return Account The created account
    * @throws \Exception If account creation fails
    */
   public function execute(array $data): Account
   ```

2. **API Documentation**: Use Scribe annotations
   ```php
   /**
    * @group Account Management
    * @bodyParam name string required The full name
    * @response 201 {"success": true, "data": {...}}
    */
   ```

### API Documentation

Generate and update API docs:

```bash
# Generate documentation
php artisan scribe:generate

# View docs (dev mode only)
# Navigate to: http://localhost:8000/api/docs
```

### Markdown Documentation

- Keep README.md up to date
- Update DEVELOPMENT.md for new features
- Document breaking changes in CHANGELOG.md

---

## Git Workflow

### Branch Strategy

```
main                    # Production-ready code
├── develop            # Development branch
    ├── feature/*      # New features
    ├── bugfix/*       # Bug fixes
    └── hotfix/*       # Critical production fixes
```

### Commit Message Format

Use conventional commits:

```
<type>(<scope>): <subject>

<body>

<footer>
```

Types:
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting)
- `refactor`: Code refactoring
- `test`: Adding tests
- `chore`: Maintenance tasks

Examples:
```
feat(api): add product management endpoints

Implements CRUD operations for products including:
- REST API endpoints
- SOAP API methods
- Action classes for business logic
- API documentation

Closes #123
```

### Pull Request Guidelines

1. **Title**: Clear and descriptive
2. **Description**: Include:
   - What was changed
   - Why it was changed
   - How to test
3. **Tests**: All tests must pass
4. **Code Style**: Must pass Pint checks
5. **Documentation**: Update if needed

---

## Deployment Process

### Pre-Deployment Checklist

- [ ] All tests passing
- [ ] Code style validated (Pint)
- [ ] Security scan completed (CodeQL)
- [ ] Documentation updated
- [ ] Environment variables configured
- [ ] Database migrations tested
- [ ] API documentation regenerated

### Deployment Steps

#### Docker Deployment

```bash
# 1. Build containers
docker-compose build

# 2. Start services
docker-compose up -d

# 3. Run migrations
docker-compose exec app php artisan migrate --force

# 4. Generate API docs
docker-compose exec app php artisan scribe:generate

# 5. Clear caches
docker-compose exec app php artisan optimize:clear
```

#### Manual Deployment

```bash
# 1. Pull latest code
git pull origin main

# 2. Install dependencies
composer install --no-dev --optimize-autoloader

# 3. Run migrations
php artisan migrate --force

# 4. Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Restart services
php artisan queue:restart
```

### Environment-Specific Configuration

#### Development
```env
APP_ENV=local
APP_DEBUG=true
```

#### Production
```env
APP_ENV=production
APP_DEBUG=false
```

### Rollback Procedure

```bash
# 1. Revert to previous deployment
git checkout <previous-commit>

# 2. Rollback migrations (if needed)
php artisan migrate:rollback --step=1

# 3. Clear caches
php artisan optimize:clear

# 4. Restart services
php artisan queue:restart
```

---

## Entity Object System

### Entity Lifecycle

1. **Define Entity**: Create entity definition in database
2. **Configure Schema**: Set JSON schema for entity
3. **Auto-Generate Endpoints**: System creates REST/SOAP endpoints
4. **Store Data**: Entity records stored in MongoDB
5. **Sync Schema**: Changes propagate automatically

### Entity Implementation

Coming soon: Detailed guide for entity object loading and schema synchronization.

---

## Monitoring and Logging

### Logging Standards

Use Laravel's built-in logging:

```php
use Illuminate\Support\Facades\Log;

// Info
Log::info('Account created', ['account_id' => $account->id]);

// Warning
Log::warning('Invalid input attempted', ['data' => $request->all()]);

// Error
Log::error('Failed to create account', ['error' => $e->getMessage()]);
```

### Monitoring

- **Application Logs**: `storage/logs/laravel.log`
- **API Requests**: Monitor via middleware
- **Database Queries**: Enable query logging in debug mode
- **Performance**: Use Laravel Debugbar in development

---

## Support and Resources

### Internal Resources

- **API Documentation**: `/api/docs` (dev mode)
- **API Playground**: `/api/playground` (dev mode)
- **WSDL**: `/soap?wsdl`

### External Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Best Practices](https://github.com/alexeymezenin/laravel-best-practices)
- [PHP Standards (PSR)](https://www.php-fig.org/psr/)
- [MongoDB PHP Library](https://www.mongodb.com/docs/php-library/)

---

## Getting Help

1. **Check Documentation**: Read this protocol and related docs
2. **Search Issues**: Look for similar issues in GitHub
3. **Ask Team**: Contact team members via Slack/Teams
4. **Create Issue**: Open a GitHub issue with details

---

## Contributing

All contributions must follow this protocol. By contributing, you agree to:

1. Follow code standards and best practices
2. Write tests for new features
3. Update documentation
4. Maintain backward compatibility
5. Pass all CI/CD checks

---

**Last Updated**: 2025-10-20  
**Version**: 1.0  
**Maintained By**: Data Core Development Team
