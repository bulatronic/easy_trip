# Используем PHP 8.3 на базе Alpine
FROM php:8.3-fpm-alpine

# Переменные для UID и GID пользователя
ARG PUID=1000
ARG PGID=1000

# Установка зависимостей
RUN apk update && apk upgrade --available && apk add --no-cache \
    bash \
    curl \
    git \
    icu-dev \
    libevent-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    libzip-dev \
    postgresql-dev \
    zlib-dev \
    autoconf \
    build-base \
    linux-headers \
    shadow

# Установка PHP расширений
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) \
        intl \
        gd \
        bcmath \
        pdo_pgsql \
        sockets \
        zip

# Установка Redis расширения через PECL
RUN pecl install redis && docker-php-ext-enable redis

# Настройка пользователя для согласования прав
RUN groupmod -o -g ${PGID} www-data && \
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

