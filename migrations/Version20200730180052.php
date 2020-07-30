<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200730180052 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, video VARCHAR(255) NOT NULL, id_figure INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP INDEX user_name ON client');
        $this->addSql('DROP INDEX email ON client');
        $this->addSql('ALTER TABLE client CHANGE user_name user_name VARCHAR(255) NOT NULL UNIQUE, CHANGE email email VARCHAR(255) NOT NULL UNIQUE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE video');
        $this->addSql('ALTER TABLE client CHANGE user_name user_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE UNIQUE INDEX user_name ON client (user_name)');
        $this->addSql('CREATE UNIQUE INDEX email ON client (email)');
    }
}
