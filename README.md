# Учебный проект для курса по Symfony Framework

### Сервис по поиску попутчиков

## Основные сущности:

### Пользователи (Users)
```text
id (Primary Key)
username
email
password
role (passenger, driver, admin)
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

