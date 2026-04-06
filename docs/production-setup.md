# Production Server Setup (Debian 12)

This guide covers setting up the Volunteer Database on a fresh Debian 12 server. By the end, you'll have the application running behind nginx on port 80 with MariaDB for the database, all managed through Docker.

## 1. Install Dependencies

As root, install git and Docker:

```sh
apt update && apt install -y git curl

# Install Docker using the official convenience script
curl -fsSL https://get.docker.com | sh
```

## 2. Create a Dedicated User

Create a non-root user to own and run the application. This user's UID/GID will be used inside the Docker container for PHP-FPM file permissions.

```sh
adduser voldb --disabled-password --gecos ""
usermod -aG docker voldb
```

Log out and back in (or reboot) for the docker group membership to take effect, then switch to the new user:

```sh
su - voldb
```

Verify Docker access:

```sh
docker ps
```

## 3. Clone and Configure

```sh
git clone https://github.com/playasoft/volunteers.git
cd volunteers

# Create environment config
cp .env.example .env

# Set UID/GID to match this user
sed -i "s/^UID=.*/UID=$(id -u)/" .env && sed -i "s/^GID=.*/GID=$(id -g)/" .env

# Generate a secure database password and set it
DB_PASS=$(openssl rand -hex 24)
sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=$DB_PASS/" .env
echo "Generated DB password: $DB_PASS"
echo "Save this somewhere safe — it won't be shown again."
```

Now edit `.env` to set your site-specific values:

```sh
nano .env
```

Update at minimum:
- `SITE_NAME` — your organization's name
- `SITE_URL` — the public URL (e.g. `http://volunteer.denverburners.org`)
- `NGINX_PORT` — leave as 80 for production, or adjust if behind a reverse proxy

## 4. Build and Start

```sh
docker compose build
docker compose up -d
```

Verify all three containers are running:

```sh
docker compose ps
```

## 5. Install and Set Up the Application

```sh
# Install PHP dependencies
docker compose exec app composer install

# Generate application key
docker compose exec app php artisan key:generate

# Run database migrations
docker compose exec app php artisan migrate

# Seed initial roles
docker compose exec app php artisan db:seed

# Build frontend assets
docker compose exec app cp resources/js/config.example.js resources/js/config.js
docker compose exec app npm install
docker compose exec app npm run build
```

## 6. Verify

The site should now be accessible on port 80 (or whatever `NGINX_PORT` you configured):

```sh
curl -I http://localhost
```

You should see a `200 OK` response. Visit the site in a browser to confirm everything is working, then register your first admin account.

## 7. Set Up HTTPS with Let's Encrypt

Once your domain's DNS is pointing at the server, you can enable HTTPS. The project uses a Docker Compose override (`docker-compose.production.yml`) to layer HTTPS on top of the base development config, keeping the two environments cleanly separated.

### Install Certbot

As root:

```sh
apt install -y certbot
```

### Configure Domains

As voldb, add your domain(s) to `.env`. Multiple domains are supported with a single certificate (SAN):

```sh
cd ~/volunteers
nano .env
```

Set the `SSL_DOMAINS` variable (comma-separated, first domain is primary):

```
SSL_DOMAINS=denverburners.playa.software
```

Or for multiple domains:

```
SSL_DOMAINS=denverburners.playa.software,volunteer.denverburners.org
```

Also update `SITE_URL` to use HTTPS:

```
SITE_URL=https://denverburners.playa.software
```

### Issue Certificate and Enable HTTPS

The included helper script reads `SSL_DOMAINS` from `.env`, issues the certificate, generates the nginx production config, and starts the containers with HTTPS:

```sh
sudo ./scripts/ssl-setup.sh
```

This runs two steps: `issue` (gets the cert from Let's Encrypt) and `configure` (generates nginx config and starts the production containers). You can also run them individually:

```sh
sudo ./scripts/ssl-setup.sh issue       # Issue/reissue the certificate
./scripts/ssl-setup.sh configure        # Generate nginx config and restart containers
./scripts/ssl-setup.sh status           # Show certificate details and expiry
./scripts/ssl-setup.sh help             # Show all commands
```

### Adding or Changing Domains

Update `SSL_DOMAINS` in `.env`, then reissue and reconfigure:

```sh
sudo ./scripts/ssl-setup.sh issue
./scripts/ssl-setup.sh configure
```

### Automatic Certificate Renewal

As root, set up a cron job for automatic renewal:

```sh
crontab -e
```

Add:

```
0 3 * * * /home/voldb/volunteers/scripts/ssl-setup.sh renew >> /var/log/certbot-renewal.log 2>&1
```

The renew command only stops/starts containers if a renewal is actually needed.

## Maintenance

**View logs:**
```sh
cd ~/volunteers
docker compose -f docker-compose.yml -f docker-compose.production.yml logs -f
```

**Restart after server reboot:**
The containers are set to `restart: unless-stopped`, so they will come back automatically after a reboot. If they don't:

```sh
cd ~/volunteers
docker compose -f docker-compose.yml -f docker-compose.production.yml up -d
```

**Pull updates:**
```sh
cd ~/volunteers
git pull
docker compose build
docker compose -f docker-compose.yml -f docker-compose.production.yml up -d
docker compose exec app composer install
docker compose exec app php artisan migrate
docker compose exec app npm run build
```

## Next Steps

- [Import legacy data from previous events](https://github.com/playasoft/volunteers/issues) (TODO)