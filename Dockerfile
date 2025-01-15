# Use the official PHP 8.3 image from Docker Hub
FROM php:8.3-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    libicu-dev \
    zlib1g-dev \
    libxml2-dev \
    && docker-php-ext-install intl pdo pdo_pgsql xml opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory inside the container
WORKDIR /var/www/html

# Copy the Symfony project files into the container
COPY . .

# Set the permissions for Symfony to work correctly
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 775 /var/www/html/var

# Install dependencies (Symfony and other PHP packages)
RUN composer install --prefer-dist --no-progress --no-suggest --optimize-autoloader --no-interaction

# Expose the PHP-FPM port
EXPOSE 9000

CMD ["php-fpm"]
