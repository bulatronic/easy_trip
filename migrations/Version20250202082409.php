<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250202082409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE bookings_id_seq');
        $this->addSql('SELECT setval(\'bookings_id_seq\', (SELECT MAX(id) FROM bookings))');
        $this->addSql('ALTER TABLE bookings ALTER id SET DEFAULT nextval(\'bookings_id_seq\')');
        $this->addSql('CREATE SEQUENCE locations_id_seq');
        $this->addSql('SELECT setval(\'locations_id_seq\', (SELECT MAX(id) FROM locations))');
        $this->addSql('ALTER TABLE locations ALTER id SET DEFAULT nextval(\'locations_id_seq\')');
        $this->addSql('CREATE SEQUENCE reviews_id_seq');
        $this->addSql('SELECT setval(\'reviews_id_seq\', (SELECT MAX(id) FROM reviews))');
        $this->addSql('ALTER TABLE reviews ALTER id SET DEFAULT nextval(\'reviews_id_seq\')');
        $this->addSql('CREATE SEQUENCE trips_id_seq');
        $this->addSql('SELECT setval(\'trips_id_seq\', (SELECT MAX(id) FROM trips))');
        $this->addSql('ALTER TABLE trips ALTER id SET DEFAULT nextval(\'trips_id_seq\')');
        $this->addSql('CREATE SEQUENCE users_id_seq');
        $this->addSql('SELECT setval(\'users_id_seq\', (SELECT MAX(id) FROM users))');
        $this->addSql('ALTER TABLE users ALTER id SET DEFAULT nextval(\'users_id_seq\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE reviews ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE bookings ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE users ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE trips ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE locations ALTER id DROP DEFAULT');
    }
}
