#!/bin/sh

mkdir -p public/images
chown -R www-data:www-data public

rm composer.lock
composer install --prefer-dist --no-progress

# To Generate JWT public and private keys
php bin/console lexik:jwt:generate-keypair
