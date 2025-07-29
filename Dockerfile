# FROM dunglas/frankenphp:php8.2
FROM php:8.2-fpm

ENV SERVER_NAME=":81"

COPY . .

RUN apt update && apt install -y \
    git zip unzip curl libpng-dev libzip-dev libonig-dev libxml2-dev \
    npm nodejs nginx supervisor \
    && docker-php-ext-install zip pdo_mysql mbstring zip exif pcntl

WORKDIR /var/www/product-management

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader \
&& npm install && npm run build

# EXPOSE 80
EXPOSE 9000
CMD ["php-fpm"]
