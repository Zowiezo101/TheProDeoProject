# We start out with an Apache webserver with PHP 8.2
FROM php:8.2-apache

# Set the server name to localhost
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Install the following libraries
RUN apt-get update && apt-get install -y \
	libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
	libgmp-dev libc-client-dev libkrb5-dev \
	libicu-dev libxml2-dev libtidy-dev \
	libxslt-dev libzip-dev default-mysql-client \
	&& rm -r /var/lib/apt/lists/*
	
# Configure the following extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl
RUN docker-php-ext-install imap

# Install the following extensions
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install gmp
RUN docker-php-ext-install intl
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install opcache
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install soap
RUN docker-php-ext-install sockets
RUN docker-php-ext-install sysvmsg
RUN docker-php-ext-install sysvsem
RUN docker-php-ext-install sysvshm
RUN docker-php-ext-install tidy
RUN docker-php-ext-install xsl
RUN docker-php-ext-install zip
	
# Install and enable igbinary
RUN pecl install igbinary && \
	docker-php-ext-enable igbinary
	
# Install and enable timezonedb
RUN pecl install timezonedb && \
	docker-php-ext-enable timezonedb

# Install and enable Xdebug
RUN pecl install xdebug-3.3.2 && \
    docker-php-ext-enable xdebug && \
    mkdir /var/log/xdebug

# Enable apache modules
RUN a2enmod actions
RUN a2enmod allowmethods
RUN a2enmod asis
RUN a2enmod auth_digest
RUN a2enmod authz_groupfile
RUN a2enmod cache
RUN a2enmod cache_disk
RUN a2enmod cgi
RUN a2enmod file_cache
RUN a2enmod headers
RUN a2enmod include
RUN a2enmod rewrite
RUN a2enmod userdir
RUN a2enmod vhost_alias
