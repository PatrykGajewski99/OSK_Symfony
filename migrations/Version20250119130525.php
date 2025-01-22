<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250119130525 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create organizations table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE organizations (id UUID NOT NULL, name VARCHAR(255) NOT NULL, street VARCHAR(128) NOT NULL, house_number VARCHAR(16) NOT NULL, flat_number VARCHAR(16) DEFAULT NULL, nip VARCHAR(32) NOT NULL, country VARCHAR(128) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('COMMENT ON COLUMN organizations.id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE organizations');
    }
}
