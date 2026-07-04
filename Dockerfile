
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libonig-dev libxml2-dev \
    libpq-dev

RUN docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd

WORKDIR /var/www

COPY . .

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

CMD sh -c 'php artisan optimize:clear && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}'