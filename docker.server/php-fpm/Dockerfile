FROM php:8.1.18-fpm-alpine3.17

# Install packages
RUN apk add --no-cache curl git build-base zlib-dev oniguruma-dev autoconf bash

# Xdebug
ARG INSTALL_XDEBUG=false
RUN if [ ${INSTALL_XDEBUG} = true ]; \
    then \
      pecl install xdebug && docker-php-ext-enable xdebug; \
    fi;

COPY ./docker.server/php-fpm/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Postgres
RUN apk add --no-cache libpq-dev && docker-php-ext-install pdo_mysql mysqli

# Configure non-root user.
ARG PUID=1000
ARG PGID=1000
RUN apk --no-cache add shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

# Source code Server
RUN chown www-data:www-data /var/www
COPY --chown=www-data:www-data ./server /var/www/server
WORKDIR /var/www/server

USER www-data

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction


# Source code Client
RUN chown www-data:www-data /var/www
COPY --chown=www-data:www-data ./client /var/www/client
WORKDIR /var/www/client

USER www-data

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction

CMD php-fpm

EXPOSE 9000
