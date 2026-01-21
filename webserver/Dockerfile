FROM php:8.4.17-apache-trixie

RUN docker-php-ext-install mysqli
COPY ./src /var/www/html/
EXPOSE 80