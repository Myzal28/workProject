<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190830112949 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE anti_waste_advice_persons (anti_waste_advice_id INT NOT NULL, persons_id INT NOT NULL, INDEX IDX_7B01D0031D968383 (anti_waste_advice_id), INDEX IDX_7B01D003FE812AD (persons_id), PRIMARY KEY(anti_waste_advice_id, persons_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE anti_waste_advice_persons ADD CONSTRAINT FK_7B01D0031D968383 FOREIGN KEY (anti_waste_advice_id) REFERENCES anti_waste_advice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE anti_waste_advice_persons ADD CONSTRAINT FK_7B01D003FE812AD FOREIGN KEY (persons_id) REFERENCES persons (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE anti_waste_advice_persons');
    }
}
