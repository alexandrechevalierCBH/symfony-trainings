<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231025151842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename person table, add group table and jointable group_person';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE Person RENAME `person`');
        $this->addSql('CREATE TABLE `group` (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', label VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_person (group_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', person_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_E75A09A6FE54D947 (group_id), INDEX IDX_E75A09A6217BBB47 (person_id), PRIMARY KEY(group_id, person_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE group_person ADD CONSTRAINT FK_E75A09A6FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_person ADD CONSTRAINT FK_E75A09A6217BBB47 FOREIGN KEY (person_id) REFERENCES `person` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE group_person DROP FOREIGN KEY FK_E75A09A6FE54D947');
        $this->addSql('ALTER TABLE group_person DROP FOREIGN KEY FK_E75A09A6217BBB47');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE group_person');
        $this->addSql('ALTER TABLE `person` RENAME `Person');
    }
}
