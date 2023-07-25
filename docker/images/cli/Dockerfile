FROM php:8.2-cli

RUN echo 'APT::Install-Recommends "0";' >>/etc/apt/apt.conf.d/99-recommends && \
    echo 'APT::Install-Suggests "0";' >>/etc/apt/apt.conf.d/99-suggests

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && \
    apt-get install -y automake \
                       build-essential \
                       libtool && \
    apt-get -y autoremove && \
    rm -rf /var/lib/apt/lists/*

RUN pecl install xdebug-3.2.2 && \
    docker-php-ext-enable xdebug

WORKDIR /usr/src/whalephant

COPY php.ini /usr/local/etc/php/conf.d
