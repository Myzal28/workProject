<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190825144155 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE articles (id INT AUTO_INCREMENT NOT NULL, person_create_id INT NOT NULL, service_type_id INT DEFAULT NULL, name VARCHAR(120) NOT NULL, description VARCHAR(255) DEFAULT NULL, content LONGTEXT NOT NULL, date_create DATETIME NOT NULL, INDEX IDX_BFDD316856373E29 (person_create_id), INDEX IDX_BFDD3168AC8DE0F (service_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE calendar (id INT AUTO_INCREMENT NOT NULL, service_id INT DEFAULT NULL, name VARCHAR(120) NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, commentary LONGTEXT DEFAULT NULL, day_week VARCHAR(10) NOT NULL, week INT NOT NULL, year INT NOT NULL, INDEX IDX_6EA9A146ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE calendar_persons (calendar_id INT NOT NULL, persons_id INT NOT NULL, INDEX IDX_C02BBC14A40A2C8 (calendar_id), INDEX IDX_C02BBC14FE812AD (persons_id), PRIMARY KEY(calendar_id, persons_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE collect (id INT AUTO_INCREMENT NOT NULL, person_check_id INT DEFAULT NULL, person_create_id INT NOT NULL, vehicle_id INT DEFAULT NULL, status_id INT NOT NULL, commentary LONGTEXT NOT NULL, date_register DATETIME NOT NULL, date_collect DATETIME DEFAULT NULL, INDEX IDX_A40662F4E4D520D0 (person_check_id), INDEX IDX_A40662F456373E29 (person_create_id), INDEX IDX_A40662F4545317D1 (vehicle_id), INDEX IDX_A40662F46BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, vehicle_id INT DEFAULT NULL, date_delivery DATETIME DEFAULT NULL, entity_firstname VARCHAR(120) NOT NULL, entity_lastname VARCHAR(120) NOT NULL, entity_email VARCHAR(120) NOT NULL, address VARCHAR(255) NOT NULL, country VARCHAR(120) NOT NULL, zipcode VARCHAR(60) NOT NULL, INDEX IDX_3781EC106BF700BD (status_id), INDEX IDX_3781EC10545317D1 (vehicle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE foods (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, description LONGTEXT DEFAULT NULL, ingredients LONGTEXT NOT NULL, quantity VARCHAR(255) NOT NULL, brands VARCHAR(255) NOT NULL, image_url LONGTEXT NOT NULL, code VARCHAR(255) NOT NULL, number INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inventory (id INT AUTO_INCREMENT NOT NULL, foods_id INT NOT NULL, person_create_id INT NOT NULL, status_id INT NOT NULL, collect_id INT NOT NULL, warehouse_id INT NOT NULL, delivery_id INT DEFAULT NULL, date_expire DATE NOT NULL, date_register DATETIME NOT NULL, commentary LONGTEXT NOT NULL, number INT NOT NULL, weight DOUBLE PRECISION NOT NULL, INDEX IDX_B12D4A367BC2350B (foods_id), INDEX IDX_B12D4A3656373E29 (person_create_id), INDEX IDX_B12D4A366BF700BD (status_id), INDEX IDX_B12D4A366A24B288 (collect_id), INDEX IDX_B12D4A365080ECDE (warehouse_id), INDEX IDX_B12D4A3612136921 (delivery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE persons (id INT AUTO_INCREMENT NOT NULL, service_id INT DEFAULT NULL, warehouse_id INT DEFAULT NULL, lastname VARCHAR(120) NOT NULL, firstname VARCHAR(120) NOT NULL, birthday DATE NOT NULL, password VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, phone_nbr VARCHAR(15) NOT NULL, email VARCHAR(255) NOT NULL, country VARCHAR(120) NOT NULL, zipcode VARCHAR(60) NOT NULL, city VARCHAR(60) NOT NULL, date_register DATETIME NOT NULL, date_modify DATETIME DEFAULT NULL, company VARCHAR(100) DEFAULT NULL, admin_site SMALLINT NOT NULL, volunteer SMALLINT NOT NULL, internal SMALLINT NOT NULL, client_par INT NOT NULL, client_pro INT NOT NULL, INDEX IDX_A25CC7D3ED5CA9E6 (service_id), INDEX IDX_A25CC7D35080ECDE (warehouse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipes_foods (recipes_id INT NOT NULL, foods_id INT NOT NULL, INDEX IDX_373831C8FDF2B1FA (recipes_id), INDEX IDX_373831C87BC2350B (foods_id), PRIMARY KEY(recipes_id, foods_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE services (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE signup (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, status_id INT NOT NULL, date_last_update DATETIME NOT NULL, commentary LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_4EA31EEF217BBB47 (person_id), INDEX IDX_4EA31EEF6BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(60) NOT NULL, status_type VARCHAR(5) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicles (id INT AUTO_INCREMENT NOT NULL, person_id INT DEFAULT NULL, status_id INT NOT NULL, model VARCHAR(60) NOT NULL, brand VARCHAR(60) NOT NULL, mileage DOUBLE PRECISION NOT NULL, date_register DATETIME NOT NULL, date_last_check DATE DEFAULT NULL, INDEX IDX_1FCE69FA217BBB47 (person_id), INDEX IDX_1FCE69FA6BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE warehouses (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, country VARCHAR(255) NOT NULL, zipcode VARCHAR(25) NOT NULL, address VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, latitude VARCHAR(255) NOT NULL, longitude VARCHAR(255) NOT NULL, INDEX IDX_AFE9C2B76BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD316856373E29 FOREIGN KEY (person_create_id) REFERENCES persons (id)');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD3168AC8DE0F FOREIGN KEY (service_type_id) REFERENCES services (id)');
        $this->addSql('ALTER TABLE calendar ADD CONSTRAINT FK_6EA9A146ED5CA9E6 FOREIGN KEY (service_id) REFERENCES services (id)');
        $this->addSql('ALTER TABLE calendar_persons ADD CONSTRAINT FK_C02BBC14A40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendar (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE calendar_persons ADD CONSTRAINT FK_C02BBC14FE812AD FOREIGN KEY (persons_id) REFERENCES persons (id) ON DELETE CASCADE');
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
        $this->addSql('ALTER TABLE persons ADD CONSTRAINT FK_A25CC7D3ED5CA9E6 FOREIGN KEY (service_id) REFERENCES services (id)');
        $this->addSql('ALTER TABLE persons ADD CONSTRAINT FK_A25CC7D35080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouses (id)');
        $this->addSql('ALTER TABLE recipes_foods ADD CONSTRAINT FK_373831C8FDF2B1FA FOREIGN KEY (recipes_id) REFERENCES recipes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipes_foods ADD CONSTRAINT FK_373831C87BC2350B FOREIGN KEY (foods_id) REFERENCES foods (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE signup ADD CONSTRAINT FK_4EA31EEF217BBB47 FOREIGN KEY (person_id) REFERENCES persons (id)');
        $this->addSql('ALTER TABLE signup ADD CONSTRAINT FK_4EA31EEF6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE vehicles ADD CONSTRAINT FK_1FCE69FA217BBB47 FOREIGN KEY (person_id) REFERENCES persons (id)');
        $this->addSql('ALTER TABLE vehicles ADD CONSTRAINT FK_1FCE69FA6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE warehouses ADD CONSTRAINT FK_AFE9C2B76BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE calendar_persons DROP FOREIGN KEY FK_C02BBC14A40A2C8');
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A366A24B288');
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A3612136921');
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A367BC2350B');
        $this->addSql('ALTER TABLE recipes_foods DROP FOREIGN KEY FK_373831C87BC2350B');
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD316856373E29');
        $this->addSql('ALTER TABLE calendar_persons DROP FOREIGN KEY FK_C02BBC14FE812AD');
        $this->addSql('ALTER TABLE collect DROP FOREIGN KEY FK_A40662F4E4D520D0');
        $this->addSql('ALTER TABLE collect DROP FOREIGN KEY FK_A40662F456373E29');
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A3656373E29');
        $this->addSql('ALTER TABLE signup DROP FOREIGN KEY FK_4EA31EEF217BBB47');
        $this->addSql('ALTER TABLE vehicles DROP FOREIGN KEY FK_1FCE69FA217BBB47');
        $this->addSql('ALTER TABLE recipes_foods DROP FOREIGN KEY FK_373831C8FDF2B1FA');
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD3168AC8DE0F');
        $this->addSql('ALTER TABLE calendar DROP FOREIGN KEY FK_6EA9A146ED5CA9E6');
        $this->addSql('ALTER TABLE persons DROP FOREIGN KEY FK_A25CC7D3ED5CA9E6');
        $this->addSql('ALTER TABLE collect DROP FOREIGN KEY FK_A40662F46BF700BD');
        $this->addSql('ALTER TABLE delivery DROP FOREIGN KEY FK_3781EC106BF700BD');
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A366BF700BD');
        $this->addSql('ALTER TABLE signup DROP FOREIGN KEY FK_4EA31EEF6BF700BD');
        $this->addSql('ALTER TABLE vehicles DROP FOREIGN KEY FK_1FCE69FA6BF700BD');
        $this->addSql('ALTER TABLE warehouses DROP FOREIGN KEY FK_AFE9C2B76BF700BD');
        $this->addSql('ALTER TABLE collect DROP FOREIGN KEY FK_A40662F4545317D1');
        $this->addSql('ALTER TABLE delivery DROP FOREIGN KEY FK_3781EC10545317D1');
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A365080ECDE');
        $this->addSql('ALTER TABLE persons DROP FOREIGN KEY FK_A25CC7D35080ECDE');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE calendar');
        $this->addSql('DROP TABLE calendar_persons');
        $this->addSql('DROP TABLE collect');
        $this->addSql('DROP TABLE delivery');
        $this->addSql('DROP TABLE foods');
        $this->addSql('DROP TABLE inventory');
        $this->addSql('DROP TABLE persons');
        $this->addSql('DROP TABLE recipes');
        $this->addSql('DROP TABLE recipes_foods');
        $this->addSql('DROP TABLE services');
        $this->addSql('DROP TABLE signup');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE vehicles');
        $this->addSql('DROP TABLE warehouses');
    }
}
