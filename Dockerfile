FROM php:8.3-apache

# Install system dependencies + PHP extensions for Laravel + PostgreSQL
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy Laravel project
COPY . /var/www/html

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions for Laravel storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Enable Apache mod_rewrite for Laravel routes
RUN a2enmod rewrite

# Point Apache DocumentRoot to /public - FIXES 403 FORBIDDEN
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Enable .htaccess in /public directory
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Bind to Render $PORT + RUN MIGRATIONS WITHOUT CRASHING
CMD sh -c 'php artisan migrate --force || echo "Migration failed but container continues..." && echo "=== RENDER PORT IS $PORT ===" && sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/
