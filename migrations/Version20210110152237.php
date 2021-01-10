<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210110152237 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE autores (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(45) DEFAULT NULL, apellidos VARCHAR(45) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE autores_has_libros (id INT AUTO_INCREMENT NOT NULL, autores_id INT NOT NULL, libros_isbn INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE editoriales (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(45) DEFAULT NULL, sede VARCHAR(45) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE libros (isbn INT AUTO_INCREMENT NOT NULL, editoriales_id INT NOT NULL, titulo VARCHAR(45) DEFAULT NULL, sinopsis LONGTEXT DEFAULT NULL, n_paginas VARCHAR(45) DEFAULT NULL, PRIMARY KEY(isbn)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE autores');
        $this->addSql('DROP TABLE autores_has_libros');
        $this->addSql('DROP TABLE editoriales');
        $this->addSql('DROP TABLE libros');
    }
}
