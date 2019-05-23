<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190520164250 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE collect (id INT AUTO_INCREMENT NOT NULL, person_check_id INT DEFAULT NULL, person_create_id INT NOT NULL, vehicle_id INT DEFAULT NULL, status_id INT NOT NULL, commentary LONGTEXT NOT NULL, date_register DATETIME NOT NULL, date_collect DATETIME NOT NULL, INDEX IDX_A40662F4E4D520D0 (person_check_id), INDEX IDX_A40662F456373E29 (person_create_id), INDEX IDX_A40662F4545317D1 (vehicle_id), INDEX IDX_A40662F46BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, vehicle_id INT DEFAULT NULL, date_delivery DATETIME DEFAULT NULL, entity_firstname VARCHAR(120) NOT NULL, entity_lastname VARCHAR(120) NOT NULL, entity_email VARCHAR(120) NOT NULL, address VARCHAR(255) NOT NULL, country VARCHAR(120) NOT NULL, zipcode VARCHAR(60) NOT NULL, INDEX IDX_3781EC106BF700BD (status_id), INDEX IDX_3781EC10545317D1 (vehicle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inventory (id INT AUTO_INCREMENT NOT NULL, foods_id INT NOT NULL, person_create_id INT NOT NULL, status_id INT NOT NULL, collect_id INT NOT NULL, warehouse_id INT NOT NULL, delivery_id INT DEFAULT NULL, date_expire DATE NOT NULL, date_register DATETIME NOT NULL, commentary LONGTEXT NOT NULL, number INT NOT NULL, weight DOUBLE PRECISION NOT NULL, INDEX IDX_B12D4A367BC2350B (foods_id), INDEX IDX_B12D4A3656373E29 (person_create_id), INDEX IDX_B12D4A366BF700BD (status_id), INDEX IDX_B12D4A366A24B288 (collect_id), INDEX IDX_B12D4A365080ECDE (warehouse_id), INDEX IDX_B12D4A3612136921 (delivery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE signup (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, status_id INT NOT NULL, date_last_update DATETIME NOT NULL, commentary LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_4EA31EEF217BBB47 (person_id), INDEX IDX_4EA31EEF6BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE collect ADD CONSTRAINT FK_A40662F4E4D520D0 FOREIGN KEY (person_check_id) REFERENCES persons (id)');
        $this->addSql('ALTER TABLE collect ADD CONSTRAINT FK_A40662F456373E29 FOREIGN KEY (person_create_id) REFERENCES persons (id)');
        $this->addSql('ALTER TABLE collect ADD CONSTRAINT FK_A40662F4545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicles (id)');
        $this->addSql('ALTER TABLE collect ADD CONSTRAINT FK_A40662F46BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE delivery ADD CONSTRAINT FK_3781EC106BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE delivery ADD CONSTRAINT FK_3781EC10545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicles (id)');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A367BC2350B FOREIGN KEY (foods_id) REFERENCES foods (id)');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A3656373E29 FOREIGN KEY (person_create_id) REFERENCES persons (id)');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A366BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A366A24B288 FOREIGN KEY (collect_id) REFERENCES collect (id)');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A365080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouses (id)');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A3612136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id)');
        $this->addSql('ALTER TABLE signup ADD CONSTRAINT FK_4EA31EEF217BBB47 FOREIGN KEY (person_id) REFERENCES persons (id)');
        $this->addSql('ALTER TABLE signup ADD CONSTRAINT FK_4EA31EEF6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A366A24B288');
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A3612136921');
        $this->addSql('DROP TABLE collect');
        $this->addSql('DROP TABLE delivery');
        $this->addSql('DROP TABLE inventory');
        $this->addSql('DROP TABLE signup');
    }
}
