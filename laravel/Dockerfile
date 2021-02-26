FROM php:7.1.3-fpm

# Move files over
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

RUN apt-get update && apt-get install -y libmcrypt-dev git zip unzip apt-utils\
    mysql-client libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
    && docker-php-ext-enable imagick\
    && docker-php-ext-install mcrypt pdo_mysql mbstring dom zip

RUN curl -sL https://deb.nodesource.com/setup_10.x | bash -\
    && apt-get install -y nodejs

# Add user for laravel application
RUN groupadd -g 1000 homestead
RUN useradd -u 1000 -ms /bin/bash -g homestead homestead

# You're awesome. Just own it
COPY . /var/www
RUN chown -R homestead:homestead /var/www

# Set up composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=1.10.16
RUN composer install

COPY resources/js/config.example.js resources/js/config.js

# Do our dirty work
USER homestead
RUN npm install && npm run build


# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
