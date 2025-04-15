# Учебный проект для курса по Symfony Framework

### Сервис по поиску попутчиков

## Основные сущности:

### Пользователи (Users)
```text
id (Primary Key)
username
email
password
roles (ROLE_DRIVER, ROLE_PASSENGER, ROLE_ADMIN)
created_at
updated_at
```

### Населенные пункты (Locations)
```text
id (Primary Key)
name
type (city, administrative_center ...)
latitude (широта)
longitude (долгота)
```

### Поездки (Trips)
```text
id (Primary Key)
driver_id (Foreign Key, ссылается на Users)
start_location_id (Foreign Key, ссылается на Locations)
end_location_id (Foreign Key, ссылается на Locations)
departure_time
available_seats
price_per_seat
status (scheduled, in_progress, completed, cancelled)
created_at
updated_at
```

### Бронирования (Bookings)
```text
id (Primary Key)
trip_id (Foreign Key, ссылается на Trips)
passenger_id (Foreign Key, ссылается на Users)
seats_booked
status (booked, cancelled, completed)
created_at
updated_at
```

### Отзывы (Reviews)
```text
id (Primary Key)
user_id (Foreign Key, ссылается на Users)
trip_id (Foreign Key, ссылается на Trips)
rating
comment
created_at
updated_at
```

## Взаимосвязи

- **Пользователи и Поездки**: Один водитель может создать много поездок.
- **Поездки и Населенные пункты**: Каждая поездка имеет начальный и конечный населенный пункт.
- **Поездки и Бронирования**: Одну поездку могут забронировать несколько пассажиров.
- **Бронирования и Пользователи**: Один пассажир может забронировать несколько поездок.
- **Поездки и Отзывы**: Одну поездку могут оценить несколько пользователей.
- **Пользователи и Отзывы**: Один пользователь может оставить несколько отзывов.

## Установка

1. Склонируйте проект:
   ```sh
   git clone <URL>
   ```

2. Запустите команду:
   ```sh
   docker-compose up -d
   ```

## Засеивание фикстур (из контейнера)
   ```sh
   php bin/console doctrine:fixtures:load --purge-with-truncate
   ```

## Команда генерации статистики

Команда генерирует статистику поездок за указанный период.

## Параметры команды

| Параметр      | Обязательность | Значения                          | Описание                                                                 |
|---------------|----------------|-----------------------------------|--------------------------------------------------------------------------|
| `--type`      | Обязательный   | `personal`, `global`              | Тип статистики: персональная или общая                                   |
| `--period`    | Обязательный   | `daily`, `weekly`, `monthly`, `yearly` | Период агрегации статистики                                         |
| `--driver-id` | Опциональный   | Числовой ID                      | ID водителя (только для `personal` статистики)                          |
| `--start-date`| Опциональный   | Дата в формате `YYYY-MM-DD`      | Начальная дата периода (по умолчанию - начало текущего периода)         |
| `--end-date`  | Опциональный   | Дата в формате `YYYY-MM-DD`      | Конечная дата периода (по умолчанию - конец текущего периода)           |

## Генератор статистики - Примеры использования

### Глобальная статистика

```bash

# Дневная статистика
php bin/console app:generate-statistics --type=global --period=daily

# Месячная статистика
php bin/console app:generate-statistics --type=global  --period=monthly

# Годовая статистика
php bin/console app:generate-statistics --type=global  --period=yearly
```

### Персональная статистика водителя

```bash

# Дневная статистика
php bin/console app:generate-statistics --type=personal --period=daily --driver-id=1

# Месячная статистика
php bin/console app:generate-statistics --type=personal --period=monthly --driver-id=1

# Годовая статистика
php bin/console app:generate-statistics --type=personal --period=yearly --driver-id=1
```

### Персональная статистика за период

```bash
php bin/console app:generate-statistics --type=personal --driver-id=1 --start-date=2025-04-01 --end-date=2025-04-14
```

[//]: # (### Тестирование ошибок)

[//]: # ()
[//]: # (```bash)

[//]: # (# Не указан ID водителя для персональной статистики)

[//]: # (php bin/console app:generate-statistics --type=personal)

[//]: # ()
[//]: # (# Неверный период)

[//]: # (php bin/console app:generate-statistics --period=invalid)

[//]: # ()
[//]: # (# Неверный формат даты)

[//]: # (php bin/console app:generate-statistics --start-date=01-04-2025)

[//]: # (```)