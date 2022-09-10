ARG VERSION=8.1.0-fpm-bullseye-nginx-1.22.0
FROM dwchiang/nginx-php-fpm:${VERSION}

RUN apt-get update && apt-get install -y git unzip zip

WORKDIR /var/www/html

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions gd pdo_mysql bcmath zip intl opcache

COPY --from=composer:2.1 /usr/bin/composer /usr/local/bin/composer
