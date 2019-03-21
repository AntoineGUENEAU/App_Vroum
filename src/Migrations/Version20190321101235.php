<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190321101235 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE result RENAME INDEX idx_136ac1139d86650f TO IDX_136AC113A76ED395');
        $this->addSql('ALTER TABLE result RENAME INDEX uniq_136ac113b748aac3 TO UNIQ_136AC113D94388BD');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE result RENAME INDEX idx_136ac113a76ed395 TO IDX_136AC1139D86650F');
        $this->addSql('ALTER TABLE result RENAME INDEX uniq_136ac113d94388bd TO UNIQ_136AC113B748AAC3');
    }
}
