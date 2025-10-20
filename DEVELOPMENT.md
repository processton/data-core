# Development Guide

## Getting Started

### Prerequisites
- PHP 8.2 or higher
- Composer
- Docker and Docker Compose (for containerized setup)
- MySQL 8.0
- MongoDB 7.0

### Local Development Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/processton/data-core.git
   cd data-core
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Update database credentials in `.env`**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=data_core
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

5. **Run migrations**
   ```bash
   php artisan migrate
   ```

6. **Start development server**
   ```bash
   php artisan serve
   ```

### Docker Development Setup

1. **Start all services**
   ```bash
   docker-compose up -d
   ```

2. **Access the application**
   - Application: http://localhost:8000
   - MySQL Primary: localhost:3306
   - MySQL Secondary: localhost:3307
   - MongoDB: localhost:27017

3. **Run migrations inside container**
   ```bash
   docker-compose exec app php artisan migrate
   ```

4. **Stop services**
   ```bash
   docker-compose down
   ```

## Development Commands

### Generate Access Token (Development)
```bash
# Generate default user token (expires in 1 hour)
php artisan token:generate

# Generate admin token (expires in 2 hours)
php artisan token:generate --role=admin --expires=7200

# The command outputs the token and instructions for use
```

Use the generated token in API requests:
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" http://localhost:8000/api/v1/accounts
```

### Run Tests
```bash
php artisan test
php artisan test --filter=AccountTest
```

### Code Styling
```bash
./vendor/bin/pint        # Fix styling issues
./vendor/bin/pint --test # Check for styling issues
```

### Database Operations
```bash
php artisan migrate              # Run migrations
php artisan migrate:fresh        # Fresh migration
php artisan migrate:rollback     # Rollback last migration
php artisan db:seed              # Run seeders
```

## API Endpoints

### Health Check
```bash
GET /api/health
```

### Documentation (Development Mode Only)
```bash
GET /api/docs                # Interactive API documentation (Scribe)
GET /api/playground          # Interactive API testing (Swagger UI)
GET /api/docs.openapi        # OpenAPI 3.0 specification
GET /api/docs.postman        # Postman collection
```

**Note**: These endpoints are only available when `APP_DEBUG=true`.

### Account Management
```bash
GET    /api/v1/accounts
POST   /api/v1/accounts
GET    /api/v1/accounts/{id}
PUT    /api/v1/accounts/{id}
DELETE /api/v1/accounts/{id}
```

### Entity Management (Admin)
```bash
GET    /api/v1/entities
POST   /api/v1/entities
GET    /api/v1/entities/{id}
PUT    /api/v1/entities/{id}
DELETE /api/v1/entities/{id}
```

### Role Management (Admin)
```bash
GET    /api/v1/roles
POST   /api/v1/roles
GET    /api/v1/roles/{id}
PUT    /api/v1/roles/{id}
DELETE /api/v1/roles/{id}
```

### Permission Management (Admin)
```bash
GET    /api/v1/permissions
POST   /api/v1/permissions
GET    /api/v1/permissions/{id}
PUT    /api/v1/permissions/{id}
DELETE /api/v1/permissions/{id}
```

### CORS Settings (Admin)
```bash
GET /api/v1/cors-settings
PUT /api/v1/cors-settings
```

## Testing API Endpoints

### Using cURL
```bash
# Generate a token
TOKEN=$(php artisan token:generate --role=admin | grep "Token:" | cut -d' ' -f2)

# Make API request
curl -H "Authorization: Bearer $TOKEN" http://localhost:8000/api/health
```

### Using Postman
1. Generate a token: `php artisan token:generate --role=admin`
2. Add header: `Authorization: Bearer YOUR_TOKEN`
3. Make requests to API endpoints

## Database Schema

### Primary MySQL Database
- accounts
- roles
- permissions
- role_permissions
- account_usernames
- identities
- entities
- entity_fields
- entity_triggers
- cors_settings

### MongoDB Database
- Entity records (dynamic collections based on entity definitions)

## Authentication

### Development Mode
Use the `token:generate` command to create development tokens:
```bash
php artisan token:generate --role=admin
```

### Production Mode
Authentication is handled through Keycloak. Configure in `.env`:
```env
KEYCLOAK_URL=http://your-keycloak-server:8080
KEYCLOAK_REALM=data-core
KEYCLOAK_CLIENT_ID=data-core-api
KEYCLOAK_CLIENT_SECRET=your-secret
```

## Admin Configuration

Admins are identified by environment variables:
```env
ADMIN_ROLE=admin
ADMIN_USERNAME=admin@example.com
```

## Troubleshooting

### Database Connection Issues
- Check MySQL/MongoDB services are running
- Verify credentials in `.env`
- Test connection: `php artisan tinker` then `DB::connection()->getPdo();`

### Migration Errors
- Ensure database exists
- Check migration order
- Use `php artisan migrate:fresh` for clean slate (⚠️ destroys data)

### Docker Issues
- Rebuild containers: `docker-compose up -d --build`
- Check logs: `docker-compose logs app`
- Reset volumes: `docker-compose down -v`

## Contributing

1. Create a feature branch
2. Make changes
3. Run tests: `php artisan test`
4. Fix styling: `./vendor/bin/pint`
5. Commit with descriptive message
6. Create pull request

## Architecture and Best Practices

For comprehensive development guidelines, architecture details, and best practices, refer to:
- [IMPLEMENTATION_PROTOCOL.md](IMPLEMENTATION_PROTOCOL.md) - Complete development protocol

## Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [MongoDB PHP Library](https://www.mongodb.com/docs/php-library/current/)
- [Keycloak Documentation](https://www.keycloak.org/documentation)
- [Scribe API Documentation](https://scribe.knuckles.wtf/laravel)
- [OpenAPI Specification](https://swagger.io/specification/)
