<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260616205757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create element, game, pitch tables and relations and use uuid';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user ADD deleted_at DATETIME DEFAULT NULL, CHANGE id id BINARY(16) NOT NULL');

        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE element (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(50) NOT NULL, value VARCHAR(255) NOT NULL, theme VARCHAR(100) NOT NULL, age_min INT NOT NULL, age_max INT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE game (id BINARY(16) NOT NULL, date DATETIME NOT NULL, theme VARCHAR(100) NOT NULL, nb_players INT NOT NULL, status VARCHAR(50) NOT NULL, user_id BINARY(16) NOT NULL, INDEX IDX_232B318CA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE pitch (id BINARY(16) NOT NULL, player_name VARCHAR(100) NOT NULL, player_age INT NOT NULL, turn_number INT NOT NULL, duration INT NOT NULL, score INT DEFAULT NULL, game_id BINARY(16) NOT NULL, word1_id INT NOT NULL, word2_id INT NOT NULL, INDEX IDX_279FBED9E48FD905 (game_id), INDEX IDX_279FBED94586854D (word1_id), INDEX IDX_279FBED957332AA3 (word2_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');

        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE pitch ADD CONSTRAINT FK_279FBED9E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE pitch ADD CONSTRAINT FK_279FBED94586854D FOREIGN KEY (word1_id) REFERENCES element (id)');
        $this->addSql('ALTER TABLE pitch ADD CONSTRAINT FK_279FBED957332AA3 FOREIGN KEY (word2_id) REFERENCES element (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CA76ED395');
        $this->addSql('ALTER TABLE pitch DROP FOREIGN KEY FK_279FBED9E48FD905');
        $this->addSql('ALTER TABLE pitch DROP FOREIGN KEY FK_279FBED94586854D');
        $this->addSql('ALTER TABLE pitch DROP FOREIGN KEY FK_279FBED957332AA3');
        $this->addSql('DROP TABLE element');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE pitch');
        $this->addSql('ALTER TABLE user DROP deleted_at, CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
