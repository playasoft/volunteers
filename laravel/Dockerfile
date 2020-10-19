FROM php:7.1.3-fpm

# Move files over
COPY .env composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

RUN apt-get update && apt-get install -y libmcrypt-dev git zip unzip apt-utils\
    mysql-client libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
    && docker-php-ext-enable imagick\
    && docker-php-ext-install mcrypt pdo_mysql mbstring dom zip

RUN curl -sL https://deb.nodesource.com/setup_10.x | bash -\
    && apt-get install -y nodejs

ARG USERNAME

# Add user for laravel application
RUN groupadd -g 1000 $USERNAME
RUN useradd -u 1000 -ms /bin/bash -g $USERNAME $USERNAME

# You're awesome. Just own it
COPY --chown=$USERNAME:$USERNAME . /var/www

# Set up composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

# Make sure we have access
RUN mkdir -p /usr/lib/node_modules
RUN chown -R $USERNAME:$USERNAME /usr/lib/node_modules
COPY resources/js/config.example.js resources/js/config.js

# Do our dirty work
RUN npm install
RUN npm run build

USER $USERNAME

# Seed the database with user roles
RUN php artisan db:seed
RUN export APP_KEY="$(php artisan key:generate | sed -r -n 's/.*\[base64:(.*)\].*/\1/p')"
RUN php artisan config:cache

# RUN php artisan migrate

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
