ARG PHP_VERSION=8.3
ARG PHP_EXTENSION_INSTALLER_VERSION=2

FROM composer:lts AS composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN set -eux \
    && composer install --no-scripts --no-progress

COPY --chown=app:app public/ ./public/
COPY --chown=app:app config/ ./config/
COPY --chown=app:app src/ ./src/
COPY --chown=app:app .env .env.dev ./
COPY --chown=app:app bin/ ./bin/
COPY --chown=app:app migrations/ ./migrations/
COPY --chown=app:app templates/ ./templates/
COPY --chown=app:app symfony.lock ./

FROM mlocati/php-extension-installer:${PHP_EXTENSION_INSTALLER_VERSION} AS php_extension_installer

FROM php:${PHP_VERSION}-fpm-alpine AS php_dev
ENV APP_ENV=dev
ENV APP_DEBUG=1

COPY --from=php_extension_installer --link /usr/bin/install-php-extensions /usr/local/bin/

RUN apk update && \
    apk upgrade && \
    apk add --no-cache --update zlib-dev icu-dev openssl-dev curl-dev  && \
    install-php-extensions mysqli pdo_mysql && \
    set -e && \
    ln -s $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV PATH=~/.composer/vendor/bin:$PATH

RUN docker-php-ext-enable mysqli pdo_mysql && \
    rm -rf /tmp/pear /var/cache/apk/*

WORKDIR /var/www/html

COPY --from=composer /var/www/html/vendor ./vendor
COPY --from=composer /var/www/html/ .

RUN adduser app -D && \
    chown -R app:www-data public/ && \
    chmod -R 755 /var/www/html/public

USER app

CMD [ "php-fpm" ]
