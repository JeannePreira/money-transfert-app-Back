<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210224181046 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction CHANGE date_depot date_depot DATE DEFAULT NULL, CHANGE date_retrait date_retrait DATE DEFAULT NULL, CHANGE date_annulation date_annulation DATE DEFAULT NULL, CHANGE ttc ttc VARCHAR(255) DEFAULT NULL, CHANGE frais_etat frais_etat VARCHAR(255) DEFAULT NULL, CHANGE frais_system frais_system VARCHAR(255) DEFAULT NULL, CHANGE frais_envoi frais_envoi VARCHAR(255) DEFAULT NULL, CHANGE frais_retrait frais_retrait VARCHAR(255) DEFAULT NULL, CHANGE code_transaction code_transaction VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction CHANGE date_depot date_depot DATE NOT NULL, CHANGE date_retrait date_retrait DATE NOT NULL, CHANGE date_annulation date_annulation DATE NOT NULL, CHANGE ttc ttc VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE frais_etat frais_etat VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE frais_system frais_system VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE frais_envoi frais_envoi VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE frais_retrait frais_retrait VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE code_transaction code_transaction VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
