# Build stage
FROM composer:1.10 AS builder

COPY composer.* /app/

WORKDIR /app

ARG BUILD_ENV=dev

RUN set -ex \
    && if [$BUILD_ARGS == 'prod']; then composer_install_args='--no-dev --optimize-autoloader'; fi; \
    && composer global require hirak/prestissimo \
    && composer install --no-scripts --no-suggest --no-interaction --prefer-dist $composer_install_args

COPY . /app/

# App stage
FROM php:7-alpine

RUN set -ex \
    && apk --no-cache add postgresql-dev $PHPIZE_DEPS \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && if [$BUILD_ARGS == 'dev' ]; then \
        pecl install xdebug \
        echo "zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20190902/xdebug.so" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
        echo "xdebug.default_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
        echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
        echo "xdebug.remote_port=9000" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
        echo "xdebug.remote_handler=dbgp" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
        echo "xdebug.remote_connect_back=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
        echo "xdebug.remote_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
        echo "xdebug.idekey=VSCODE" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
        echo "xdebug.remote_autostart=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
        echo "xdebug.remote_log=/usr/local/etc/php/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; fi

COPY --from=builder /app/vendor /var/www/vendor

COPY . /var/www

WORKDIR /var/www

CMD ["php", "-S", "0.0.0.0:80", "-t", "public" ]
