FROM php:8.4.17-apache-trixie

RUN docker-php-ext-install mysqli
RUN a2enmod rewrite
COPY ./src /var/www/html/

EXPOSE 80
