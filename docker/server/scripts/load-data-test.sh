#!/bin/sh

cd /var/www/html 

# create databases for "test" environment
php bin/console doctrine:database:create --env=test

# run migrations for "test" environment
echo 'yes' | php bin/console doctrine:migrations:migrate --env=test

# load fixtures (sample dummy data) for "test" environment
echo 'yes' | php bin/console doctrine:fixtures:load --env=test