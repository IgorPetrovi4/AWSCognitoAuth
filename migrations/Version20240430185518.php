<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240430185518 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user and balance tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE balance (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, currency VARCHAR(255) NOT NULL, amount NUMERIC(18, 3) NOT NULL, INDEX IDX_ACF41FFEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE balance ADD CONSTRAINT FK_ACF41FFEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE balance DROP FOREIGN KEY FK_ACF41FFEA76ED395');
        $this->addSql('DROP TABLE balance');
        $this->addSql('DROP TABLE user');
    }
}
