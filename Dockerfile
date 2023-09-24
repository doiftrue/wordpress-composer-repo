##
## @var $PHP_INI_DIR eg: /usr/local/etc/php
## @var $PHP_VERSION eg: 7.4.33
##
FROM php:8.2-cli

LABEL Version="0.1"
LABEL ImageName="WPCOMPREPO PHP"
LABEL Author="Timur Kamaev (wp-kama.ru)"

RUN set -ex; \
# Bash settings
	echo 'alias ll="ls -l --color"' >>  /etc/bash.bashrc; \
# Use the default PHP configuration.
# IMPORTANT: Use `php.ini-production` file for Prod env.
	cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini";

RUN apt-get update; \
# Enable exit on error & debugging
	set -ex; \
# OS Utilities
	apt-get -y install \
	libonig-dev \
	libxml2-dev \
	libzip-dev \
	tree \
    less \
	unzip \
	zip \
	wget \
	curl \
	nano \
	mc;

# php Extensions
RUN docker-php-ext-install \
	mbstring \
	opcache \
	exif \
	intl \
	zip;

RUN set -ex; \
# Install Composer
# See: https://getcomposer.org/download
	wget https://getcomposer.org/download/2.5.5/composer.phar \
	&& chmod +x composer.phar \
	&& mv composer.phar /usr/local/bin/composer \
;

# For correct access to container mounting volume
RUN usermod -u 1000 www-data; \
	groupmod -g 1000 www-data; \
	chown -R www-data:www-data /var/www;

WORKDIR /var/www/app
USER www-data

CMD [ "bash" ]


