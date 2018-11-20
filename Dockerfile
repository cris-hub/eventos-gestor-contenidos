FROM php:7.1-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

WORKDIR /var/www/html
COPY . /var/www/html
COPY ./vendor/ /var/www/html

RUN chmod -R 777 -c /var/www/html/vendor/ && \
    chmod -R 777 -c /var/www/html/runtime/ && \
    chmod -R 777 -c /var/www/html/uploads/ && \
    chmod -R 777 -c /var/www/html/assets/ && \
    chmod -R 777 -c /var/www/html/web/ && \
    chgrp www-data /var/www/html/web/assets/ && \
    chmod g+w /var/www/html/web/assets/