FROM php:8.4-fpm

RUN apt-get update && apt-get install -y libpq-dev libzip-dev unzip \
    && docker-php-ext-install pdo pdo_mysql

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www
