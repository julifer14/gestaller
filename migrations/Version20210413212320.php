<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210413212320 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agenda (id INT AUTO_INCREMENT NOT NULL, vehicle_id INT NOT NULL, treballador_id INT NOT NULL, tasca_id INT NOT NULL, data_hora DATETIME NOT NULL, INDEX IDX_2CEDC877545317D1 (vehicle_id), INDEX IDX_2CEDC877BE5F8E09 (treballador_id), INDEX IDX_2CEDC8772947F9E4 (tasca_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tasca (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, descripcio VARCHAR(255) NOT NULL, temps DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agenda ADD CONSTRAINT FK_2CEDC877545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE agenda ADD CONSTRAINT FK_2CEDC877BE5F8E09 FOREIGN KEY (treballador_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE agenda ADD CONSTRAINT FK_2CEDC8772947F9E4 FOREIGN KEY (tasca_id) REFERENCES tasca (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agenda DROP FOREIGN KEY FK_2CEDC8772947F9E4');
        $this->addSql('DROP TABLE agenda');
        $this->addSql('DROP TABLE tasca');
    }
}
