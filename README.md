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

When running in development mode, API documentation and playground are available at:
- **API Docs**: `/api/docs`
- **API Playground**: `/api/playground`

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
php artisan token:generate
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
