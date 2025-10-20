# Docker Deployment Guide

This guide covers deploying the Data Core application using Docker and Docker Compose.

## Architecture

The Docker setup includes:
- **Application Container**: PHP 8.2-FPM with Laravel application
- **Nginx Container**: Web server for serving the API
- **MySQL Primary**: Main structural database
- **MySQL Secondary**: Secondary structural database
- **MongoDB**: Document storage for entity records

## Quick Start

### Start All Services
```bash
docker-compose up -d
```

### Check Service Status
```bash
docker-compose ps
```

### View Logs
```bash
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f mysql_primary
docker-compose logs -f mongodb
```

### Stop Services
```bash
docker-compose down
```

### Stop and Remove Volumes (⚠️ Destroys Data)
```bash
docker-compose down -v
```

## Initial Setup

### 1. Build and Start Containers
```bash
docker-compose up -d --build
```

### 2. Install Dependencies (if not in image)
```bash
docker-compose exec app composer install
```

### 3. Generate Application Key
```bash
docker-compose exec app php artisan key:generate
```

### 4. Run Migrations
```bash
docker-compose exec app php artisan migrate
```

### 5. Access the Application
The API will be available at: http://localhost:8000

## Container Details

### Application Container
- **Base Image**: php:8.2-fpm
- **Working Directory**: /var/www/html
- **Extensions**: PDO MySQL, MongoDB, GD, ZIP, BCMath, etc.
- **Port**: 9000 (internal)

### Nginx Container
- **Base Image**: nginx:alpine
- **Configuration**: `/docker/nginx/conf.d/default.conf`
- **Port**: 8000 (maps to 80 internal)

### MySQL Primary Container
- **Image**: mysql:8.0
- **Database**: data_core
- **Port**: 3306
- **Volume**: mysql_primary_data

### MySQL Secondary Container
- **Image**: mysql:8.0
- **Database**: data_core_secondary
- **Port**: 3307
- **Volume**: mysql_secondary_data

### MongoDB Container
- **Image**: mongo:7
- **Database**: data_core_entities
- **Port**: 27017
- **Volume**: mongodb_data

## Common Operations

### Execute Artisan Commands
```bash
docker-compose exec app php artisan <command>
```

Examples:
```bash
docker-compose exec app php artisan migrate
docker-compose exec app php artisan token:generate --role=admin
docker-compose exec app php artisan test
```

### Access MySQL Primary
```bash
docker-compose exec mysql_primary mysql -u root -psecret data_core
```

### Access MySQL Secondary
```bash
docker-compose exec mysql_secondary mysql -u root -psecret data_core_secondary
```

### Access MongoDB
```bash
docker-compose exec mongodb mongosh data_core_entities
```

### Access Application Shell
```bash
docker-compose exec app bash
```

### Rebuild Application Container
```bash
docker-compose up -d --build app
```

## Environment Configuration

Environment variables are configured in `docker-compose.yml`. Key variables:

```yaml
environment:
  - APP_ENV=local
  - APP_DEBUG=true
  - DB_HOST=mysql_primary
  - DB_DATABASE=data_core
  - MONGODB_URI=mongodb://mongodb:27017
```

To use a custom `.env` file, mount it:
```yaml
volumes:
  - ./.env:/var/www/html/.env
```

## Production Deployment

### 1. Update Environment
Set production environment variables in `docker-compose.yml`:
```yaml
environment:
  - APP_ENV=production
  - APP_DEBUG=false
```

### 2. Use Production .env
Create a `.env.production` file and mount it:
```yaml
volumes:
  - ./.env.production:/var/www/html/.env
```

### 3. Optimize Application
```bash
docker-compose exec app composer install --no-dev --optimize-autoloader
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

### 4. Set Proper Permissions
```bash
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chmod -R 755 /var/www/html/storage
```

## Scaling

### Scale Application Containers
```bash
docker-compose up -d --scale app=3
```

Note: You'll need a load balancer (like nginx-proxy or Traefik) to distribute traffic.

## Backups

### MySQL Backup
```bash
docker-compose exec mysql_primary mysqldump -u root -psecret data_core > backup_primary.sql
docker-compose exec mysql_secondary mysqldump -u root -psecret data_core_secondary > backup_secondary.sql
```

### MongoDB Backup
```bash
docker-compose exec mongodb mongodump --db=data_core_entities --out=/tmp/backup
docker cp data-core-mongodb:/tmp/backup ./mongodb_backup
```

### Restore MySQL
```bash
docker-compose exec -T mysql_primary mysql -u root -psecret data_core < backup_primary.sql
```

### Restore MongoDB
```bash
docker cp ./mongodb_backup data-core-mongodb:/tmp/backup
docker-compose exec mongodb mongorestore --db=data_core_entities /tmp/backup/data_core_entities
```

## Networking

All containers are on the same network: `data-core-network`

To connect external services:
```yaml
networks:
  data-core-network:
    external: true
```

## Volume Management

### List Volumes
```bash
docker volume ls | grep data-core
```

### Inspect Volume
```bash
docker volume inspect data-core_mysql_primary_data
```

### Remove Specific Volume (⚠️ Destroys Data)
```bash
docker volume rm data-core_mysql_primary_data
```

## Troubleshooting

### Container Won't Start
```bash
docker-compose logs app
docker-compose ps
```

### Permission Issues
```bash
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chmod -R 755 /var/www/html/storage
```

### Database Connection Refused
- Ensure database containers are running: `docker-compose ps`
- Check network connectivity: `docker-compose exec app ping mysql_primary`
- Verify credentials in environment variables

### MongoDB Connection Issues
```bash
# Check MongoDB is accessible
docker-compose exec app ping mongodb

# Verify MongoDB is running
docker-compose exec mongodb mongosh --eval "db.adminCommand('ping')"
```

### Application Shows Errors
```bash
# Clear caches
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
```

### Rebuild Everything
```bash
docker-compose down -v
docker-compose build --no-cache
docker-compose up -d
docker-compose exec app php artisan migrate
```

## Security Considerations

1. **Change Default Passwords**: Update MySQL passwords in `docker-compose.yml`
2. **Use Secrets**: Store sensitive data in Docker secrets (Swarm mode)
3. **Network Isolation**: Use internal networks for database containers
4. **SSL/TLS**: Configure Nginx with SSL certificates for production
5. **Firewall**: Restrict exposed ports in production

## Monitoring

### Check Resource Usage
```bash
docker stats
```

### View Real-time Logs
```bash
docker-compose logs -f
```

### Health Checks
Access: http://localhost:8000/up

## CI/CD Integration

Example GitHub Actions workflow:
```yaml
- name: Build Docker images
  run: docker-compose build

- name: Run tests
  run: |
    docker-compose up -d
    docker-compose exec -T app php artisan test
    docker-compose down
```

## Additional Resources

- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [MySQL Docker Image](https://hub.docker.com/_/mysql)
- [MongoDB Docker Image](https://hub.docker.com/_/mongo)
- [PHP Docker Image](https://hub.docker.com/_/php)
