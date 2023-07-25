FROM php:8.2-cli

RUN pecl install xdebug-3.2.2 \
    && docker-php-ext-enable xdebug

COPY php.ini /usr/local/etc/php/conf.d
