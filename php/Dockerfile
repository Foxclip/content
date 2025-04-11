FROM php:8.2-apache

RUN apt-get update && apt-get upgrade -y
RUN a2enmod rewrite
RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo pdo_mysql
RUN pecl install xdebug-3.2.1 && docker-php-ext-enable xdebug
COPY docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY php.ini-development /usr/local/etc/php/php.ini

# Чтобы сервер не перенаправлял папку /var/www/html/icons
COPY alias.conf /etc/apache2/mods-available/

COPY apache2.conf /etc/apache2/
COPY 000-default.conf /etc/apache2/sites-available/
