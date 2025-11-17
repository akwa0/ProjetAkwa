<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251117155723 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F1E969C5');
        $this->addSql('DROP INDEX IDX_B6BD307F1E969C5 ON message');
        $this->addSql('ALTER TABLE message ADD emetteur_id INT NOT NULL, ADD destinataire_id INT NOT NULL, DROP utilisateurs_id, CHANGE contenu contenu LONGTEXT NOT NULL, CHANGE date_envoie date_envoie DATETIME NOT NULL, CHANGE etat lu TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F79E92E8C FOREIGN KEY (emetteur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA4F84F6E FOREIGN KEY (destinataire_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F79E92E8C ON message (emetteur_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FA4F84F6E ON message (destinataire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F79E92E8C');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA4F84F6E');
        $this->addSql('DROP INDEX IDX_B6BD307F79E92E8C ON message');
        $this->addSql('DROP INDEX IDX_B6BD307FA4F84F6E ON message');
        $this->addSql('ALTER TABLE message ADD utilisateurs_id INT DEFAULT NULL, DROP emetteur_id, DROP destinataire_id, CHANGE contenu contenu VARCHAR(255) DEFAULT NULL, CHANGE date_envoie date_envoie DATE DEFAULT NULL, CHANGE lu etat TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F1E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F1E969C5 ON message (utilisateurs_id)');
    }
}
