FROM php:8.3-apache

# Install system dependencies + PHP extensions - ADDED pdo_pgsql
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

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Enable Apache mod_rewrite for Laravel routes
RUN a2enmod rewrite

# Point Apache DocumentRoot to /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Enable .htaccess in /public
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Bind to Render $PORT at runtime
CMD sh -c 'echo "=== RENDER PORT IS $PORT ===" && sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf && sed -i "s/:80/:$PORT/g" /etc/apache2/sites-available/000-default.conf && exec apache2-foreground'
