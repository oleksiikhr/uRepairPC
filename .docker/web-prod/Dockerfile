FROM node:14.4-alpine as build-stage
WORKDIR /usr/app
RUN yarn global add cross-env
CMD yarn && yarn build

FROM nginx:1.19-alpine as production-stage
RUN set -x ; \
    addgroup -g 82 -S www-data ; \
    adduser -u 82 -D -S -G www-data www-data && exit 0 ; exit 1
WORKDIR /usr/app
CMD ["nginx"]
EXPOSE 80 443
