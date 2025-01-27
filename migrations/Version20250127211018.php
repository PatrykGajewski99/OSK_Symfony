<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250127211018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creating users table with relations';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "users" (id UUID NOT NULL, first_name VARCHAR(64) NOT NULL, second_name VARCHAR(64) DEFAULT NULL, last_name VARCHAR(64) NOT NULL, email VARCHAR(255) NOT NULL, pesel VARCHAR(11) NOT NULL, password VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_user_email ON "users" (email)');
        $this->addSql('CREATE INDEX idx_user_pesel ON "users" (pesel)');
        $this->addSql('COMMENT ON COLUMN "users".id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE user_organization (user_id UUID NOT NULL, organization_id UUID NOT NULL, PRIMARY KEY(user_id, organization_id))');
        $this->addSql('CREATE INDEX IDX_41221F7EA76ED395 ON user_organization (user_id)');
        $this->addSql('CREATE INDEX IDX_41221F7E32C8A3DE ON user_organization (organization_id)');
        $this->addSql('COMMENT ON COLUMN user_organization.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_organization.organization_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE user_organization ADD CONSTRAINT FK_41221F7EA76ED395 FOREIGN KEY (user_id) REFERENCES "users" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_organization ADD CONSTRAINT FK_41221F7E32C8A3DE FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_organization DROP CONSTRAINT FK_41221F7EA76ED395');
        $this->addSql('ALTER TABLE user_organization DROP CONSTRAINT FK_41221F7E32C8A3DE');
        $this->addSql('DROP TABLE "users"');
        $this->addSql('DROP TABLE user_organization');
    }
}
