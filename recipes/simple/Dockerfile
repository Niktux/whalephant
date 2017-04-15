FROM php:7.1-cli

RUN pecl install xdebug  && \
    docker-php-ext-enable xdebug 

WORKDIR /var/www/whalephant-test

COPY php.ini /usr/local/etc/php/conf.d
