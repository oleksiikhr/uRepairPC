FROM node:14.4-alpine

WORKDIR /usr/app

RUN yarn global add cross-env

COPY package.json yarn.lock ./

CMD yarn && yarn dev

EXPOSE 80
