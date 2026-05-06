<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260506204338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY `FK_6EEAA67D9D86650F`');
        $this->addSql('DROP INDEX IDX_6EEAA67D9D86650F ON commande');
        $this->addSql('ALTER TABLE commande CHANGE user_id_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DA76ED395 ON commande (user_id)');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY `FK_3170B74B462C4194`');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY `FK_3170B74B942A73D3`');
        $this->addSql('DROP INDEX IDX_3170B74B942A73D3 ON ligne_commande');
        $this->addSql('DROP INDEX IDX_3170B74B462C4194 ON ligne_commande');
        $this->addSql('ALTER TABLE ligne_commande ADD commande_id INT DEFAULT NULL, ADD meuble_id INT DEFAULT NULL, DROP commande_id_id, DROP meuble_id_id');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74B82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74BE1780C00 FOREIGN KEY (meuble_id) REFERENCES meuble (id)');
        $this->addSql('CREATE INDEX IDX_3170B74B82EA2E54 ON ligne_commande (commande_id)');
        $this->addSql('CREATE INDEX IDX_3170B74BE1780C00 ON ligne_commande (meuble_id)');
        $this->addSql('ALTER TABLE meuble DROP FOREIGN KEY `FK_B758BB868A3C7387`');
        $this->addSql('DROP INDEX IDX_B758BB868A3C7387 ON meuble');
        $this->addSql('ALTER TABLE meuble CHANGE categorie_id_id categorie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE meuble ADD CONSTRAINT FK_B758BB86BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_B758BB86BCF5E72D ON meuble (categorie_id)');
        $this->addSql('ALTER TABLE user DROP adresse');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DA76ED395');
        $this->addSql('DROP INDEX IDX_6EEAA67DA76ED395 ON commande');
        $this->addSql('ALTER TABLE commande CHANGE user_id user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT `FK_6EEAA67D9D86650F` FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67D9D86650F ON commande (user_id_id)');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74B82EA2E54');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74BE1780C00');
        $this->addSql('DROP INDEX IDX_3170B74B82EA2E54 ON ligne_commande');
        $this->addSql('DROP INDEX IDX_3170B74BE1780C00 ON ligne_commande');
        $this->addSql('ALTER TABLE ligne_commande ADD commande_id_id INT DEFAULT NULL, ADD meuble_id_id INT DEFAULT NULL, DROP commande_id, DROP meuble_id');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT `FK_3170B74B462C4194` FOREIGN KEY (commande_id_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT `FK_3170B74B942A73D3` FOREIGN KEY (meuble_id_id) REFERENCES meuble (id)');
        $this->addSql('CREATE INDEX IDX_3170B74B942A73D3 ON ligne_commande (meuble_id_id)');
        $this->addSql('CREATE INDEX IDX_3170B74B462C4194 ON ligne_commande (commande_id_id)');
        $this->addSql('ALTER TABLE meuble DROP FOREIGN KEY FK_B758BB86BCF5E72D');
        $this->addSql('DROP INDEX IDX_B758BB86BCF5E72D ON meuble');
        $this->addSql('ALTER TABLE meuble CHANGE categorie_id categorie_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE meuble ADD CONSTRAINT `FK_B758BB868A3C7387` FOREIGN KEY (categorie_id_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_B758BB868A3C7387 ON meuble (categorie_id_id)');
        $this->addSql('ALTER TABLE user ADD adresse LONGTEXT DEFAULT NULL');
    }
}
