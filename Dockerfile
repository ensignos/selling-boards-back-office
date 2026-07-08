FROM php:8.4-fpm AS base

RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    default-mysql-client \
    && docker-php-ext-install pdo_mysql zip gd \
    && rm -rf /var/lib/apt/lists/* 

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

FROM base AS dev

ARG UID=1000
ARG GID=1000

RUN groupmod -g ${GID} www-data && usermod -u ${UID} -g ${GID} www-data

RUN composer install --no-interaction --no-scripts

COPY . .

RUN composer dump-autoload --optimize \
    && chown -R www-data:www-data storage bootstrap/cache

USER www-data

EXPOSE 9000

CMD ["php-fpm"]

FROM base AS prod

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY . .

RUN composer dump-autoload --optimize \
    && chown -R www-data:www-data storage bootstrap/cache

USER www-data

EXPOSE 9000

CMD ["php-fpm"]