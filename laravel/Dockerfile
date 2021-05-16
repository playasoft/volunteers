FROM php:7.4-fpm-alpine

# Move files over
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

RUN apk add --no-cache \
	git \
	bash \
	nodejs \
	npm \
	freetype \
	libjpeg-turbo \
	libpng \
	freetype-dev \
	libjpeg-turbo-dev \
	libpng-dev \
	&& docker-php-ext-configure gd \
		--with-freetype=/usr/include \
		--with-jpeg=/usr/include \
	&& docker-php-ext-install -j$(nproc) gd \
	&& docker-php-ext-enable gd \
	&& apk del --no-cache \
		freetype-dev \
		libjpeg-turbo-dev \
		libpng-dev \
	&& rm -rf /tmp/*

RUN apk add libzip-dev \
	&& docker-php-ext-install pdo pdo_mysql zip bcmath 

# Add user for laravel application
RUN addgroup -S -g 1000 homestead && adduser -u 1000 -S -G homestead homestead

# You're awesome. Just own it
COPY --chown=homestead:homestead . /var/www

# Set up composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.0.2
RUN composer update --no-scripts && composer install

COPY resources/js/config.example.js resources/js/config.js
RUN npm install && npm run build

# Do our dirty work
USER homestead

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
