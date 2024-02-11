<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240211010405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create table for character read model';
    }

    public function up(Schema $schema): void
    {
        $sql = "CREATE TABLE characters(
            character_id CHAR(26) PRIMARY KEY,
            character_data JSON NOT NULL
        )";
        $this->addSql($sql);
    }

    public function down(Schema $schema): void
    {
        $sql = "DROP TABLE characters";
        $this->addSql($sql);
    }
}
