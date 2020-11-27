<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VehicleRepository::class)
 */
class Vehicle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $Matricula;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Marca;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Model;

    /**
     * @ORM\Column(type="float")
     */
    private $Kilometres;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="vehicles")
     */
    private $client;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricula(): ?string
    {
        return $this->Matricula;
    }

    public function setMatricula(string $Matricula): self
    {
        $this->Matricula = $Matricula;

        return $this;
    }

    public function getMarca(): ?string
    {
        return $this->Marca;
    }

    public function setMarca(string $Marca): self
    {
        $this->Marca = $Marca;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->Model;
    }

    public function setModel(string $Model): self
    {
        $this->Model = $Model;

        return $this;
    }

    public function getKilometres(): ?float
    {
        return $this->Kilometres;
    }

    public function setKilometres(float $Kilometres): self
    {
        $this->Kilometres = $Kilometres;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    
}
