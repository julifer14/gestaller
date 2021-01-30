<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210130213341 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ordre_reparacio ADD vehicle_id INT NOT NULL, ADD treballador_id INT NOT NULL, ADD pressupost_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ordre_reparacio ADD CONSTRAINT FK_EF7609A2545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE ordre_reparacio ADD CONSTRAINT FK_EF7609A2BE5F8E09 FOREIGN KEY (treballador_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ordre_reparacio ADD CONSTRAINT FK_EF7609A2BA779D3A FOREIGN KEY (pressupost_id) REFERENCES pressupost (id)');
        $this->addSql('CREATE INDEX IDX_EF7609A2545317D1 ON ordre_reparacio (vehicle_id)');
        $this->addSql('CREATE INDEX IDX_EF7609A2BE5F8E09 ON ordre_reparacio (treballador_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EF7609A2BA779D3A ON ordre_reparacio (pressupost_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ordre_reparacio DROP FOREIGN KEY FK_EF7609A2545317D1');
        $this->addSql('ALTER TABLE ordre_reparacio DROP FOREIGN KEY FK_EF7609A2BE5F8E09');
        $this->addSql('ALTER TABLE ordre_reparacio DROP FOREIGN KEY FK_EF7609A2BA779D3A');
        $this->addSql('DROP INDEX IDX_EF7609A2545317D1 ON ordre_reparacio');
        $this->addSql('DROP INDEX IDX_EF7609A2BE5F8E09 ON ordre_reparacio');
        $this->addSql('DROP INDEX UNIQ_EF7609A2BA779D3A ON ordre_reparacio');
        $this->addSql('ALTER TABLE ordre_reparacio DROP vehicle_id, DROP treballador_id, DROP pressupost_id');
    }
}
