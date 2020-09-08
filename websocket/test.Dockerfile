FROM node:14.4-alpine

WORKDIR /usr/app

RUN apk add yarn

COPY package.json yarn.lock ./

RUN yarn

COPY . .

CMD yarn test
