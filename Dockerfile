FROM php:8.2-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli \
    && docker-php-ext-enable mysqli

# Copy your project into Apache web root
COPY . /var/www/html
