<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190520170049 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE articles (id INT AUTO_INCREMENT NOT NULL, person_create_id INT NOT NULL, name VARCHAR(120) NOT NULL, description VARCHAR(255) DEFAULT NULL, content LONGTEXT NOT NULL, date_create DATETIME NOT NULL, INDEX IDX_BFDD316856373E29 (person_create_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE calendar (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, commentary LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE calendar_persons (calendar_id INT NOT NULL, persons_id INT NOT NULL, INDEX IDX_C02BBC14A40A2C8 (calendar_id), INDEX IDX_C02BBC14FE812AD (persons_id), PRIMARY KEY(calendar_id, persons_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipes_foods (recipes_id INT NOT NULL, foods_id INT NOT NULL, INDEX IDX_373831C8FDF2B1FA (recipes_id), INDEX IDX_373831C87BC2350B (foods_id), PRIMARY KEY(recipes_id, foods_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD316856373E29 FOREIGN KEY (person_create_id) REFERENCES persons (id)');
        $this->addSql('ALTER TABLE calendar_persons ADD CONSTRAINT FK_C02BBC14A40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendar (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE calendar_persons ADD CONSTRAINT FK_C02BBC14FE812AD FOREIGN KEY (persons_id) REFERENCES persons (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipes_foods ADD CONSTRAINT FK_373831C8FDF2B1FA FOREIGN KEY (recipes_id) REFERENCES recipes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipes_foods ADD CONSTRAINT FK_373831C87BC2350B FOREIGN KEY (foods_id) REFERENCES foods (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE calendar_persons DROP FOREIGN KEY FK_C02BBC14A40A2C8');
        $this->addSql('ALTER TABLE recipes_foods DROP FOREIGN KEY FK_373831C8FDF2B1FA');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE calendar');
        $this->addSql('DROP TABLE calendar_persons');
        $this->addSql('DROP TABLE recipes');
        $this->addSql('DROP TABLE recipes_foods');
    }
}
