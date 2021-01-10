<?php

namespace App\Entity;

use App\Repository\AutoresHasLibrosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AutoresHasLibrosRepository::class)
 */
class AutoresHasLibros
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $autores_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $libros_ISBN;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAutoresId(): ?int
    {
        return $this->autores_id;
    }

    public function setAutoresId(int $autores_id): self
    {
        $this->autores_id = $autores_id;

        return $this;
    }

    public function getLibrosISBN(): ?int
    {
        return $this->libros_ISBN;
    }

    public function setLibrosISBN(int $libros_ISBN): self
    {
        $this->libros_ISBN = $libros_ISBN;

        return $this;
    }
}
