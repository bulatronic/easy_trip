<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250125115646 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bookings (id BIGINT NOT NULL, trip_id BIGINT NOT NULL, passenger_id BIGINT NOT NULL, seats_booked INT NOT NULL, status VARCHAR(50) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX booking__trip_id__idx ON bookings (trip_id)');
        $this->addSql('CREATE INDEX booking__passenger_id__idx ON bookings (passenger_id)');
        $this->addSql('CREATE TABLE locations (id BIGINT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(50) NOT NULL, latitude NUMERIC(10, 8) NOT NULL, longitude NUMERIC(11, 8) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX location__name__idx ON locations (name)');
        $this->addSql('CREATE INDEX location__type__idx ON locations (type)');
        $this->addSql('CREATE INDEX location__coordinates__idx ON locations (latitude, longitude)');
        $this->addSql('CREATE TABLE reviews (id BIGINT NOT NULL, user_id BIGINT NOT NULL, trip_id BIGINT NOT NULL, rating INT NOT NULL, comment TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX review__user_id__idx ON reviews (user_id)');
        $this->addSql('CREATE INDEX review__trip_id__idx ON reviews (trip_id)');
        $this->addSql('CREATE TABLE trips (id BIGINT NOT NULL, driver_id BIGINT NOT NULL, start_location_id BIGINT NOT NULL, end_location_id BIGINT NOT NULL, departure_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_seats INT NOT NULL, price_per_seat NUMERIC(10, 2) NOT NULL, status VARCHAR(50) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX trip__driver_id__idx ON trips (driver_id)');
        $this->addSql('CREATE INDEX trip__start_location_id__idx ON trips (start_location_id)');
        $this->addSql('CREATE INDEX trip__end_location_id__idx ON trips (end_location_id)');
        $this->addSql('CREATE TABLE users (id BIGINT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(50) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX user__email__uniq ON users (email)');
        $this->addSql('ALTER TABLE bookings ADD CONSTRAINT FK_booking_trip FOREIGN KEY (trip_id) REFERENCES trips (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bookings ADD CONSTRAINT FK_booking_passenger FOREIGN KEY (passenger_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_review_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_review_trip FOREIGN KEY (trip_id) REFERENCES trips (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trips ADD CONSTRAINT FK_trip_driver FOREIGN KEY (driver_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trips ADD CONSTRAINT FK_trip_start_location FOREIGN KEY (start_location_id) REFERENCES locations (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trips ADD CONSTRAINT FK_trip_end_location FOREIGN KEY (end_location_id) REFERENCES locations (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE bookings DROP CONSTRAINT FK_booking_trip');
        $this->addSql('ALTER TABLE bookings DROP CONSTRAINT FK_booking_passenger');
        $this->addSql('ALTER TABLE reviews DROP CONSTRAINT FK_review_user');
        $this->addSql('ALTER TABLE reviews DROP CONSTRAINT FK_review_trip');
        $this->addSql('ALTER TABLE trips DROP CONSTRAINT FK_trip_driver');
        $this->addSql('ALTER TABLE trips DROP CONSTRAINT FK_trip_start_location');
        $this->addSql('ALTER TABLE trips DROP CONSTRAINT FK_trip_end_location');
        $this->addSql('DROP TABLE bookings');
        $this->addSql('DROP TABLE locations');
        $this->addSql('DROP TABLE reviews');
        $this->addSql('DROP TABLE trips');
        $this->addSql('DROP TABLE users');
    }
}
