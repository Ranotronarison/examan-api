# Examan API

A Symfony API Platform application for exam management.

## Prerequisites

- Docker
- Docker Compose

## Quick Start

1. **Start the application**
   ```bash
   cd docker
   docker-compose up -d
   ```

2. **Setup database and JWT keys**
   ```bash
   docker-compose exec examan-api php bin/console lexik:jwt:generate-keypair
   docker-compose exec examan-api php bin/console doctrine:migrations:migrate --no-interaction
   docker-compose exec examan-api php bin/console doctrine:fixtures:load --no-interaction
   ```

3. **Access the API**
   - API Documentation: http://localhost:8080/api
   - API Endpoints: http://localhost:8080/api/*

## Commands

- **Start**: `docker-compose up -d --wait`
- **Stop**: `docker-compose down`
- **Logs**: `docker-compose logs -f`
- **Console**: `docker-compose exec examan-api php bin/console`

## Testing

- **Run all tests**: `docker-compose exec examan-api vendor/bin/phpunit`
- **Run tests with detailed output**: `docker-compose exec examan-api vendor/bin/phpunit --testdox`

## Environment

- API Port: 8080 (configurable via `API_PORT`)
- Database Port: 3307 (configurable via `DB_PORT`)
- Database: MariaDB 12.0

### Configuration

Create `.env.dev.local` to override environment variables:

```bash
cp .env.dev .env.dev.local
# Edit .env.dev.local with your local configuration
```

## Troubleshooting

- **Database connection issues**: Check if containers are running with `docker-compose ps`
- **View logs**: `docker-compose logs [service-name]`
