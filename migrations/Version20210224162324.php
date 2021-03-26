<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210224162324 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agence (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, status TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_64C19AA96C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, nom_complet VARCHAR(255) NOT NULL, cni VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compte (id INT AUTO_INCREMENT NOT NULL, agence_id INT DEFAULT NULL, user_id INT DEFAULT NULL, numero INT NOT NULL, solde VARCHAR(255) NOT NULL, archivage TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_CFF65260D725330D (agence_id), INDEX IDX_CFF65260A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE depot (id INT AUTO_INCREMENT NOT NULL, compte_id INT DEFAULT NULL, user_id INT DEFAULT NULL, date DATE NOT NULL, montant VARCHAR(255) NOT NULL, INDEX IDX_47948BBCF2C56620 (compte_id), INDEX IDX_47948BBCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, compte_id INT DEFAULT NULL, client_id INT DEFAULT NULL, user_id INT DEFAULT NULL, montant INT NOT NULL, date_depot DATE NOT NULL, date_retrait DATE NOT NULL, date_annulation DATE NOT NULL, ttc VARCHAR(255) NOT NULL, frais_etat VARCHAR(255) NOT NULL, frais_system VARCHAR(255) NOT NULL, frais_envoi VARCHAR(255) NOT NULL, frais_retrait VARCHAR(255) NOT NULL, code_transaction VARCHAR(255) NOT NULL, INDEX IDX_723705D1F2C56620 (compte_id), INDEX IDX_723705D119EB6921 (client_id), INDEX IDX_723705D1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, agence_id INT DEFAULT NULL, profil_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), INDEX IDX_8D93D649D725330D (agence_id), INDEX IDX_8D93D649275ED078 (profil_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF65260D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF65260A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBCF2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1F2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D119EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF65260D725330D');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D725330D');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D119EB6921');
        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBCF2C56620');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1F2C56620');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649275ED078');
        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF65260A76ED395');
        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBCA76ED395');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1A76ED395');
        $this->addSql('DROP TABLE agence');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE compte');
        $this->addSql('DROP TABLE depot');
        $this->addSql('DROP TABLE profil');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE user');
    }
}
