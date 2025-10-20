# Data Core Features

Complete feature overview for the Data Core entity management system.

## API Documentation & Testing

### 🔍 Interactive API Documentation (Scribe)
**Location**: `/api/docs` (development mode only)

- **Comprehensive Documentation**: Auto-generated from code annotations
- **Multiple Language Examples**: Bash, JavaScript, PHP, and Python
- **Request/Response Examples**: Real examples for every endpoint
- **Authentication Guides**: Token generation and usage instructions
- **Try It Out**: Test endpoints directly from documentation

**Key Features**:
- Powered by Scribe 5.3.0
- OpenAPI 3.0 specification
- Postman collection export
- Dark/light theme
- Mobile-responsive design

### 🎮 API Playground (Swagger UI)
**Location**: `/api/playground` (development mode only)

- **Interactive Testing**: Test all endpoints in real-time
- **Auto-populated**: Uses Scribe's OpenAPI specification
- **Token Management**: Instructions for generating test tokens
- **Request Builder**: Visual interface for building API requests
- **Response Inspector**: View formatted responses

**Security**: Both documentation and playground are restricted to development mode (`APP_DEBUG=true`)

### 📥 Downloadable Formats
- **OpenAPI Spec**: `/api/docs.openapi` - Standard OpenAPI 3.0 YAML
- **Postman Collection**: `/api/docs.postman` - Import into Postman for testing

---

## Unified Business Logic (Actions)

### 🎯 Architecture Pattern
**Actions** are single-responsibility classes that encapsulate business logic, used by both REST and SOAP APIs.

### Account Actions
Located in `app/Actions/Account/`:

1. **GetAccountsAction** - Retrieve all accounts
2. **GetAccountAction** - Retrieve single account
3. **CreateAccountAction** - Create new account
4. **UpdateAccountAction** - Update existing account
5. **DeleteAccountAction** - Delete account

### Entity Actions
Located in `app/Actions/Entity/`:

1. **GetEntitiesAction** - Retrieve all entities
2. **GetEntityAction** - Retrieve single entity
3. **CreateEntityAction** - Create new entity
4. **UpdateEntityAction** - Update existing entity
5. **DeleteEntityAction** - Delete entity

### Benefits
- ✅ **Single Source of Truth**: Business logic in one place
- ✅ **Protocol Agnostic**: Same logic for REST and SOAP
- ✅ **Easy to Test**: Test business logic independently
- ✅ **Easy to Maintain**: Change logic once, affects all APIs
- ✅ **Type Safe**: Full type hinting throughout

### Usage Example

**REST Controller** (Dependency Injection):
```php
public function index(GetAccountsAction $action): JsonResponse
{
    $accounts = $action->execute();
    return $this->success($accounts);
}
```

**SOAP Controller** (Direct Instantiation):
```php
public function getAccounts()
{
    $action = new GetAccountsAction;
    $accounts = $action->execute();
    return ['success' => true, 'data' => $accounts->toArray()];
}
```

---

## Entity Loading System

### 🚀 One-Command Entity Setup

Load complete entity definitions with a single command:

```bash
php artisan entity:load entities/countries.php
```

This automatically:
1. Creates/updates entity in MySQL
2. Syncs all entity fields
3. Creates MongoDB indexes
4. Generates API endpoints
5. Applies validation rules

### 📋 Entity Configuration Format

Entities are defined in PHP configuration files:

```php
return [
    'entities' => [
        [
            'name' => 'countries',
            'display_name' => 'Countries',
            'description' => 'Global countries data',
            'collection_name' => 'countries_collection',
            'is_active' => true,
            
            'schema' => [/* JSON Schema */],
            'fields' => [/* Field Definitions */],
            'indexes' => [/* MongoDB Indexes */],
        ],
    ],
];
```

### 🏗️ Entity Components

#### 1. Schema (JSON Schema)
Defines data structure and validation at the schema level:
```php
'schema' => [
    'type' => 'object',
    'required' => ['code', 'name'],
    'properties' => [
        'code' => ['type' => 'string', 'pattern' => '^[A-Z]{2}$'],
        'name' => ['type' => 'string'],
    ],
]
```

#### 2. Fields
Define fields with Laravel validation:
```php
'fields' => [
    [
        'name' => 'code',
        'display_name' => 'Country Code',
        'type' => 'string',
        'is_required' => true,
        'is_indexed' => true,
        'validation_rules' => 'required|string|size:2',
    ],
]
```

**Supported Types**:
- `string`, `integer`, `decimal`, `boolean`
- `json`, `date`, `datetime`

