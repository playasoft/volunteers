FROM php:7.2-fpm

# Move files over
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    python \
    nodejs

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install npm
# This ships with npm v. 5.6 or so, and that doesn't play well with the node version in the repos
RUN curl -sS https://www.npmjs.com/install.sh | sh

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-install gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 nginx
RUN useradd -u 1000 -ms /bin/bash -g nginx nginx

# Change owner for the directory (for nginx)
COPY --chown=nginx:nginx . /var/www

# Run composer
RUN composer install

# Set up our /secrets
RUN mkdir -p /var/www/secrets
RUN sed -n 's/DB_HOST=//p' /var/www/.env > /var/www/secrets/host
RUN sed -n 's/DB_DATABASE=//p' /var/www/.env > /var/www/secrets/database
RUN sed -n 's/DB_USERNAME=//p' /var/www/.env > /var/www/secrets/user
RUN sed -n 's/DB_PASSWORD=//p' /var/www/.env > /var/www/secrets/dbpass

RUN npm install

RUN php artisan key:generate
RUN php artisan config:cache

# May want this tunable
COPY resources/js/config.example.js resources/js/config.js
# This currently fails
#RUN npm run build

# Seed the database with user roles
RUN php artisan db:seed

# Change current user
USER nginx

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
