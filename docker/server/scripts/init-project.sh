#!/bin/sh

#|-------------------------------------------------------------------------------|
#|-------------------------- setup the symfony project --------------------------|
#|-------------------------------------------------------------------------------|
cd /var/www/html

mkdir -p public/images
chown -R www-data:www-data public

rm composer.lock
composer install --prefer-dist --no-progress

# To Generate JWT public and private keys
echo 'yes' | php bin/console lexik:jwt:generate-keypair --overwrite

#|-------------------------------------------------------------------------------|
#|------------------------ Load Data for dev environment ------------------------|
#|-------------------------------------------------------------------------------|
# run migrations for "dev" environment
echo 'yes' | php bin/console doctrine:migrations:migrate

# load fixtures (sample dummy data) for"dev" environment
echo 'yes' | php bin/console doctrine:fixtures:load


#|-------------------------------------------------------------------------------|
#|------------------------ Load Data for test environment ------------------------|
#|-------------------------------------------------------------------------------|
# create databases for "test" environment
php bin/console doctrine:database:create --env=test

# run migrations for "test" environment
echo 'yes' | php bin/console doctrine:migrations:migrate --env=test

# load fixtures (sample dummy data) for "test" environment
echo 'yes' | php bin/console doctrine:fixtures:load --env=test

# to avoid exit container

/usr/bin/supervisord -n