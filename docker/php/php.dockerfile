FROM php:7.3-fpm

RUN useradd -u 1000 local

RUN docker-php-ext-install pdo_mysql mysqli

# Set up the working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update
RUN apt-get -y install git
RUN apt-get -y install zip
RUN apt-get -y install unzip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer