FROM php:8.1-fpm-alpine

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN docker-php-ext-install pdo pdo_mysql mysqli

RUN apk add icu-dev
RUN docker-php-ext-configure intl && docker-php-ext-install intl


RUN apk add --no-cache mysql-client msmtp perl wget procps shadow libzip libpng libjpeg-turbo libwebp freetype icu

RUN apk add --no-cache --virtual build-essentials \
    icu-dev icu-libs zlib-dev g++ make automake autoconf libzip-dev \
    libpng-dev libwebp-dev libjpeg-turbo-dev freetype-dev && \
    docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install gd && \
    docker-php-ext-install zip

RUN apk add imagemagick
RUN apk add imagemagick-dev

RUN apk add --update --no-cache autoconf g++ imagemagick-dev libtool make pcre-dev curl-dev \
    && pecl install imagick \
    && docker-php-ext-enable imagick

RUN docker-php-ext-install bcmath

# Copy the php.ini file
COPY php.ini /usr/local/etc/php/
