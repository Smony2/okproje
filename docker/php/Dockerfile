FROM php:8.3-fpm-alpine

# Install system dependencies
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
        freetype-dev \
        libxml2-dev \
        sqlite-dev \
        postgresql-dev \
        autoconf \
        g++ \
        make \
        linux-headers \
    && docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype \
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
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Set working directory
WORKDIR /var/www/html

# Set user variables
ARG uid=1000
ARG user=www

# Create www-data user with proper permissions
RUN addgroup -g 1000 www && \
    adduser -u 1000 -G www -s /bin/sh -D www

# Add user to additional groups and create home directory
RUN usermod -a -G www-data,root www
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set proper permissions for web directory
RUN chmod 755 /var/www/html

# Copy composer files first for better caching
COPY composer.json composer.lock* ./

# Create vendor directory and set permissions
RUN mkdir -p /var/www/html/vendor && \
    chown -R $user:$user /var/www/html

# Switch to www user for composer operations
USER $user

# Install PHP dependencies
RUN composer install --no-scripts --no-progress --prefer-dist --optimize-autoloader

# Switch back to root for file operations
USER root

# Copy remaining application files
COPY . /var/www/html

# Set proper ownership
RUN chown -R $user:$user /var/www/html

# Create necessary directories and set permissions
RUN mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/bootstrap/cache \
    && chown -R $user:$user /var/www/html/storage \
    && chown -R $user:$user /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Switch back to www user
USER $user

# Generate application key if .env doesn't exist
RUN php -r "file_exists('.env') || copy('.env.example', '.env');" \
    && php artisan key:generate --ansi || true

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]