#!/bin/sh

cd /var/www/html && rm composer.lock

composer update --prefer-dist --no-progress
