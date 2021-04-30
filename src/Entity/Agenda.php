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
    private $dataHoraInici;

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

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $dataHoraFi;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $allDay;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observacions;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getdataHoraInici(): ?\DateTimeInterface
    {
        return $this->dataHoraInici;
    }

    public function setdataHoraInici(\DateTimeInterface $dataHoraInici): self
    {
        $this->dataHoraInici = $dataHoraInici;

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

    public function getTitol():string{
        return $this->getVehicle()->getMatricula()." - ".$this->getTasca()->getNom();
    }

    public function getDataHoraFi(): ?\DateTimeInterface
    {
        return $this->dataHoraFi;
    }

    public function setDataHoraFi(\DateTimeInterface $dataHoraFi): self
    {
        $this->dataHoraFi = $dataHoraFi;

        return $this;
    }

    public function getAllDay(): ?bool
    {
        return $this->allDay;
    }

    public function setAllDay(?bool $allDay): self
    {
        $this->allDay = $allDay;

        return $this;
    }

    public function getObservacions(): ?string
    {
        return $this->observacions;
    }

    public function setObservacions(?string $observacions): self
    {
        $this->observacions = $observacions;

        return $this;
    }
}
