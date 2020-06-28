FROM nginx:1.19.0-alpine

RUN set -x ; \
    addgroup -g 82 -S www-data ; \
    adduser -u 82 -D -S -G www-data www-data && exit 0 ; exit 1

WORKDIR /var/www

CMD ["nginx"]

EXPOSE 80 443
