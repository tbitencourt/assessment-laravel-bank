FROM php:8.4-fpm

LABEL maintainer="Thales Bitencourt"
LABEL php_version=8.4
LABEL node_version=22

# Set working directory
WORKDIR /var/www/html

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC

# Set timezone
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential net-tools \
    libfreetype6-dev libicu-dev libjpeg-dev libpq-dev libpng-dev libzip-dev \
    zip unzip \
    curl \
    git \
    gnupg

# Install extensions
RUN docker-php-ext-install pdo_mysql pdo_pgsql intl zip
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
RUN docker-php-ext-configure pcntl --enable-pcntl
RUN docker-php-ext-install pcntl

# Install node and npm
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash -
RUN apt-get update
RUN apt-get install -y nodejs
RUN npm install -g npm

# Clear Apt cache
RUN apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add Xdebug to PHP configuration
RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY php.ini /usr/local/etc/php/conf.d/99-local.ini

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Change current user to www
USER www
