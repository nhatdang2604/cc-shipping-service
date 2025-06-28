FROM php:7.4.0-fpm

RUN apt-get update && \ 
    apt-get install -y zip unzip libxml2-dev

RUN docker-php-ext-install \ 
    bcmath \
    dom xml xmlwriter

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

