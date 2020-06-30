FROM node:14.4-alpine

WORKDIR /usr/app

RUN apk add yarn

COPY package.json yarn.lock ./

CMD yarn && yarn dev

EXPOSE 3000
