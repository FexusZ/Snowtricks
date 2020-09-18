<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200916163217 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX user_name ON client');
        $this->addSql('DROP INDEX email ON client');
        $this->addSql('ALTER TABLE client ADD first_name VARCHAR(255) NOT NULL, ADD last_name VARCHAR(255) NOT NULL, CHANGE user_name user_name VARCHAR(255) NOT NULL UNIQUE, CHANGE email email VARCHAR(255) NOT NULL UNIQUE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP first_name, DROP last_name, CHANGE user_name user_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE UNIQUE INDEX user_name ON client (user_name)');
        $this->addSql('CREATE UNIQUE INDEX email ON client (email)');
    }
}