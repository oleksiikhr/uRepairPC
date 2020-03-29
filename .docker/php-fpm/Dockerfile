FROM php:fpm-alpine

RUN apk update

RUN apk add curl mcrypt

RUN usermod -u 1000 www-data

CMD ["php-fpm"]

EXPOSE 9000
