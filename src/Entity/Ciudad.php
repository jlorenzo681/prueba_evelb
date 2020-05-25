<?php

namespace App\Entity;

use App\Repository\CiudadRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CiudadRepository::class)
 * @UniqueEntity(
 *     fields={"latitud", "longitud"},
 *     message="Ya se ha guardado la ciudad"
 * )
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
     * @ORM\Column(type="string", length=255)
     */
    private $provincia;

    /**
     * @ORM\Column(type="float")
     */
    private $norte;

    /**
     * @ORM\Column(type="float")
     */
    private $sur;

    /**
     * @ORM\Column(type="float")
     */
    private $este;

    /**
     * @ORM\Column(type="float")
     */
    private $oeste;

    /**
     * @ORM\Column(name="latitud", type="integer")
     */
    private $latitud;

    /**
     * @ORM\Column(name="longitud", type="integer")
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

    /**
     * @return mixed
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * @param mixed $provincia
     */
    public function setProvincia($provincia): void
    {
        $this->provincia = $provincia;
    }

    /**
     * @return mixed
     */
    public function getNorte()
    {
        return $this->norte;
    }

    /**
     * @param mixed $norte
     */
    public function setNorte($norte): void
    {
        $this->norte = $norte;
    }

    /**
     * @return mixed
     */
    public function getSur()
    {
        return $this->sur;
    }

    /**
     * @param mixed $sur
     */
    public function setSur($sur): void
    {
        $this->sur = $sur;
    }

    /**
     * @return mixed
     */
    public function getEste()
    {
        return $this->este;
    }

    /**
     * @param mixed $este
     */
    public function setEste($este): void
    {
        $this->este = $este;
    }

    /**
     * @return mixed
     */
    public function getOeste()
    {
        return $this->oeste;
    }

    /**
     * @param mixed $oeste
     */
    public function setOeste($oeste): void
    {
        $this->oeste = $oeste;
    }
}
