# Laravel Starter Kit - MongoDB Docker

This is a modified version of [Nuno Maduro's Laravel Starter Kit](https://github.com/nunomaduro/laravel-starter-kit) configured to run with Docker Compose and MongoDB.

> **Original Starter Kit**: For the official Laravel Starter Kit with all documentation and features, please visit **[github.com/nunomaduro/laravel-starter-kit](https://github.com/nunomaduro/laravel-starter-kit)**

## About This Version

This repository adapts the ultra-strict, type-safe Laravel Starter Kit to work seamlessly with Docker Compose and MongoDB. It maintains all the rigorous code quality standards of the original while providing a containerized development environment.

### Key Modifications

- **Docker Compose Setup**: Fully containerized environment with Nginx, PHP-FPM, MongoDB, and Redis
- **MongoDB Integration**: Configured with MongoDB Laravel driver instead of traditional SQL databases
- **Optimized Dockerfile**: PHP 8.4 with all necessary extensions, Composer, Node.js 24, and Playwright support

### Docker Stack

This project uses the following Docker images:

- **PHP**: `php:8.4-fpm-bookworm` (custom built with extensions)
- **Nginx**: `nginx:1.29.3-alpine`
- **MongoDB**: `mongo:8.0`
- **Redis**: `redis:8.2-alpine`
- **Node.js**: `24.x` (installed in PHP container)

## Getting Started

### Prerequisites

- Docker and Docker Compose installed on your system
- Git

### Setup

1. Clone this repository:
```bash
git clone https://github.com/ricu23/laravel-starter-kit-mongodb-docker.git
cd laravel-starter-kit-mongodb-docker
```

2. Start the Docker containers:
```bash
docker compose up -d --build
```

That's it! The application is now running at `http://localhost:8080`

### What Happens on Startup

Every time you run `docker compose up`, the entrypoint script automatically executes the following tasks:

- **Installs Composer dependencies** - Ensures all PHP packages are up to date
- **Sets up environment** - Copies `.env.example` to `.env` if it doesn't exist
- **Generates application key** - Creates `APP_KEY` if not already set
- **Runs database migrations** - Automatically migrates your database schema
- **Installs npm dependencies** - Ensures all frontend packages are available
- **Builds frontend assets** - Compiles CSS/JS using Vite

This means your application is always in sync with the latest dependencies and migrations on every startup.

### Running Tests

You can run the test suite in two ways:

**From outside the container:**
```bash
docker compose exec php composer test
```

**From inside the container:**
```bash
composer test
```

## Available Services

- **Web Application**: http://localhost:8080
- **MongoDB**: localhost:27017
- **Redis**: localhost:6379

## Environment Variables

You can customize the following ports in your `.env` file:

- `NGINX_PORT` - Nginx port (default: 8080)
- `MONGODB_PORT` - MongoDB port (default: 27017)
- `REDIS_PORT_EXPOSE` - Redis port (default: 6379)

## Credits

- Original starter kit by **[Nuno Maduro](https://x.com/enunomaduro)**
- Docker and MongoDB modifications by **[Ricu23](https://ricu.dev/)**
- Docker configuration assistance by **Claude Sonnet 4.5**

## License

This project maintains the **[MIT license](https://opensource.org/licenses/MIT)** from the original Laravel Starter Kit.
