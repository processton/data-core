# Data Core

A flexible entity management system with dynamic schema support, built on Laravel framework.

## About Data Core

Data Core is a powerful backend system designed to manage dynamic entities with flexible schemas. It provides a robust API for entity management, webhooks, and identity management, with support for both relational and document-based storage.

### Repository Goals

- ✅ API-first architecture (REST APIs)
- ✅ WSDL support for legacy integrations
- ✅ Dynamic entity management with flexible schemas
- ✅ Multi-database support (MySQL 8 + MongoDB)
- ✅ Keycloak integration for authentication
- ✅ Role-based access control (RBAC)
- ✅ Webhook support for entity triggers
- ✅ API documentation and playground
- ✅ Dockerized deployment
- ✅ Admin management for entities, fields, triggers, identities, and indexing
- ✅ Configurable CORS settings

## Key Features

### Account Management
- Multiple usernames per account
- Role and type-based permissions
- Keycloak-based authentication (no local passwords)

### Entity System
- Dynamic entity creation and management
- Flexible field definitions
- Custom triggers and webhooks
- MongoDB storage for entity records
- MySQL storage for structural schema

### Admin Capabilities
- Manage entities (objects) and their fields
- Configure entity triggers and webhooks
- Identity management
- Indexing configuration
- CORS settings management

## Architecture

### Databases
- **MySQL 8 (Primary)**: Structural schema, accounts, roles, permissions
- **MySQL 8 (Secondary)**: Additional structural data
- **MongoDB**: Entity records and documents

### Authentication
- Keycloak for all authentication and authorization
- Admin privileges controlled via environment variables
- Development access token generation command

## API Documentation

When running in development mode, comprehensive API documentation and interactive playground are available:
- **API Docs**: `/api/docs` - Full documentation with examples in Bash, JavaScript, PHP, and Python
- **API Playground**: `/api/playground` - Interactive Swagger UI for testing endpoints
- **OpenAPI Spec**: `/api/docs.openapi` - OpenAPI 3.0 specification
- **Postman Collection**: `/api/docs.postman` - Import into Postman for testing

For detailed API usage examples, see [API_USAGE.md](API_USAGE.md).  
For development guidelines and protocols, see [IMPLEMENTATION_PROTOCOL.md](IMPLEMENTATION_PROTOCOL.md).

### REST API

The REST API is available at `/api/v1/` with the following endpoints:
- **Accounts**: `/api/v1/accounts` - Full CRUD operations
- **Entities**: `/api/v1/entities` - Full CRUD operations

### SOAP API

The SOAP API is available at `/soap` with WSDL at `/soap?wsdl`.
All REST operations are available via SOAP for legacy system integrations.

See [API_USAGE.md](API_USAGE.md) for detailed examples of both REST and SOAP usage.

## Environment Configuration

Key environment variables:
```
ADMIN_ROLE=admin
ADMIN_USERNAME=admin@example.com

# Keycloak Configuration
KEYCLOAK_URL=
KEYCLOAK_REALM=
KEYCLOAK_CLIENT_ID=
KEYCLOAK_CLIENT_SECRET=

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=data_core
DB_USERNAME=root
DB_PASSWORD=

DB_CONNECTION_SECONDARY=mysql
DB_HOST_SECONDARY=127.0.0.1
DB_PORT_SECONDARY=3306
DB_DATABASE_SECONDARY=data_core_secondary
DB_USERNAME_SECONDARY=root
DB_PASSWORD_SECONDARY=

MONGODB_URI=mongodb://localhost:27017
MONGODB_DATABASE=data_core_entities
```

## Development

### Docker Setup
```bash
docker-compose up -d
```

### Generate Development Access Token
```bash
# Generate default user token
php artisan token:generate

# Generate admin token with custom expiry
php artisan token:generate --role=admin --expires=7200
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
