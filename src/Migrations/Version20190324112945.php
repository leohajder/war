<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190324112945 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE battle_outcome ADD battle_id INT NOT NULL, ADD soldier_id INT NOT NULL');
        $this->addSql('ALTER TABLE battle_outcome ADD CONSTRAINT FK_FA23D9A1C9732719 FOREIGN KEY (battle_id) REFERENCES battle (id)');
        $this->addSql('ALTER TABLE battle_outcome ADD CONSTRAINT FK_FA23D9A1A38C1700 FOREIGN KEY (soldier_id) REFERENCES soldier (id)');
        $this->addSql('CREATE INDEX IDX_FA23D9A1C9732719 ON battle_outcome (battle_id)');
        $this->addSql('CREATE INDEX IDX_FA23D9A1A38C1700 ON battle_outcome (soldier_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE battle_outcome DROP FOREIGN KEY FK_FA23D9A1C9732719');
        $this->addSql('ALTER TABLE battle_outcome DROP FOREIGN KEY FK_FA23D9A1A38C1700');
        $this->addSql('DROP INDEX IDX_FA23D9A1C9732719 ON battle_outcome');
        $this->addSql('DROP INDEX IDX_FA23D9A1A38C1700 ON battle_outcome');
        $this->addSql('ALTER TABLE battle_outcome DROP battle_id, DROP soldier_id');
    }
}
