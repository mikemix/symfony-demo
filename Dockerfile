FROM php:7.1-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends curl libpq-dev libzip-dev zlib1g-dev libicu-dev \
    && apt-get clean \
    && rm -rf /var/* /var/apt/lists/*

RUN docker-php-ext-install zip

ENV COMPOSER_PATH /usr/bin/composer
RUN curl -o $COMPOSER_PATH https://getcomposer.org/composer.phar && chmod +x $COMPOSER_PATH

RUN rm -rf /var/www/html/*
COPY . /var/www/html

RUN composer install -a -n -o --prefer-dist --no-suggest
RUN chown -R 33:33 var
RUN chmod -R 777 var/cache

COPY vhost.conf /etc/apache2/sites-enabled/000-default.conf
