#!/usr/bin/env bash

set -e

[ -f ".env.testing" ] || (echo Please make an .env.testing file and run: php artisan key:generate --env=testing; exit 1)
source .env.testing
echo Starting services
docker-compose up -d
echo Host: 127.0.0.1
until docker-compose exec database mysql -h database -u $DB_USERNAME -p$DB_PASSWORD -D $DB_DATABASE --silent -e "show databases;"
do
  echo "Waiting for database connection..."
  sleep 5
done
echo Installing dependencies
./scripts/npm.sh install
./scripts/npm.sh run build
./scripts/composer.sh install
echo Seeding database
rm -f bootstrap/cache/*.php
docker-compose exec app php artisan migrate --env=testing && echo Database migrated
docker-compose exec app php artisan db:seed --env=testing && echo Database seeded
docker-compose exec app php artisan key:generate --env=testing
