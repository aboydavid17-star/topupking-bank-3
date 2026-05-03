FROM php:8.3-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    libzip-dev libpng-dev libonig-dev libxml2-dev libpq-dev zip unzip git
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath zip
    

    

    

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN cp .env.example .env
RUN php artisan key:generate --force

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=$PORT
