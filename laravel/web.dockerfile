# base nginx image
FROM nginx:1.10-alpine as nginxbase
COPY vhost.conf /etc/nginx/conf.d/default.conf

# Fetch node dependencies
# - generates main.css and bundle.js
FROM node:8 as nodebuild
COPY package*.json /usr/src/app/
WORKDIR /usr/src/app
RUN npm install
COPY webpack*.js /usr/src/app/
COPY resources /usr/src/app/resources
RUN npm run build

# Start nginx
FROM nginxbase
COPY --from=nodebuild /usr/src/app/public/css/main.css /var/www/public/css/
COPY --from=nodebuild /usr/src/app/public/js/bundle.js /var/www/public/js/
COPY public /var/www/public
