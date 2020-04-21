FROM php:7.4-fpm-alpine

RUN apk add supervisor autoconf build-base

RUN pecl install redis && docker-php-ext-enable redis

RUN addgroup -g 1000 urepairpc && \
    adduser -D -G urepairpc -u 1000 urepairpc

COPY supervisord.conf /etc/supervisord.conf

ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisord.conf"]

WORKDIR /etc/supervisor/conf.d/