#### 3. MongoDB Indexes
Optimize query performance:
```php
'indexes' => [
    ['keys' => ['code' => 1], 'options' => ['unique' => true]],
    ['keys' => ['name' => 1], 'options' => ['name' => 'name_idx']],
]
```

### 🌍 Countries Entity Example

Complete example at `entities/countries.php`:

**Fields (14 total)**:
- ISO codes (alpha-2, alpha-3, numeric)
- Names (common and official)
- Geographic data (region, subregion, capital)
- Demographics (population, area)
- Localization (currencies, languages, timezones)
- Visual (flag images)
- Status (is_active)

**Features**:
- Comprehensive JSON Schema validation
- Laravel validation rules on all fields
- 5 optimized MongoDB indexes
- Full documentation

**Load It**:
```bash
php artisan entity:load entities/countries.php
```

### 📍 Auto-Generated Endpoints

After loading, entities get automatic endpoints:

**REST API**:
- `GET /api/v1/entity/countries` - List
- `GET /api/v1/entity/countries/{id}` - Show
- `POST /api/v1/entity/countries` - Create
- `PUT /api/v1/entity/countries/{id}` - Update
- `DELETE /api/v1/entity/countries/{id}` - Delete

**SOAP API**:
- `getCountries()`, `getCountry($id)`
- `createCountry($data)`, `updateCountry($id, $data)`
- `deleteCountry($id)`

---

## Development Protocol

### 📚 IMPLEMENTATION_PROTOCOL.md

Comprehensive 17KB development guide covering:

#### 1. Architecture
- Multi-layered architecture diagram
- Design principles
- Action-based pattern explanation

#### 2. Code Organization
- Directory structure
- Naming conventions
- File organization standards

#### 3. Development Standards
- PHP 8.2+ requirements
- PSR-12 code style
- Type safety guidelines
- Code quality tools

#### 4. API Development
- Step-by-step guide for new endpoints
- Creating Actions
- REST controller setup
- SOAP controller setup
- Route registration
- Documentation generation

#### 5. Database Management
- Migration standards
- Model best practices
- MongoDB integration
- Multiple connection handling

#### 6. Testing
- Test structure
- Unit test examples
- Feature test examples
- Coverage requirements (80% minimum)

#### 7. Security
- Authentication (dev tokens, Keycloak)
- Input validation
- Authorization
- Security best practices
- CodeQL integration

#### 8. Documentation
- PHPDoc standards
- API documentation (Scribe)
- Markdown documentation

#### 9. Git Workflow
- Branch strategy
- Commit message format (Conventional Commits)
- Pull request guidelines

#### 10. Deployment
- Pre-deployment checklist
- Docker deployment steps
- Manual deployment steps
- Environment configuration
- Rollback procedures

---

## Security Features

### 🔒 Development Mode Protection

**DevOnly Middleware**: Restricts sensitive features to development:
- API documentation (`/api/docs`)
- API playground (`/api/playground`)
- Returns 404 when `APP_DEBUG=false`

### 🛡️ Input Validation

All API endpoints validate input:
```php
$validated = $request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:accounts,email',
    'role' => 'nullable|string|max:50',
]);
```

### 🔐 Authentication

**Development**:
```bash
php artisan token:generate --role=admin --expires=7200
```

**Production**:
- Keycloak integration
- JWT token validation
- Identity provider linking

### ✅ Security Scanning

- **CodeQL**: Automated security vulnerability detection
- **Laravel Pint**: Code style and potential issues
- **PHPUnit**: Test coverage for critical paths

---

## Protocol Support

### 🌐 REST API

**Base URL**: `/api/v1/`

**Features**:
- JSON request/response
- Standard HTTP methods (GET, POST, PUT, DELETE)
- RESTful resource routing
- Consistent response format

