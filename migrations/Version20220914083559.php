<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220914083559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ingredient_per_recette (id INT AUTO_INCREMENT NOT NULL, ingrediient_id INT NOT NULL, recette_id INT NOT NULL, qty INT NOT NULL, INDEX IDX_9C9C5465190C60E3 (ingrediient_id), INDEX IDX_9C9C546589312FE9 (recette_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ingredient_per_recette ADD CONSTRAINT FK_9C9C5465190C60E3 FOREIGN KEY (ingrediient_id) REFERENCES ingredient (id)');
        $this->addSql('ALTER TABLE ingredient_per_recette ADD CONSTRAINT FK_9C9C546589312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient_per_recette DROP FOREIGN KEY FK_9C9C5465190C60E3');
        $this->addSql('ALTER TABLE ingredient_per_recette DROP FOREIGN KEY FK_9C9C546589312FE9');
        $this->addSql('DROP TABLE ingredient_per_recette');
    }
}
