<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240329105421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Party projection tables';
    }

    public function up(Schema $schema): void
    {
        $partySql = "CREATE TABLE party(
            party_id CHAR(26) PRIMARY KEY,
            name VARCHAR(128) NOT NULL
        )";
        $relationSql = "CREATE TABLE party_character(
            party_id CHAR(26),
            character_id CHAR(26),
            PRIMARY KEY (party_id, character_id),
            FOREIGN KEY (party_id) REFERENCES party(party_id),
            FOREIGN KEY (character_id) REFERENCES characters(character_id)
        )";
        $this->addSql($partySql);
        $this->addSql($relationSql);
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP TABLE IF EXISTS party_character");
        $this->addSql("DROP TABLE IF EXISTS party");
    }
}
