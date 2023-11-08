<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231108123017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add createdAt field in group & expense tables. add slug field in group table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE expense ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE amount amount INT NOT NULL');
        $this->addSql('ALTER TABLE `group` ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6DC044C5989D9B62 ON `group` (slug)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `expense` DROP created_at, CHANGE amount amount DOUBLE PRECISION NOT NULL');
        $this->addSql('DROP INDEX UNIQ_6DC044C5989D9B62 ON `group`');
        $this->addSql('ALTER TABLE `group` DROP created_at, DROP slug');
    }
}
