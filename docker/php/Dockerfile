FROM php:7.4

WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libxml2-dev libc-client-dev libkrb5-dev \
    libz-dev libonig-dev libzip-dev libssl-dev

# Composer Installation
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath soap sockets
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install gd
RUN PHP_OPENSSL=yes docker-php-ext-configure imap --with-kerberos --with-imap-ssl && docker-php-ext-install imap

# XDEBUG
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
# This needs in order to run xdebug from PhpStorm
ENV PHP_IDE_CONFIG "serverName=DockerApp"

ADD php.ini /usr/local/etc/php
