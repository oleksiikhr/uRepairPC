FROM php:7.4-alpine

RUN apk add shadow && usermod -u 1000 www-data && groupmod -g 1000 www-data

RUN apk add git autoconf libzip-dev zip build-base curl-dev

RUN pecl install -o -f redis

RUN docker-php-ext-install mysqli && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-install curl && \
    docker-php-ext-install zip && \
    docker-php-ext-install sockets && \
    docker-php-ext-enable redis

RUN curl -sSL https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www

COPY . .

RUN composer install

RUN php artisan test
