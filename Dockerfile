FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . /var/www/html

RUN composer install --no-dev --optimize-autoloader
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN a2enmod rewrite

# Fix 1: Point Apache to Laravel /public folder - fixes 403 error
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Fix 2: Allow .htaccess in /public for Laravel routing
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Fix 3: Bind to Render's $PORT at runtime - kills status 128
CMD sh -c 'echo "=== RENDER PORT IS $PORT ===" && sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf && sed -i "s/:80/:$PORT/g" /etc/apache2/sites-available/000-default.conf && exec apache2-foreground'
