FROM php:8.2-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    unzip \
    libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl pdo pdo_mysql

RUN curl -sL https://deb.nodesource.com/setup_20.x | bash -\
    && apt-get install -y nodejs \
    && npm install -g yarn

RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && mkdir /var/www/.composer \
    && chown 1000:www-data /var/www/.composer \
    && mkdir /var/www/.yarn \
    && mkdir /var/www/.cache \
    && mkdir /var/www/.npm \
    && touch /var/www/.yarnrc \
    && chown 1000:www-data /var/www/.yarn \
    && chown 1000:www-data /var/www/.yarnrc \
    && chown 1000:www-data /var/www/.npm \
    && chown 1000:www-data /var/www/.cache

WORKDIR /var/www/html

RUN usermod -u 1000 www-data

USER 1000

EXPOSE 9000
CMD ["php-fpm"]
