FROM node:14.4-alpine as build-stage
WORKDIR /usr/app
RUN yarn global add cross-env
COPY package.json yarn.lock ./
RUN yarn
COPY . .
RUN yarn build

FROM nginx:1.19-alpine as production-stage
WORKDIR /usr/share/nginx/html
COPY --from=build-stage /usr/app/dist /usr/share/nginx/html
CMD ["nginx"]
EXPOSE 80 443
