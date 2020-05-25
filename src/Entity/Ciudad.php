<?php

namespace App\Entity;

use App\Repository\CiudadRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CiudadRepository::class)
 */
class Ciudad
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pais;

    /**
     * @ORM\Column(type="integer")
     */
    private $latitud;

    /**
     * @ORM\Column(type="integer")
     */
    private $longitud;

    /**
     * @ORM\Column(type="integer")
     */
    private $temperatura;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getPais(): ?string
    {
        return $this->pais;
    }

    public function setPais(string $pais): self
    {
        $this->pais = $pais;

        return $this;
    }

    public function getLatitud(): ?int
    {
        return $this->latitud;
    }

    public function setLatitud(int $latitud): self
    {
        $this->latitud = $latitud;

        return $this;
    }

    public function getLongitud(): ?int
    {
        return $this->longitud;
    }

    public function setLongitud(int $longitud): self
    {
        $this->longitud = $longitud;

        return $this;
    }

    public function getTemperatura(): ?int
    {
        return $this->temperatura;
    }

    public function setTemperatura(int $temperatura): self
    {
        $this->temperatura = $temperatura;

        return $this;
    }
}
