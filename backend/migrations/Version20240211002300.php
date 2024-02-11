<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240211002300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create table for event store';
    }

    public function up(Schema $schema): void
    {
        $sql = "CREATE TABLE events(
            id INT AUTO_INCREMENT PRIMARY KEY,
            event_class VARCHAR(256) NOT NULL,
            aggregate_id CHAR(26) NOT NULL,
            body JSON NOT NULL
        )";
        $this->addSql($sql);
        $indexSql = "CREATE INDEX aggregate_id_idx ON events(aggregate_id)";
        $this->addSql($indexSql);
    }

    public function down(Schema $schema): void
    {
        $sql = "DROP TABLE events";
        $this->addSql($sql);
    }
}
