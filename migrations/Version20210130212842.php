<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210130212842 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ordre_reparacio (id INT AUTO_INCREMENT NOT NULL, any INT NOT NULL, tasca VARCHAR(255) NOT NULL, data_creacio DATE NOT NULL, iva DOUBLE PRECISION NOT NULL, data_entrada DATETIME NOT NULL, data_sortida DATETIME DEFAULT NULL, autoritzacio TINYINT(1) NOT NULL, combustible DOUBLE PRECISION DEFAULT NULL, quilometres DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ordre_reparacio');
    }
}
