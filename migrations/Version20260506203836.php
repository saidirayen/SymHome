<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260506203836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, reference VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, total DOUBLE PRECISION NOT NULL, date_creation DATETIME NOT NULL, user_id_id INT DEFAULT NULL, INDEX IDX_6EEAA67D9D86650F (user_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE ligne_commande (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, prix_unitaire DOUBLE PRECISION NOT NULL, commande_id_id INT DEFAULT NULL, meuble_id_id INT DEFAULT NULL, INDEX IDX_3170B74B462C4194 (commande_id_id), INDEX IDX_3170B74B942A73D3 (meuble_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE meuble (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, prix DOUBLE PRECISION NOT NULL, stock INT NOT NULL, image VARCHAR(255) DEFAULT NULL, categorie_id_id INT DEFAULT NULL, INDEX IDX_B758BB868A3C7387 (categorie_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(255) DEFAULT NULL, adresse LONGTEXT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74B462C4194 FOREIGN KEY (commande_id_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74B942A73D3 FOREIGN KEY (meuble_id_id) REFERENCES meuble (id)');
        $this->addSql('ALTER TABLE meuble ADD CONSTRAINT FK_B758BB868A3C7387 FOREIGN KEY (categorie_id_id) REFERENCES categorie (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D9D86650F');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74B462C4194');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74B942A73D3');
        $this->addSql('ALTER TABLE meuble DROP FOREIGN KEY FK_B758BB868A3C7387');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE ligne_commande');
        $this->addSql('DROP TABLE meuble');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
