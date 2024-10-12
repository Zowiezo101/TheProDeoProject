# We start out with an Apache webserver with PHP 8.3
FROM php:8.3-apache

# Set the server name to localhost
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Install xdebug
RUN pecl install xdebug-3.3.1 && \
    docker-php-ext-enable xdebug && \
    mkdir /var/log/xdebug

# Enable apache modules
RUN a2enmod rewrite