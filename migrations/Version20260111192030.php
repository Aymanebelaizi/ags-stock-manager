<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260111192030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD description LONGTEXT DEFAULT NULL, ADD status VARCHAR(50) DEFAULT NULL, DROP created_at');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD12469DE2 ON product (category_id)');
        $this->addSql('ALTER TABLE purchase_request ADD estimated_price NUMERIC(10, 2) NOT NULL, ADD refusal_reason LONGTEXT DEFAULT NULL, DROP created_at, CHANGE requested_by_id requested_by_id INT DEFAULT NULL, CHANGE supplier_id supplier_id INT NOT NULL, CHANGE justification justification LONGTEXT NOT NULL, CHANGE quantity requested_quantity INT NOT NULL');
        $this->addSql('ALTER TABLE supplier ADD description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE user DROP created_at');
        $this->addSql('DROP INDEX uniq_identifier_email ON user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('DROP INDEX IDX_D34A04AD12469DE2 ON product');
        $this->addSql('ALTER TABLE product ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP description, DROP status');
        $this->addSql('ALTER TABLE purchase_request ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP estimated_price, DROP refusal_reason, CHANGE supplier_id supplier_id INT DEFAULT NULL, CHANGE requested_by_id requested_by_id INT NOT NULL, CHANGE justification justification VARCHAR(255) DEFAULT NULL, CHANGE requested_quantity quantity INT NOT NULL');
        $this->addSql('ALTER TABLE supplier DROP description');
        $this->addSql('ALTER TABLE `user` ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('DROP INDEX uniq_8d93d649e7927c74 ON `user`');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON `user` (email)');
    }
}
