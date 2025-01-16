# Use the official PHP 8.3 image from Docker Hub
FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    libicu-dev \
    zlib1g-dev \
    libxml2-dev \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    intl \
    opcache \
    pdo \
    pdo_pgsql \
    xml \
    zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first to leverage Docker cache
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --prefer-dist --no-scripts --no-progress --no-interaction

# Copy the rest of the application
COPY . .

# Create var directory and set permissions
RUN mkdir -p var && \
    chown -R www-data:www-data var && \
    chmod -R 775 var

EXPOSE 9000

CMD ["php-fpm"]