**Response Format**:
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {}
}
```

### 🔌 SOAP API

**Endpoint**: `/soap`
**WSDL**: `/soap?wsdl`

**Features**:
- XML-based protocol
- WSDL definition
- Legacy system compatibility
- Same business logic as REST (via Actions)

**PHP SoapClient Compatible**:
```php
$client = new SoapClient('http://localhost:8000/soap?wsdl');
$result = $client->getAccounts();
```

---

## Data Storage Architecture

### 🗄️ MySQL (Primary)

**Purpose**: Structural data and schema

**Stores**:
- Account information
- Roles and permissions
- Entity definitions
- Entity fields
- Entity triggers
- Identity mappings
- CORS settings

### 🗄️ MySQL (Secondary)

**Purpose**: Additional structural data separation

**Use Cases**:
- Separate concerns
- Performance optimization
- Data isolation

### 🍃 MongoDB

**Purpose**: Entity records and document storage

**Stores**:
- Dynamic entity records
- Countries data
- Any custom entity data

**Features**:
- Schema-less flexibility
- Horizontal scaling
- Document-based queries
- Automatic indexing

---

## Testing & Quality

### ✅ Test Suite

**20 Tests, 67 Assertions**:
- Unit Tests: Model logic
- Feature Tests: API endpoints
- SOAP Tests: SOAP functionality

**Run Tests**:
```bash
php artisan test
```

### 🎨 Code Style

**Laravel Pint (PSR-12)**:
```bash
./vendor/bin/pint        # Fix issues
./vendor/bin/pint --test # Check only
```

**Standards**:
- PSR-12 compliant
- Consistent formatting
- Type safety
- PHPDoc blocks

### 📊 Code Quality

- **Type Hints**: Used throughout
- **Return Types**: All methods declare return types
- **Null Safety**: Proper nullable handling
- **Error Handling**: Comprehensive try-catch blocks

---

## Commands Reference

### Entity Management
```bash
# Load entity from file
php artisan entity:load entities/countries.php

# With verbose output
php artisan entity:load entities/countries.php -v

# Force reload
php artisan entity:load entities/countries.php --force
```

### Token Generation
```bash
# Default user token
php artisan token:generate

# Admin token with custom expiry
php artisan token:generate --role=admin --expires=7200
```

### Development
```bash
# Run tests
php artisan test

# Fix code style
./vendor/bin/pint

# Generate API docs
php artisan scribe:generate
```

---

## Documentation

### Primary Documents

1. **README.md** - Project overview and quick start
2. **DEVELOPMENT.md** - Development setup and commands
3. **IMPLEMENTATION_PROTOCOL.md** - Comprehensive development guide
4. **API_USAGE.md** - API usage examples (REST and SOAP)
5. **DOCKER.md** - Docker deployment guide
6. **FEATURES.md** - This document (feature overview)
7. **CHANGELOG.md** - Version history and changes

### Entity Documentation

- **entities/README.md** - Entity configuration guide
- **entities/countries.php** - Complete entity example

### API Documentation

- **Live Docs**: `/api/docs` (dev mode)
- **Playground**: `/api/playground` (dev mode)
- **OpenAPI Spec**: `/api/docs.openapi`
- **Postman**: `/api/docs.postman`

---

## Getting Started

### 1. Installation
```bash
git clone https://github.com/processton/data-core.git
cd data-core
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Database Setup
```bash
php artisan migrate
```

### 3. Load Sample Entity
```bash
php artisan entity:load entities/countries.php
```

### 4. Generate Dev Token
```bash
php artisan token:generate --role=admin
```

### 5. View Documentation
```
Open: http://localhost:8000/api/docs
```

---

## Enterprise Features

### 🏢 Production-Ready

- ✅ Multi-database architecture
- ✅ Horizontal scaling support (MongoDB)
- ✅ Docker containerization
- ✅ Environment-based configuration
- ✅ Logging and monitoring ready
- ✅ Security best practices
- ✅ API versioning (`/api/v1/`)
- ✅ CORS configuration
- ✅ Rate limiting ready
- ✅ Queue system integration

### 🔄 Extensibility

- ✅ Action-based architecture (easy to extend)
- ✅ Dynamic entity system
- ✅ Plugin-ready structure
- ✅ Middleware support
- ✅ Event-driven triggers
- ✅ Webhook system

### 📈 Scalability

- ✅ MongoDB for entity data (horizontal scaling)
- ✅ Queue support for async operations
- ✅ Multiple database connections
- ✅ Stateless API design
- ✅ Docker deployment
- ✅ Load balancer ready

---

## Support & Resources

### Internal Resources

- API Documentation: `/api/docs`
- API Playground: `/api/playground`
- WSDL: `/soap?wsdl`
- Implementation Protocol: `IMPLEMENTATION_PROTOCOL.md`

### External Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Scribe Documentation](https://scribe.knuckles.wtf/laravel)
- [MongoDB PHP Library](https://www.mongodb.com/docs/php-library/)
- [OpenAPI Specification](https://swagger.io/specification/)

### Getting Help

1. Check documentation (this file and IMPLEMENTATION_PROTOCOL.md)
2. Review API documentation at `/api/docs`
3. Search GitHub issues
4. Create new issue with details

---

**Version**: 1.0  
**Last Updated**: 2025-10-20  
**Maintained By**: Data Core Development Team
