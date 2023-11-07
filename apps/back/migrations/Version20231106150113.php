<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231106150113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Allow multiple expenses per person';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE expense DROP INDEX UNIQ_2D3A8DA6C17AD9A9, ADD INDEX IDX_2D3A8DA6C17AD9A9 (payer_id)');
        $this->addSql('ALTER TABLE person RENAME INDEX uniq_3370d440e7927c74 TO UNIQ_34DCD176E7927C74');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `expense` DROP INDEX IDX_2D3A8DA6C17AD9A9, ADD UNIQUE INDEX UNIQ_2D3A8DA6C17AD9A9 (payer_id)');
        $this->addSql('ALTER TABLE `person` RENAME INDEX uniq_34dcd176e7927c74 TO UNIQ_3370D440E7927C74');
    }
}
