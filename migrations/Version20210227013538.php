<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210227013538 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agence ADD CONSTRAINT FK_64C19AA9F2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19AA9F2C56620 ON agence (compte_id)');
        $this->addSql('ALTER TABLE transaction ADD montant INT NOT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1659D30DE FOREIGN KEY (user_depot_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1D99F8396 FOREIGN KEY (user_retrait_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D11171DC20 FOREIGN KEY (client_envoi_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1EEAC783B FOREIGN KEY (client_retrait_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_723705D1659D30DE ON transaction (user_depot_id)');
        $this->addSql('CREATE INDEX IDX_723705D1D99F8396 ON transaction (user_retrait_id)');
        $this->addSql('CREATE INDEX IDX_723705D11171DC20 ON transaction (client_envoi_id)');
        $this->addSql('CREATE INDEX IDX_723705D1EEAC783B ON transaction (client_retrait_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agence DROP FOREIGN KEY FK_64C19AA9F2C56620');
        $this->addSql('DROP INDEX UNIQ_64C19AA9F2C56620 ON agence');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1659D30DE');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1D99F8396');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D11171DC20');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1EEAC783B');
        $this->addSql('DROP INDEX IDX_723705D1659D30DE ON transaction');
        $this->addSql('DROP INDEX IDX_723705D1D99F8396 ON transaction');
        $this->addSql('DROP INDEX IDX_723705D11171DC20 ON transaction');
        $this->addSql('DROP INDEX IDX_723705D1EEAC783B ON transaction');
        $this->addSql('ALTER TABLE transaction DROP montant');
    }
}
