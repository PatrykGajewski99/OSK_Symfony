<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\ValueObjects\RoleName;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250218171529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Crete roles table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE roles (id UUID NOT NULL, name VARCHAR(64) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_role_name ON roles (name)');
        $this->addSql('COMMENT ON COLUMN roles.id IS \'(DC2Type:uuid)\'');
        
        $roles = RoleName::getAll();

        foreach ($roles as $role) {
            $this->addSql("INSERT INTO roles (id, name, created_at, updated_at) VALUES (gen_random_uuid(), :name, NOW(), NOW())", [
                'name' => $role->value
            ]);
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE roles');
    }
}
