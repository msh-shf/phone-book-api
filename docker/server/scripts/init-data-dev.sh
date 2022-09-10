#!/bin/sh

cd /var/www/html 

# run migrations for "dev" environment
echo 'yes' | php bin/console doctrine:migrations:migrate

# load fixtures (sample dummy data) for"dev" environment
echo 'yes' | php bin/console doctrine:fixtures:load