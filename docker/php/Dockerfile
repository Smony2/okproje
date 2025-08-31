FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
        git \
        curl \
        libpng libpng-dev \
        libjpeg-turbo-dev \
        libwebp-dev \
        libzip-dev \
        oniguruma-dev \
        icu-dev \
        bash \
        shadow \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd intl \
    && rm -rf /var/cache/apk/*

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Optimize composer for dev containers
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_VENDOR_DIR=/var/www/html/vendor

# Pre-copy composer files for better layer caching
COPY composer.json composer.lock* ./
RUN composer install --no-scripts --no-progress --prefer-dist || true

# Copy project (mounted in compose, but useful for image builds)
COPY . .

RUN php -r "file_exists('.env') || copy('.env.example', '.env');" \
    && php artisan key:generate --ansi || true

CMD ["php-fpm"]


