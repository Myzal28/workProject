<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190830154238 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cooking_class_persons (cooking_class_id INT NOT NULL, persons_id INT NOT NULL, INDEX IDX_F3CA63717F5C040C (cooking_class_id), INDEX IDX_F3CA6371FE812AD (persons_id), PRIMARY KEY(cooking_class_id, persons_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cooking_class_persons ADD CONSTRAINT FK_F3CA63717F5C040C FOREIGN KEY (cooking_class_id) REFERENCES cooking_class (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cooking_class_persons ADD CONSTRAINT FK_F3CA6371FE812AD FOREIGN KEY (persons_id) REFERENCES persons (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cooking_class ADD duration INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE cooking_class_persons');
        $this->addSql('ALTER TABLE cooking_class DROP duration');
    }
}
