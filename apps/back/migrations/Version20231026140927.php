<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231026140927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Expense Table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE `expense` (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', payer_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', group_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', description VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_2D3A8DA6C17AD9A9 (payer_id), INDEX IDX_2D3A8DA6FE54D947 (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expense_person (expense_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', person_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_F2730063F395DB7B (expense_id), INDEX IDX_F2730063217BBB47 (person_id), PRIMARY KEY(expense_id, person_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `expense` ADD CONSTRAINT FK_2D3A8DA6C17AD9A9 FOREIGN KEY (payer_id) REFERENCES `person` (id)');
        $this->addSql('ALTER TABLE `expense` ADD CONSTRAINT FK_2D3A8DA6FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE expense_person ADD CONSTRAINT FK_F2730063F395DB7B FOREIGN KEY (expense_id) REFERENCES `expense` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE expense_person ADD CONSTRAINT FK_F2730063217BBB47 FOREIGN KEY (person_id) REFERENCES `person` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person RENAME INDEX uniq_3370d440e7927c74 TO UNIQ_34DCD176E7927C74');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `expense` DROP FOREIGN KEY FK_2D3A8DA6C17AD9A9');
        $this->addSql('ALTER TABLE `expense` DROP FOREIGN KEY FK_2D3A8DA6FE54D947');
        $this->addSql('ALTER TABLE expense_person DROP FOREIGN KEY FK_F2730063F395DB7B');
        $this->addSql('ALTER TABLE expense_person DROP FOREIGN KEY FK_F2730063217BBB47');
        $this->addSql('DROP TABLE `expense`');
        $this->addSql('DROP TABLE expense_person');
        $this->addSql('ALTER TABLE `person` RENAME INDEX uniq_34dcd176e7927c74 TO UNIQ_3370D440E7927C74');
    }
}
