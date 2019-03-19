<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190319144536 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE result (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, serie_id_id INT DEFAULT NULL, result INT DEFAULT NULL, date DATETIME DEFAULT NULL, INDEX IDX_136AC1139D86650F (user_id_id), UNIQUE INDEX UNIQ_136AC113B748AAC3 (serie_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1139D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113B748AAC3 FOREIGN KEY (serie_id_id) REFERENCES serie (id)');
        $this->addSql('DROP TABLE serie_user');
        $this->addSql('ALTER TABLE serie ADD libelle VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE serie_user (serie_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_56F6C27BD94388BD (serie_id), INDEX IDX_56F6C27BA76ED395 (user_id), PRIMARY KEY(serie_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE serie_user ADD CONSTRAINT FK_56F6C27BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE serie_user ADD CONSTRAINT FK_56F6C27BD94388BD FOREIGN KEY (serie_id) REFERENCES serie (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE result');
        $this->addSql('ALTER TABLE serie DROP libelle');
    }
}
