FROM node:14.10-alpine

WORKDIR /usr/app

RUN apk add yarn

RUN yarn global add pm2

COPY package.json yarn.lock ./

RUN yarn

COPY . .

RUN yarn build

CMD ["pm2-runtime", "dist/app.js"]

EXPOSE 3000
