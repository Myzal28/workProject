<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190831140555 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE guarding (id INT AUTO_INCREMENT NOT NULL, user_to_guard_id INT NOT NULL, user_guarding_id INT DEFAULT NULL, date DATETIME NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_9A0CC43E8E0CD5A (user_to_guard_id), INDEX IDX_9A0CC43E478F9AC4 (user_guarding_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE guarding ADD CONSTRAINT FK_9A0CC43E8E0CD5A FOREIGN KEY (user_to_guard_id) REFERENCES persons (id)');
        $this->addSql('ALTER TABLE guarding ADD CONSTRAINT FK_9A0CC43E478F9AC4 FOREIGN KEY (user_guarding_id) REFERENCES persons (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE guarding');
    }
}
