# FROM php:8.2-fpm

# RUN apt-get update && apt-get install -y \
#     git \
#     curl \
#     zip \
#     unzip \
#     libpng-dev \
#     libonig-dev \
#     libxml2-dev \
#     nodejs \
#     npm

# RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# RUN pecl install redis && docker-php-ext-enable redis

# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# WORKDIR /var/www
FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libonig-dev libxml2-dev

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

EXPOSE 10000

CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000