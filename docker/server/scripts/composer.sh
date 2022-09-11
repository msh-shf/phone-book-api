#!/bin/sh

cd /var/www/html && rm composer.lock

composer install --prefer-dist --no-progress
