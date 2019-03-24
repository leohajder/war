<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190324105539 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE army ADD war_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE army ADD CONSTRAINT FK_C212F365B81B612 FOREIGN KEY (war_id) REFERENCES war (id)');
        $this->addSql('CREATE INDEX IDX_C212F365B81B612 ON army (war_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE army DROP FOREIGN KEY FK_C212F365B81B612');
        $this->addSql('DROP INDEX IDX_C212F365B81B612 ON army');
        $this->addSql('ALTER TABLE army DROP war_id');
    }
}
