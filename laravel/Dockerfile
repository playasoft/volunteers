FROM php:7.1.3-fpm

# Move files over
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

RUN apt-get update && apt-get install -y libmcrypt-dev git zip\
    mysql-client libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install mcrypt pdo_mysql mbstring dom

RUN curl -sL https://deb.nodesource.com/setup_8.x | bash -\
    && apt-get install -y nodejs

# Add user for laravel application
RUN groupadd -g 1000 nginx
RUN useradd -u 1000 -ms /bin/bash -g nginx nginx

# Change owner for the directory (for nginx)
COPY --chown=nginx:nginx . /var/www

RUN mkdir -p /usr/lib/node_modules
RUN chown -R nginx:nginx /usr/lib/node_modules

# Set up composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

# Make sure we have access
RUN mkdir -p /usr/lib/node_modules
RUN chown -R nginx:nginx /usr/lib/node_modules
COPY resources/js/config.example.js resources/js/config.js

# Set up our /secrets
RUN mkdir -p /var/www/secrets
RUN sed -n 's/DB_HOST=//p' /var/www/.env > /var/www/secrets/host
RUN sed -n 's/DB_DATABASE=//p' /var/www/.env > /var/www/secrets/database
RUN sed -n 's/DB_USERNAME=//p' /var/www/.env > /var/www/secrets/user
RUN sed -n 's/DB_PASSWORD=//p' /var/www/.env > /var/www/secrets/dbpass

# DB
#RUN php artisan migrate
RUN php artisan key:generate
RUN php artisan config:cache

# Seed the database with user roles
RUN php artisan db:seed

# Do our dirty work
RUN npm install

# node-sass throws errors from the package.json, unsure what issues are causing this at this time
RUN npm install -g node-sass@latest --unsafe-perm=true

# Run as nginx tho 
USER nginx

RUN php artisan migrate
RUN npm run build

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
