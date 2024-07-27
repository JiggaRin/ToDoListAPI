FROM php:8.2-apache

# Install necessary PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Copy local configuration files
COPY ./php/local.ini /usr/local/etc/php/conf.d/local.ini
COPY ./apache/vhost.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache modules
RUN a2enmod rewrite
