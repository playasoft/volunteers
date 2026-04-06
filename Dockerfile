FROM php:7.4-fpm

# Match www-data UID/GID to the host user
# so PHP-FPM workers can read/write mounted volumes
ARG UID
ARG GID
RUN usermod -u $UID www-data && groupmod -g $GID www-data

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libzip-dev \
    libonig-dev \
    libmagickwand-dev \
    zip \
    unzip \
    --no-install-recommends

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring bcmath zip \
 && pecl install imagick \
 && docker-php-ext-enable imagick

# Clear apt cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Node.js 16 (needed for webpack asset builds)
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash - \
 && apt-get install -y nodejs \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Custom PHP configuration
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# Get latest Composer 2.x
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www