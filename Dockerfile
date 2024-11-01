FROM php:8.2-fpm
#  FastCGI Process Manager
ARG user
ARG uid
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
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
RUN docker-php-ext-install zip sodium
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# RUN useradd -G www-data,root -u $uid -d /home/$user $user
# RUN mkdir -p /home/$user/.composer && \
#     chown -R $user:$user /home/$user
WORKDIR /var/www
# USER $user


COPY . /var/www
# RUN composer install

# Set permissions for storage and bootstrap/cache directories
RUN chmod -R u+rwX,g+rwX,o+rwX /var/www/storage && \
    chmod -R u+rwX,g+rwX,o+rwX /var/www/bootstrap/cache && \
    chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache



# Copy the entrypoint script
COPY docker-compose/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Use the entrypoint script
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 80

# Use the PORT environment variable for the application
# CMD ["php", "artisan", "serve", "--host", "0.0.0.0", "--port"]
CMD ["php-fpm"]
