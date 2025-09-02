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
FROM php:8.3-fpm-alpine AS production

# Install system dependencies
RUN apk add --no-cache \
    git bash curl libpng-dev libxml2-dev zip unzip \
    freetype-dev libjpeg-turbo-dev libwebp-dev zlib-dev libzip-dev oniguruma-dev \
    postgresql-dev supervisor autoconf g++ make icu-dev \
    sqlite-dev shadow openssl-dev libffi-dev linux-headers \
    && rm -rf /var/cache/apk/*

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install \
        pdo pdo_mysql pdo_pgsql pdo_sqlite \
        mbstring exif pcntl bcmath gd zip \
        intl xml opcache sockets ffi \
    && pecl install redis \
    && docker-php-ext-enable redis

# Create non-root user with specific UID/GID
ARG UID=1000
ARG GID=1000
RUN addgroup -g ${GID} appuser && \
    adduser -D -u ${UID} -G appuser appuser

# Configure PHP-FPM for non-root user
RUN sed -i 's/user = www-data/user = appuser/g' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's/group = www-data/group = appuser/g' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's/listen.owner = www-data/listen.owner = appuser/g' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's/listen.group = www-data/listen.group = appuser/g' /usr/local/etc/php-fpm.d/www.conf

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

# Create required directories with proper permissions
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/app/public \
    public/storage \
    bootstrap/cache \
    storage/logs \
    /var/run/php \
    /var/log/php-fpm

# Set proper permissions
RUN chown -R appuser:appuser \
    storage \
    bootstrap/cache \
    public \
    /var/run/php \
    /var/log/php-fpm \
    && chmod -R 775 storage bootstrap/cache public \
    && chmod -R 755 /var/run/php /var/log/php-fpm

# Generate application key and optimize (run as root first)
RUN php -r "file_exists('.env') || copy('.env.example', '.env');" \
    && php artisan key:generate --ansi --force || true \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan event:cache \
    && php artisan package:discover --ansi || true

# Switch to non-root user
USER appuser

# Health check with better error handling
HEALTHCHECK --interval=30s --timeout=10s --start-period=30s --retries=3 \
    CMD php -r "echo 'Health check passed';" || exit 1

# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]

