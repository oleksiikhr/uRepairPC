FROM php:7.4-fpm

USER root

RUN groupmod -o -g 1000 www-data && \
    usermod -o -u 1000 -g www-data www-data

WORKDIR /var/www

CMD ["php-fpm"]

EXPOSE 9000
