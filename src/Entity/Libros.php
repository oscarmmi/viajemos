<?php

namespace App\Entity;

use App\Repository\LibrosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LibrosRepository::class)
 */
class Libros
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $ISBN;

    /**
     * @ORM\Column(type="integer")
     */
    private $editoriales_id;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $titulo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sinopsis;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $n_paginas;

    public function getId(): ?int
    {
        return $this->ISBN;
    }

    public function getEditorialesId(): ?int
    {
        return $this->editoriales_id;
    }

    public function setEditorialesId(int $editoriales_id): self
    {
        $this->editoriales_id = $editoriales_id;

        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(?string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getSinopsis(): ?string
    {
        return $this->sinopsis;
    }

    public function setSinopsis(?string $sinopsis): self
    {
        $this->sinopsis = $sinopsis;

        return $this;
    }

    public function getNPaginas(): ?string
    {
        return $this->n_paginas;
    }

    public function setNPaginas(?string $n_paginas): self
    {
        $this->n_paginas = $n_paginas;

        return $this;
    }
}
