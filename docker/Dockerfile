# Используем PHP 8.3 на базе Alpine
FROM php:8.3-fpm-alpine

# Переменные для UID и GID пользователя
ARG PUID=1000
ARG PGID=1000

# Установка зависимостей
RUN apk update && apk upgrade --available && apk add --virtual build-deps \
        autoconf \
        build-base \
        icu-dev \
        libevent-dev \
        openssl-dev \
        zlib-dev \
        libzip \
        libzip-dev \
        zlib \
        zlib-dev \
        bzip2 \
        git \
        libpng \
        libpng-dev \
        libjpeg \
        libjpeg-turbo-dev \
        libwebp-dev \
        freetype \
        freetype-dev \
        postgresql-dev \
        linux-headers \
        libmemcached-dev \
        rabbitmq-c \
        rabbitmq-c-dev \
        curl \
        wget \
        bash

# Установка PHP расширений
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install -j$(getconf _NPROCESSORS_ONLN) \
    intl \
    gd \
    bcmath \
    pcntl \
    pdo_pgsql \
    sockets \
    zip
RUN pecl channel-update pecl.php.net \
    && pecl install -o -f \
        amqp \
        memcached \
        redis \
        event \
    && rm -rf /tmp/pear \
    && echo "extension=amqp.so" > /usr/local/etc/php/conf.d/amqp.ini \
    && echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini \
    && echo "extension=event.so" > /usr/local/etc/php/conf.d/event.ini \
    && echo "extension=memcached.so" > /usr/local/etc/php/conf.d/memcached.ini

# Настройка пользователя для согласования прав
RUN apk add --no-cache shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка рабочей директории
WORKDIR /var/www/html

# Копируем файлы проекта
COPY ./ /var/www/html

# Устанавливаем права на папки
RUN chown -R www-data:www-data /var/www/html

# Указываем команду по умолчанию
CMD ["php-fpm"]

# Открываем порт PHP-FPM
EXPOSE 9000

