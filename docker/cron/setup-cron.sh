#!/bin/bash
set -e

# Устанавливаем переменную окружения
export APP_ENV=${APP_ENV}

# Вывод для отладки
echo "Current environment variables:"
echo "APP_ENV: ${APP_ENV}"

# Тестовая cron задача
echo "0 0 * * * app echo \"[CRON] Starting JWT clear at \$(date)\" >> /var/www/html/var/log/cron.log && /usr/local/bin/php /var/www/html/bin/console gesdinet:jwt:clear >> /var/www/html/var/log/cron.log 2>&1 && echo \"[CRON] Finished JWT clear at \$(date)\" >> /var/www/html/var/log/cron.log" > /etc/cron.d/my-cron

chmod 0644 /etc/cron.d/my-cron

# Применяем cron задачи
crontab /etc/cron.d/my-cron

# Запускаем cron демон (dcron) в фоновом режиме
crond -l 8 &

# Убедимся, что PostgreSQL готов перед продолжением
echo "Waiting for PostgreSQL to be ready..."
sleep 10

# Dev окружение
if [ "$APP_ENV" = "dev" ]; then
    composer dump-autoload
    composer require symfony/requirements-checker:2.0.1
    chmod -R +x vendor/bin/requirements-checker
    php bin/console cache:clear --no-warmup
    php bin/console cache:warmup
    # cp ".env.$APP_ENV" .env
    echo "Running database migrations..."
    php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration
    echo "Loading fixtures..."
    php bin/console doctrine:fixtures:load --no-interaction --purge-with-truncate
fi

# Устанавливаем максимальный размер лога 5MB в байтах
MAX_SIZE=5242880
LOG_FILE="/var/www/html/var/log/cron.log"

if [ -f "$LOG_FILE" ]; then
    FILE_SIZE=$(stat -c%s "$LOG_FILE")
    if [ "$FILE_SIZE" -ge "$MAX_SIZE" ]; then
        echo "[$(date)] Truncating cron.log (was $FILE_SIZE bytes)" > "$LOG_FILE"
    fi
fi

# Запускаем supervisor
exec /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf