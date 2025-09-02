# Multi-stage build for Laravel application
# Stage 1: Composer dependencies
FROM composer:2.6 AS composer

WORKDIR /app

# Copy composer files and install dependencies
COPY composer.json composer.lock* ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader \
    --classmap-authoritative

# Stage 2: Application image
FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git bash curl libpng-dev libxml2-dev zip unzip \
    freetype-dev libjpeg-turbo-dev libwebp-dev zlib-dev libzip-dev oniguruma-dev \
    postgresql-dev supervisor autoconf g++ make icu-dev \
    sqlite-dev shadow openssl-dev libffi-dev linux-headers

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install \
        pdo pdo_mysql pdo_pgsql pdo_sqlite \
        mbstring exif pcntl bcmath gd zip \
        intl xml opcache sockets ffi

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Create user
RUN addgroup -g 1000 appuser && \
    adduser -D -u 1000 -G appuser appuser

# Configure PHP-FPM
RUN sed -i 's/user = www-data/user = appuser/g' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's/group = www-data/group = appuser/g' /usr/local/etc/php-fpm.d/www.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application files
COPY --chown=appuser:appuser . .

# Copy vendor from composer stage
COPY --from=composer --chown=appuser:appuser /app/vendor /var/www/html/vendor

# Assets will be built by the node service in docker-compose

# Copy configuration files
COPY --chown=appuser:appuser docker/php/local.ini /usr/local/etc/php/conf.d/custom.ini

# Create required directories
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/app/public \
    public/storage \
    bootstrap/cache \
    storage/logs

# Set permissions
RUN chown -R appuser:appuser \
    storage \
    bootstrap/cache \
    public \
    && chmod -R 775 storage bootstrap/cache public

# Generate application key and optimize
RUN php -r "file_exists('.env') || copy('.env.example', '.env');" \
    && php artisan key:generate --ansi --force || true \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan event:cache \
    && php artisan package:discover --ansi || true

# Switch to non-root user
USER appuser

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD php artisan tinker --execute="echo 'OK';" || exit 1

# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]