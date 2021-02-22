<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210222215820 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE any (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE factura (id INT AUTO_INCREMENT NOT NULL, vehicle_id INT DEFAULT NULL, treballador_id INT DEFAULT NULL, ordre_id INT NOT NULL, any INT NOT NULL, tasca LONGTEXT NOT NULL, data DATE NOT NULL, iva DOUBLE PRECISION NOT NULL, estat TINYINT(1) NOT NULL, forma_pagament INT NOT NULL, quilometres DOUBLE PRECISION NOT NULL, total DOUBLE PRECISION NOT NULL, observacions LONGTEXT NOT NULL, INDEX IDX_F9EBA009545317D1 (vehicle_id), INDEX IDX_F9EBA009BE5F8E09 (treballador_id), UNIQUE INDEX UNIQ_F9EBA0099291498C (ordre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE factura ADD CONSTRAINT FK_F9EBA009545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE factura ADD CONSTRAINT FK_F9EBA009BE5F8E09 FOREIGN KEY (treballador_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE factura ADD CONSTRAINT FK_F9EBA0099291498C FOREIGN KEY (ordre_id) REFERENCES ordre_reparacio (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE any');
        $this->addSql('DROP TABLE factura');
    }
}
