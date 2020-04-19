FROM php:7.4-fpm

USER root

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git libzip-dev zip

RUN pecl install redis

RUN groupmod -o -g 1000 www-data && \
    usermod -o -u 1000 -g www-data www-data

RUN apt-get update && \
    apt-get install libcurl4-openssl-dev

RUN docker-php-ext-install mysqli && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-install curl && \
    docker-php-ext-install zip && \
    docker-php-ext-enable redis

RUN curl -sSL https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# TODO production
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www

CMD ["php-fpm"]

EXPOSE 9000
