FROM dunglas/frankenphp:php8.2

ENV SERVER_NAME=":80"

WORKDIR /app

COPY . .

RUN apt update && apt install -y \
    zip unzip git curl libzip-dev libonig-dev \
    && docker-php-ext-install zip pdo_mysql mbstring bcmath

COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

COPY composer.* ./
RUN composer install

COPY package*.json ./
RUN npm install

COPY . .
# RUN npm run dev

EXPOSE 80