FROM php:8.3-fpm
# FastCGI Process Manager

ARG user
ARG uid

# Install system dependencies
RUN apt update && apt install -y \
    coreutils \
    libzip-dev \
    libsodium-dev \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev

RUN apt clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip sodium

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy application code
COPY . /var/www

# Set permissions
RUN chmod -R u+rwX,g+rwX,o+rwX /var/www/storage \
    && chmod -R u+rwX,g+rwX,o+rwX /var/www/bootstrap/cache \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copy entrypoint script
COPY docker-compose/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

# Use the entrypoint script
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Start the PHP FastCGI Process Manager
CMD ["php-fpm"]
