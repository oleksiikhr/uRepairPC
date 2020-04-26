FROM php:7.4-fpm-alpine

RUN apk add shadow && usermod -u 1000 www-data && groupmod -g 1000 www-data

RUN apk add supervisor autoconf build-base git libzip-dev zip curl-dev

RUN pecl install -o -f redis

RUN docker-php-ext-install mysqli && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-install curl && \
    docker-php-ext-install zip && \
    docker-php-ext-install sockets && \
    docker-php-ext-enable redis

COPY supervisord.conf /etc/supervisord.conf

ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisord.conf"]

WORKDIR /etc/supervisor/conf.d/
