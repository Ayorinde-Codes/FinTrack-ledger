#!/bin/sh

# Run Laravel migrations
php artisan migrate --force

# Seed the database
php artisan db:seed --force

# Start the main process
exec "$@"
