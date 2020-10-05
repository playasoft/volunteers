FROM php:7.2-fpm

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set up our /secrets
RUN mkdir /var/secrets
COPY .env /var/secrets/env
RUN sed -n 's/DB_HOST=//p' /var/secrets/env > /var/secrets/host
RUN sed -n 's/DB_DATABASE=//p' /var/secrets/env > /var/secrets/database
RUN sed -n 's/DB_USERNAME=//p' /var/secrets/env > /var/secrets/user
RUN sed -n 's/DB_PASSWORD=//p' /var/secrets/env > /var/secrets/dbpass

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
    curl\
    npm\
    nodejs

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

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

# Set up node
RUN npm install

# Migrate - this requires sql up and running, maybe move for testing?
#RUN php artisan migrate
RUN php artisan key:generate
RUN php artisan config:cache

# May want this tunable
COPY resources/js/config.example.js resources/js/config.js
RUN npm run build

# Seed the database with user roles
RUN php artisan db:seed

# Change current user
USER nginx

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
