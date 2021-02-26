<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210226213943 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE linia_factura (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, factura_id INT NOT NULL, quantitat DOUBLE PRECISION NOT NULL, preu DOUBLE PRECISION NOT NULL, descompte DOUBLE PRECISION NOT NULL, INDEX IDX_A20FEAC07294869C (article_id), INDEX IDX_A20FEAC0F04F795F (factura_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE linia_factura ADD CONSTRAINT FK_A20FEAC07294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE linia_factura ADD CONSTRAINT FK_A20FEAC0F04F795F FOREIGN KEY (factura_id) REFERENCES factura (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE linia_factura');
    }
}
