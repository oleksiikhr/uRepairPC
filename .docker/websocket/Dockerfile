FROM node:13.12-alpine

WORKDIR /usr/app

RUN apk add yarn

RUN yarn global add pm2

CMD yarn && yarn dev

EXPOSE 3000
