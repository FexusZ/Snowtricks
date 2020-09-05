<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200905091215 extends AbstractMigration
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
        $this->addSql('ALTER TABLE client CHANGE user_name user_name VARCHAR(255) NOT NULL UNIQUE, CHANGE email email VARCHAR(255) NOT NULL UNIQUE');
        $this->addSql('ALTER TABLE image ADD id_figure_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F85F5AD92 FOREIGN KEY (id_figure_id) REFERENCES figures (id)');
        $this->addSql('CREATE INDEX IDX_C53D045F85F5AD92 ON image (id_figure_id)');
        $this->addSql('ALTER TABLE video ADD id_figure_id INT DEFAULT NULL, DROP id_figure');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C85F5AD92 FOREIGN KEY (id_figure_id) REFERENCES figures (id)');
        $this->addSql('CREATE INDEX IDX_7CC7DA2C85F5AD92 ON video (id_figure_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client CHANGE user_name user_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE UNIQUE INDEX user_name ON client (user_name)');
        $this->addSql('CREATE UNIQUE INDEX email ON client (email)');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F85F5AD92');
        $this->addSql('DROP INDEX IDX_C53D045F85F5AD92 ON image');
        $this->addSql('ALTER TABLE image DROP id_figure_id');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2C85F5AD92');
        $this->addSql('DROP INDEX IDX_7CC7DA2C85F5AD92 ON video');
        $this->addSql('ALTER TABLE video ADD id_figure INT NOT NULL, DROP id_figure_id');
    }
}
