FROM composer:1.10 AS builder

COPY composer.* /app/

WORKDIR /app

RUN set -xe \
    && composer global require hirak/prestissimo \
    && composer install --no-dev --no-scripts --no-suggest --no-interaction --prefer-dist --optimize-autoloader

COPY . /app/

RUN composer dump-autoload --no-dev --optimize

FROM php:7-alpine

RUN set -ex \
    && apk --no-cache add postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

COPY --from=builder /app/vendor /var/www/vendor

COPY . /var/www

WORKDIR /var/www

#RUN php /var/www/vendor/bin/doctrine orm:schema-tool:update

CMD ["php", "-S", "0.0.0.0:80", "-t", "public" ]
