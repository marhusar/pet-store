# Use PHP 8.1 FPM as the base image
FROM php:8.1-fpm

# Install Git and dependencies needed for Composer
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    curl \
    && docker-php-ext-install opcache && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory (optional, but useful)
WORKDIR /var/www/html

# Default command to run PHP-FPM
CMD ["php-fpm"]
