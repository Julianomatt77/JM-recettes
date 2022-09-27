<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220927065806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_recette ADD course_id INT DEFAULT NULL, ADD recette_id INT DEFAULT NULL, DROP course, DROP recette');
        $this->addSql('ALTER TABLE course_recette ADD CONSTRAINT FK_4178667C591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE course_recette ADD CONSTRAINT FK_4178667C89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_4178667C591CC992 ON course_recette (course_id)');
        $this->addSql('CREATE INDEX IDX_4178667C89312FE9 ON course_recette (recette_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_recette DROP FOREIGN KEY FK_4178667C591CC992');
        $this->addSql('ALTER TABLE course_recette DROP FOREIGN KEY FK_4178667C89312FE9');
        $this->addSql('DROP INDEX IDX_4178667C591CC992 ON course_recette');
        $this->addSql('DROP INDEX IDX_4178667C89312FE9 ON course_recette');
        $this->addSql('ALTER TABLE course_recette ADD course VARCHAR(255) DEFAULT NULL, ADD recette VARCHAR(255) DEFAULT NULL, DROP course_id, DROP recette_id');
    }
}
