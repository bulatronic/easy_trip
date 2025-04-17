# Используем PHP 8.3 на базе Alpine
FROM php:8.3-fpm-alpine

# Переменные для UID и GID пользователя
ARG APP_ENV
ARG HOST_UID=1000
ARG HOST_GID=1000

ENV APP_ENV=${APP_ENV}
ENV HOST_UID=${HOST_UID}
ENV HOST_GID=${HOST_GID}

# Установка необходимых runtime-зависимостей
RUN apk update && apk upgrade --available && apk add --no-cache \
    bash \
    curl \
    wget \
    git \
    dcron \
    supervisor \
    libzip \
    zlib \
    bzip2 \
    libpng \
    libjpeg-turbo \
    libwebp \
    freetype \
    postgresql-libs \
    rabbitmq-c

# Установка build-зависимостей
RUN apk add --no-cache --virtual .build-deps \
    autoconf \
    build-base \
    icu-dev \
    libevent-dev \
    openssl-dev \
    zlib-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    postgresql-dev \
    libmemcached-dev \
    rabbitmq-c-dev \
    linux-headers

# Установка PHP расширений
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(getconf _NPROCESSORS_ONLN) \
        intl \
        gd \
        bcmath \
        pcntl \
        pdo_pgsql \
        sockets \
        zip

# Установка PECL расширений
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
    groupmod -o -g ${HOST_UID} www-data && \
    usermod -o -u ${HOST_GID} -g www-data www-data

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Настройка Supervisor для Alpine Linux
RUN mkdir -p /etc/supervisor/conf.d /etc/supervisor.d
COPY docker/supervisor/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

# Создаем симлинк на конфигурацию для Alpine или копируем файл в оба места
RUN mkdir -p /etc/supervisor && \
    echo "[include]" > /etc/supervisord.conf && \
    echo "files = /etc/supervisor/conf.d/*.conf /etc/supervisor.d/*.ini" >> /etc/supervisord.conf && \
    ln -sf /etc/supervisord.conf /etc/supervisor/supervisord.conf

# Копируем и настраиваем entrypoint
COPY docker/cron/setup-cron.sh /usr/local/bin/setup-cron.sh
RUN chmod +x /usr/local/bin/setup-cron.sh

# Установка рабочей директории
WORKDIR /var/www/html

# Копируем файлы проекта
COPY ./ /var/www/html

# Подготовка каталогов и установка разрешений
RUN mkdir -p \
    var/log \
    var/supervisor \
    var/cache \
    vendor/bin \
    vendor/symfony/requirements-checker/bin \
    && chown -R ${HOST_UID}:${HOST_GID} /var/www/html \
    && chmod -R 775 \
        vendor/bin \
        vendor/symfony/requirements-checker/bin \
    && chmod -R 777 \
        var/log \
        var/supervisor \
        var/cache

# Настройка прав на все директории и файлы
RUN set -e \
    && find /var/www/html -type f -exec chmod 664 {} + \
    && find /var/www/html -type d -exec chmod 775 {} + \
    && find /var/www/html/vendor/bin -type f -exec chmod +x {} + \
    && find /var/www/html/vendor/symfony/requirements-checker/bin -type f -exec chmod +x {} +

# Устанавливаем права на папки
RUN chown -R www-data:www-data /var/www/html
RUN mkdir -p /var/log/supervisor && chmod -R 777 /var/log/supervisor

ENTRYPOINT ["/bin/bash", "/usr/local/bin/setup-cron.sh"]