<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201226214429 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pressupost ADD treballador_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pressupost ADD CONSTRAINT FK_4957C195BE5F8E09 FOREIGN KEY (treballador_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_4957C195BE5F8E09 ON pressupost (treballador_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pressupost DROP FOREIGN KEY FK_4957C195BE5F8E09');
        $this->addSql('DROP INDEX IDX_4957C195BE5F8E09 ON pressupost');
        $this->addSql('ALTER TABLE pressupost DROP treballador_id');
    }
}
