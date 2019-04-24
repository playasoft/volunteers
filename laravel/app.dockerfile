# Base php-fpm target
FROM php:7.2.2-fpm as phpfpm
RUN apt-get update && apt-get install -y mysql-client --no-install-recommends \
 && docker-php-ext-install pdo_mysql
WORKDIR /var/www

# Fetch php dependencies
# - generates vendor folder
FROM composer as phpbuild
COPY composer.* /app/
COPY database /app/database
COPY tests /app/tests
RUN composer install --no-scripts

# Fetch node dependencies
# - generates node_modules
FROM node:8 as nodebuild
COPY package*.json /usr/src/app/
WORKDIR /usr/src/app
RUN npm install

# Copy in all the built components
FROM phpfpm
COPY --from=phpbuild /app/vendor /var/www/vendor
COPY --from=nodebuild /usr/src/app/node_modules /var/www/node_modules
COPY . /var/www/
RUN chown -R www-data:www-data \
        /var/www/storage \
        /var/www/bootstrap/cache
