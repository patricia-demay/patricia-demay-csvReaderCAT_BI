<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220308131116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE action_on_column (id INT AUTO_INCREMENT NOT NULL, display TINYINT(1) NOT NULL, filter TINYINT(1) NOT NULL, user ENUM(\'MOE\',\'CAT\'), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `column` (id INT AUTO_INCREMENT NOT NULL, header_name VARCHAR(255) NOT NULL, ordre INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entry (id INT AUTO_INCREMENT NOT NULL, file_id INT DEFAULT NULL, indice INT NOT NULL, INDEX IDX_2B219D7093CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, date_import DATE NOT NULL, draft TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE valeur (id INT AUTO_INCREMENT NOT NULL, entry_id INT DEFAULT NULL, col_id INT NOT NULL, val LONGTEXT DEFAULT NULL, INDEX IDX_1B44CD51BA364942 (entry_id), INDEX IDX_1B44CD51CC306852 (col_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE entry ADD CONSTRAINT FK_2B219D7093CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE valeur ADD CONSTRAINT FK_1B44CD51BA364942 FOREIGN KEY (entry_id) REFERENCES entry (id)');
        $this->addSql('ALTER TABLE valeur ADD CONSTRAINT FK_1B44CD51CC306852 FOREIGN KEY (col_id) REFERENCES `column` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE valeur DROP FOREIGN KEY FK_1B44CD51CC306852');
        $this->addSql('ALTER TABLE valeur DROP FOREIGN KEY FK_1B44CD51BA364942');
        $this->addSql('ALTER TABLE entry DROP FOREIGN KEY FK_2B219D7093CB796C');
        $this->addSql('DROP TABLE action_on_column');
        $this->addSql('DROP TABLE `column`');
        $this->addSql('DROP TABLE entry');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE valeur');
    }
}
