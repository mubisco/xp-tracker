<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240501230210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create encounter projection table';
    }

    public function up(Schema $schema): void
    {
        $sql = "CREATE TABLE encounter(
            encounter_id CHAR(26) PRIMARY KEY,
            party_id CHAR(26) NOT NULL,
            status VARCHAR(8) NOT NULL
            data JSON NOT NULL,
            INDEX (party_id)
        )";
        $this->addSql($sql);
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP TABLE IF EXISTS encounter");
    }
}
