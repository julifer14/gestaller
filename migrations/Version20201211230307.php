<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201211230307 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C74404557F8F253B ON client (dni)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_70A01136C6E55B5 ON marca (nom)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1B80E48615DF1885 ON vehicle (matricula)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_C74404557F8F253B ON client');
        $this->addSql('DROP INDEX UNIQ_70A01136C6E55B5 ON marca');
        $this->addSql('DROP INDEX UNIQ_1B80E48615DF1885 ON vehicle');
    }
}
