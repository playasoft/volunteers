#!/usr/bin/env bash

docker run --rm \
    --user $(id -u):$(id -g) \
    --volume $PWD:/var/www/default \
    --volume $HOME/.composer:/.composer \
    --workdir /var/www/default \
    --entrypoint "composer" \
    kyleparisi/larasible "$@"
