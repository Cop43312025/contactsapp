FROM php:8.4.17-apache-trixie

RUN docker-php-ext-install pdo_mysql
COPY ./src /var/www/html/
EXPOSE 80