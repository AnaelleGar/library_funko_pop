FROM php:8.2-fpm

RUN apt-get update && \
apt-get install -y --no-install-recommends \
zlib1g-dev libxml2-dev librabbitmq-dev less git openssh-client unzip vim libpng-dev curl libzip-dev locales apt-utils git libicu-dev g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev gnupg nano;

RUN echo "en_US.UTF-8 UTF-8" >> /etc/locale.gen && \
    echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen

RUN docker-php-ext-install pdo pdo_mysql mysqli sockets zip soap gd intl calendar dom mbstring xsl

RUN pecl install apcu amqp mailparse xdebug

RUN docker-php-ext-enable apcu amqp mailparse xdebug pdo_mysql gd opcache intl zip calendar dom mbstring xsl
RUN docker-php-ext-configure intl

RUN curl --location --output /usr/local/bin/composer https://getcomposer.org/composer.phar && chmod +x /usr/local/bin/composer
RUN apt-get update && apt-get install -y \
    software-properties-common \
    nodejs \
    npm

RUN echo node -v

RUN npm install npm@9 -g && \
    npm install n -g && \
    npm install yarn -g && \
    n latest

RUN git config --global --add safe.directory /var/www

RUN export NODE_OPTIONS=--openssl-legacy-provider

RUN echo 'upload_max_filesize=512M' > /usr/local/etc/php/conf.d/upload_max_filesize.ini;
RUN echo 'post_max_size=512M' > /usr/local/etc/php/conf.d/post_max_size.ini;

WORKDIR /var/www

RUN PATH=$PATH:/var/www/vendor/bin:bin
