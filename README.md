# Volunteer Database

A volunteer scheduling system for festivals and community events, originally built for [Apogaea](https://www.apogaea.com/) and now maintained by [DenverBurners](https://volunteer.denverburners.org).

## What It Does

This platform helps event organizers coordinate volunteer shifts across departments, manage role-based access, and track training certifications. It's designed for events that need to staff everything from greeters and gate ticket checkers to certified positions like emergency medical response, fire safety, and conflict de-escalation.

**Events and Departments** — Organizers create events and break them into departments (e.g. Gate, Medical, Fire, Rangers). Each department can have its own set of shifts across the event's schedule.

**Shifts and Slots** — Shifts define the work that needs doing: what time, how long, and how many volunteers are needed. Each shift is broken into individual slots that volunteers can sign up for.

**Roles and Permissions** — Every shift can be gated by permission level to ensure volunteers have the right training or certification. The built-in roles include admin, volunteer, medical, fire, ranger, ranger-khaki, event-admin, department-lead, photography, and board-member.

**Training Verification** — Volunteers can upload certification documents (CPR cards, fire training certificates, etc.) through their profile. Administrators review and approve uploads before granting the corresponding role, ensuring nobody signs up for a shift they aren't qualified for.

**Password-Protected Shifts** — Shifts can also be gated by password, giving department leads another way to control access to sensitive positions.

## Quick Start

Requirements: [Docker](https://docs.docker.com/engine/install/) and [Docker Compose](https://docs.docker.com/compose/install/).

```sh
git clone https://github.com/playasoft/volunteers.git
cd volunteers

# Create your environment config
cp .env.example .env

# Set UID/GID to match your host user (required for file permissions)
sed -i "s/^UID=.*/UID=$(id -u)/" .env && sed -i "s/^GID=.*/GID=$(id -g)/" .env

# Set a real database password in .env
# Edit DB_PASSWORD, SITE_NAME, SITE_URL, and any other values for your environment

# Build and start containers
docker compose build
docker compose up -d

# Install dependencies
docker compose exec app composer install

# Set up the application
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed

# Build frontend assets
docker compose exec app cp resources/js/config.example.js resources/js/config.js
docker compose exec app npm install
docker compose exec app npm run build
```

The site will be available at `http://localhost` (or whatever `NGINX_PORT` you configured in `.env`).

## Configuration

All configuration is done through the `.env` file. Key settings:

| Variable | Purpose |
|---|---|
| `SITE_NAME` | Displayed on the home page and in emails |
| `SITE_DESCRIPTION` | HTML description shown on the home page |
| `SITE_URL` | Used in email links sent to volunteers |
| `NGINX_PORT` | Host port for the web server (default: 80) |
| `DB_PORT` | Host port for MariaDB (default: 3306) |
| `DB_PASSWORD` | Database password — change this from the default |
| `UID` / `GID` | Must match your host user for file permissions |

## Docker Environment

The project runs three containers:

| Container | Image | Purpose |
|---|---|---|
| voldb-app | PHP 7.4-FPM + Node 16 | Runs the Laravel application |
| voldb-nginx | nginx:alpine | Serves web requests, proxies PHP to the app container |
| voldb-db | MariaDB 10.11 | Database |

Common commands:

```sh
# View logs
docker compose logs -f

# Run artisan commands
docker compose exec app php artisan [command]

# Access the database
docker compose exec db mysql -u root -p

# Rebuild after Dockerfile changes
docker compose build

# Stop everything
docker compose down

# Stop and wipe all data (database, vendor, node_modules)
docker compose down -v
```

## Websockets (Optional)

For real-time updates when shifts are taken or departments change:

1. Install and configure `redis` as the broadcast driver in `.env`
2. Set `WEBSOCKETS_ENABLED=true` in `.env`
3. Run `npm install` in the `node/` directory
4. Run `node websocket-server.js` in the `node/` directory (use `pm2` or `screen` to keep it running)

## Further Documentation

- [Writing Tests and Factories](docs/testing.md)

## License

[AGPL-3.0](LICENSE)