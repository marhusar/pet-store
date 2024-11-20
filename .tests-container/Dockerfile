FROM php:8.2-cli

# Install necessary dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    && docker-php-ext-install opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHPUnit globally
RUN composer global require phpunit/phpunit:^10

# Install Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Configure Xdebug for PHP-FPM and CLI
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Copy custom php.ini for PHP CLI
COPY ./php-cli.ini /usr/local/etc/php/cli-php.ini

WORKDIR /var/www/html
