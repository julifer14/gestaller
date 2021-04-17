<?php

namespace App\Entity;

use App\Repository\AgendaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AgendaRepository::class)
 */
class Agenda
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dataHora;

    /**
     * @ORM\ManyToOne(targetEntity=Vehicle::class, inversedBy="agendas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vehicle;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="agendas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $treballador;

    /**
     * @ORM\ManyToOne(targetEntity=Tasca::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $tasca;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $estat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDataHora(): ?\DateTimeInterface
    {
        return $this->dataHora;
    }

    public function setDataHora(\DateTimeInterface $dataHora): self
    {
        $this->dataHora = $dataHora;

        return $this;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): self
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    public function getTreballador(): ?User
    {
        return $this->treballador;
    }

    public function setTreballador(?User $treballador): self
    {
        $this->treballador = $treballador;

        return $this;
    }

    public function getTasca(): ?Tasca
    {
        return $this->tasca;
    }

    public function setTasca(?Tasca $tasca): self
    {
        $this->tasca = $tasca;

        return $this;
    }

    public function getEstat(): ?int
    {
        return $this->estat;
    }

    public function setEstat(?int $estat): self
    {
        $this->estat = $estat;

        return $this;
    }
}
