# Multi-stage build for Laravel application
# Stage 1: Composer dependencies
FROM composer:2.6 AS composer

WORKDIR /app

# Copy composer files
COPY composer.json composer.lock* ./

# Install dependencies (no dev dependencies for production)
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
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    freetype-dev \
    libxml2-dev \
    sqlite-dev \
    postgresql-dev \
    autoconf \
    g++ \
    make \
    linux-headers \
    shadow \
    bash \
    && docker-php-ext-configure gd \
        --with-jpeg \
        --with-webp \
        --with-freetype \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        pdo_sqlite \
        mbstring \
        zip \
        gd \
        intl \
        xml \
        bcmath \
        opcache \
        sockets \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && rm -rf /var/cache/apk/*

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/local/bin/composer

# Create system user
RUN addgroup -g 1000 www && \
    adduser -u 1000 -G www -s /bin/sh -D www

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY --chown=www:www . /var/www/html

# Copy vendor from composer stage
COPY --from=composer --chown=www:www /app/vendor /var/www/html/vendor

# Create necessary directories
RUN mkdir -p /var/www/html/storage/logs \
    /var/www/html/storage/framework/cache \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/bootstrap/cache

# Set proper permissions
RUN chown -R www:www /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage \
    /var/www/html/bootstrap/cache

# Generate application key if needed
RUN php -r "file_exists('.env') || copy('.env.example', '.env');" \
    && php artisan key:generate --ansi --force || true

# Optimize Laravel for production
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan event:cache || true

# Switch to non-root user
USER www

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD php artisan tinker --execute="echo 'OK';" || exit 1

# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]