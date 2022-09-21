<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220921072716 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_recette DROP FOREIGN KEY FK_4178667C89312FE9');
        $this->addSql('ALTER TABLE course_recette DROP FOREIGN KEY FK_4178667C591CC992');
        $this->addSql('DROP TABLE course_recette');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course_recette (course_id INT NOT NULL, recette_id INT NOT NULL, INDEX IDX_4178667C591CC992 (course_id), INDEX IDX_4178667C89312FE9 (recette_id), PRIMARY KEY(course_id, recette_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE course_recette ADD CONSTRAINT FK_4178667C89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course_recette ADD CONSTRAINT FK_4178667C591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
    }
}
