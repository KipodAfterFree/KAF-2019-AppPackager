FROM php:7.0-apache

RUN apt update
RUN apt install -y libzip-dev zip
RUN docker-php-ext-configure zip --with-libzip
RUN docker-php-ext-install zip

COPY src/flag.txt /flag.txt
COPY src/challenge/ /var/www/html/
RUN chmod 775 /var/www/html -R
RUN chown www-data /var/www/html -R
