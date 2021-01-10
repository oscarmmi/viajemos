<?php

namespace App\Entity;

use App\Repository\EditorialesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EditorialesRepository::class)
 */
class Editoriales
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $sede;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getSede(): ?string
    {
        return $this->sede;
    }

    public function setSede(?string $sede): self
    {
        $this->sede = $sede;

        return $this;
    }
}
