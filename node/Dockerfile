# npm bits
FROM node:7.7.2-alpine

COPY package.json websocket-server.js /usr/app/

WORKDIR /usr/app

RUN npm install
RUN node /usr/app/websocket-server.js