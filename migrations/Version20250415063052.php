<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250415063052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE statistics (id BIGSERIAL NOT NULL, driver_id BIGINT DEFAULT NULL, statistics_type VARCHAR(20) NOT NULL, period_type VARCHAR(20) NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, total_trips INT NOT NULL, total_revenue NUMERIC(10, 2) NOT NULL, total_passengers INT NOT NULL, average_price NUMERIC(10, 2) NOT NULL, average_passengers NUMERIC(5, 2) NOT NULL, average_rating NUMERIC(3, 2) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX statistics__period_type__idx ON statistics (period_type)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX statistics__created_at__idx ON statistics (created_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX statistics__driver_id__idx ON statistics (driver_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX statistics__period_type_start_date_end_date__idx ON statistics (period_type, start_date, end_date)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE statistics ADD CONSTRAINT FK_E2D38B22C3423909 FOREIGN KEY (driver_id) REFERENCES users (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE statistics DROP CONSTRAINT FK_E2D38B22C3423909
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE statistics
        SQL);
    }
}
