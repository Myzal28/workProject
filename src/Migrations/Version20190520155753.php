<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190520155753 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE foods (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE persons (id INT AUTO_INCREMENT NOT NULL, service_id INT DEFAULT NULL, warehouse_id INT DEFAULT NULL, lastname VARCHAR(120) NOT NULL, firstname VARCHAR(120) NOT NULL, birthday DATE NOT NULL, password VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, phone_nbr VARCHAR(15) NOT NULL, email VARCHAR(255) NOT NULL, country VARCHAR(120) NOT NULL, zipcode VARCHAR(60) NOT NULL, date_register DATETIME NOT NULL, date_modify DATETIME DEFAULT NULL, company VARCHAR(100) DEFAULT NULL, admin_site SMALLINT NOT NULL, volunteer SMALLINT NOT NULL, internal SMALLINT NOT NULL, INDEX IDX_A25CC7D3ED5CA9E6 (service_id), INDEX IDX_A25CC7D35080ECDE (warehouse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicles (id INT AUTO_INCREMENT NOT NULL, person_id INT DEFAULT NULL, status_id INT NOT NULL, model VARCHAR(60) NOT NULL, brand VARCHAR(60) NOT NULL, mileage DOUBLE PRECISION NOT NULL, date_register DATETIME NOT NULL, date_last_check DATE DEFAULT NULL, INDEX IDX_1FCE69FA217BBB47 (person_id), INDEX IDX_1FCE69FA6BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE persons ADD CONSTRAINT FK_A25CC7D3ED5CA9E6 FOREIGN KEY (service_id) REFERENCES services (id)');
        $this->addSql('ALTER TABLE persons ADD CONSTRAINT FK_A25CC7D35080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouses (id)');
        $this->addSql('ALTER TABLE vehicles ADD CONSTRAINT FK_1FCE69FA217BBB47 FOREIGN KEY (person_id) REFERENCES persons (id)');
        $this->addSql('ALTER TABLE vehicles ADD CONSTRAINT FK_1FCE69FA6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vehicles DROP FOREIGN KEY FK_1FCE69FA217BBB47');
        $this->addSql('DROP TABLE foods');
        $this->addSql('DROP TABLE persons');
        $this->addSql('DROP TABLE vehicles');
    }
}
