<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190324182110 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE war_outcome (id INT AUTO_INCREMENT NOT NULL, war_id INT NOT NULL, army_id INT NOT NULL, outcome VARCHAR(255) DEFAULT \'won\' NOT NULL, INDEX IDX_7D3821575B81B612 (war_id), INDEX IDX_7D38215718D2742D (army_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE war_outcome ADD CONSTRAINT FK_7D3821575B81B612 FOREIGN KEY (war_id) REFERENCES war (id)');
        $this->addSql('ALTER TABLE war_outcome ADD CONSTRAINT FK_7D38215718D2742D FOREIGN KEY (army_id) REFERENCES army (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE war_outcome');
    }
}
