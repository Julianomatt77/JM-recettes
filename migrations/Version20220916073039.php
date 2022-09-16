<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220916073039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient_per_recette DROP FOREIGN KEY FK_9C9C546589312FE9');
        $this->addSql('ALTER TABLE ingredient_per_recette ADD CONSTRAINT FK_9C9C546589312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient_per_recette DROP FOREIGN KEY FK_9C9C546589312FE9');
        $this->addSql('ALTER TABLE ingredient_per_recette ADD CONSTRAINT FK_9C9C546589312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE SET NULL');
    }
}
