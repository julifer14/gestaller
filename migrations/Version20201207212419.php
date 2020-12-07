<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201207212419 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE linia_pressupost (id INT AUTO_INCREMENT NOT NULL, pressupost_id INT DEFAULT NULL, quantitat DOUBLE PRECISION NOT NULL, preu DOUBLE PRECISION NOT NULL, INDEX IDX_7AD8EDEFBA779D3A (pressupost_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pressupost (id INT AUTO_INCREMENT NOT NULL, vehicle_id INT NOT NULL, any INT NOT NULL, tasca LONGTEXT NOT NULL, data DATE NOT NULL, iva DOUBLE PRECISION NOT NULL, INDEX IDX_4957C195545317D1 (vehicle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE linia_pressupost ADD CONSTRAINT FK_7AD8EDEFBA779D3A FOREIGN KEY (pressupost_id) REFERENCES pressupost (id)');
        $this->addSql('ALTER TABLE pressupost ADD CONSTRAINT FK_4957C195545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE article ADD linia_pressupost_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66A7278590 FOREIGN KEY (linia_pressupost_id) REFERENCES linia_pressupost (id)');
        $this->addSql('CREATE INDEX IDX_23A0E66A7278590 ON article (linia_pressupost_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66A7278590');
        $this->addSql('ALTER TABLE linia_pressupost DROP FOREIGN KEY FK_7AD8EDEFBA779D3A');
        $this->addSql('DROP TABLE linia_pressupost');
        $this->addSql('DROP TABLE pressupost');
        $this->addSql('DROP INDEX IDX_23A0E66A7278590 ON article');
        $this->addSql('ALTER TABLE article DROP linia_pressupost_id');
    }
}
