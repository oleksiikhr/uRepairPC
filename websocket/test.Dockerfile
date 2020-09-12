FROM node:14.10-alpine

WORKDIR /usr/app

RUN apk add yarn

COPY package.json yarn.lock ./

RUN yarn

COPY . .

CMD yarn lint && yarn test
