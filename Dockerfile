FROM php:8.4-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    icu-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    mysql-client \
    && docker-php-ext-install \
    pdo_mysql \
    intl \
    zip \
    opcache

# Set working directory
WORKDIR /var/www

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Install dependencies if vendor doesn't exist (optional, usually handled via volumes in dev)
# RUN composer install --no-interaction --optimize-autoloader

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
