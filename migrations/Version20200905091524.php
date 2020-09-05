<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200905091524 extends AbstractMigration
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
        $this->addSql('ALTER TABLE figures ADD id_client_id INT NOT NULL');
        $this->addSql('ALTER TABLE figures ADD CONSTRAINT FK_ABF1009A99DED506 FOREIGN KEY (id_client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_ABF1009A99DED506 ON figures (id_client_id)');
        $this->addSql('ALTER TABLE image CHANGE id_figure_id id_figure_id INT NOT NULL');
        $this->addSql('ALTER TABLE video CHANGE id_figure_id id_figure_id INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client CHANGE user_name user_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE UNIQUE INDEX user_name ON client (user_name)');
        $this->addSql('CREATE UNIQUE INDEX email ON client (email)');
        $this->addSql('ALTER TABLE figures DROP FOREIGN KEY FK_ABF1009A99DED506');
        $this->addSql('DROP INDEX IDX_ABF1009A99DED506 ON figures');
        $this->addSql('ALTER TABLE figures DROP id_client_id');
        $this->addSql('ALTER TABLE image CHANGE id_figure_id id_figure_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE video CHANGE id_figure_id id_figure_id INT DEFAULT NULL');
    }
}
