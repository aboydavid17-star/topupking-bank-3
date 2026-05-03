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

RUN echo '#!/bin/sh' > /start.sh && \
    echo 'echo "=== RENDER STARTING ON PORT $PORT ==="' >> /start.sh && \
    echo 'sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf' >> /start.sh && \
    echo 'sed -i "s/:80/:$PORT/g" /etc/apache2/sites-available/000-default.conf' >> /start.sh && \
    echo 'apache2-foreground' >> /start.sh && \
    chmod +x /start.sh

CMD /start.sh
