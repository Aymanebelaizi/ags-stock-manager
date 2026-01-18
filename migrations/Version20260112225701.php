<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260112225701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase_request DROP FOREIGN KEY FK_204D45E62ADD6D8C');
        $this->addSql('DROP INDEX IDX_204D45E62ADD6D8C ON purchase_request');
        $this->addSql('ALTER TABLE purchase_request DROP supplier_id, CHANGE status status VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE supplier ADD address VARCHAR(255) DEFAULT NULL, CHANGE phone phone VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP created_at');
        $this->addSql('DROP INDEX uniq_identifier_email ON user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase_request ADD supplier_id INT DEFAULT NULL, CHANGE status status VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE purchase_request ADD CONSTRAINT FK_204D45E62ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('CREATE INDEX IDX_204D45E62ADD6D8C ON purchase_request (supplier_id)');
        $this->addSql('ALTER TABLE supplier DROP address, CHANGE phone phone VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE `user` ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('DROP INDEX uniq_8d93d649e7927c74 ON `user`');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON `user` (email)');
    }
}
