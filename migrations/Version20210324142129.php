<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210324142129 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE info_transaction (id INT AUTO_INCREMENT NOT NULL, montant VARCHAR(255) NOT NULL, compte VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, user VARCHAR(255) NOT NULL, frais VARCHAR(255) NOT NULL, date_transaction DATE NOT NULL, code_transaction VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE ListTransaction');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ListTransaction (id INT AUTO_INCREMENT NOT NULL, montant VARCHAR(225) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, compte VARCHAR(225) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, type VARCHAR(225) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, user VARCHAR(225) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, frais VARCHAR(225) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, dateTransaction DATE NOT NULL, codeTransaction VARCHAR(225) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE info_transaction');
    }
}
