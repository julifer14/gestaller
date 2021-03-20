<?php

namespace App\Entity;

use App\Repository\OrdreReparacioRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrdreReparacioRepository::class)
 */
class OrdreReparacio
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
    private $any;

    /**
     * @ORM\Column(type="text")
     */
    private $tasca;

    /**
     * @ORM\Column(type="date")
     */
    private $dataCreacio;

    /**
     * @ORM\Column(type="float")
     */
    private $iva;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dataEntrada;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dataSortida;

    /**
     * @ORM\Column(type="boolean")
     */
    private $autoritzacio;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $combustible;

    /**
     * @ORM\Column(type="float")
     */
    private $quilometres;

    /**
     * @ORM\ManyToOne(targetEntity=Vehicle::class, inversedBy="ordreReparacios")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vehicle;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $treballador;

    /**
     * @ORM\OneToOne(targetEntity=Pressupost::class, inversedBy="ordreReparacio", cascade={"persist", "remove"})
     */
    private $pressupost;

    /**
     * @ORM\Column(type="boolean")
     */
    private $estat;

    /**
     * @ORM\OneToOne(targetEntity=Factura::class,inversedBy="ordre", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $factura;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAny(): ?int
    {
        return $this->any;
    }

    public function setAny(int $any): self
    {
        $this->any = $any;

        return $this;
    }

    public function getTasca(): ?string
    {
        return $this->tasca;
    }

    public function setTasca(string $tasca): self
    {
        $this->tasca = $tasca;

        return $this;
    }

    public function getDataCreacio(): ?\DateTimeInterface
    {
        return $this->dataCreacio;
    }

    public function setDataCreacio(\DateTimeInterface $dataCreacio): self
    {
        $this->dataCreacio = $dataCreacio;

        return $this;
    }

    public function getIva(): ?float
    {
        return $this->iva;
    }

    public function setIva(float $iva): self
    {
        $this->iva = $iva;

        return $this;
    }

   

    public function getDataEntrada(): ?\DateTimeInterface
    {
        return $this->dataEntrada;
    }

    public function setDataEntrada(\DateTimeInterface $dataEntrada): self
    {
        $this->dataEntrada = $dataEntrada;

        return $this;
    }

    public function getDataSortida(): ?\DateTimeInterface
    {
        return $this->dataSortida;
    }

    public function setDataSortida(?\DateTimeInterface $dataSortida): self
    {
        $this->dataSortida = $dataSortida;

        return $this;
    }

    public function getAutoritzacio(): ?bool
    {
        return $this->autoritzacio;
    }

    public function setAutoritzacio(bool $autoritzacio): self
    {
        $this->autoritzacio = $autoritzacio;

        return $this;
    }

    public function getCombustible(): ?float
    {
        return $this->combustible;
    }

    public function setCombustible(?float $combustible): self
    {
        $this->combustible = $combustible;

        return $this;
    }

    public function getQuilometres(): ?float
    {
        return $this->quilometres;
    }

    public function setQuilometres(float $quilometres): self
    {
        $this->quilometres = $quilometres;

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

    public function getPressupost(): ?Pressupost
    {
        return $this->pressupost;
    }

    public function setPressupost(?Pressupost $pressupost): self
    {
        $this->pressupost = $pressupost;

        return $this;
    }

    public function getEstat()
    {
        return $this->estat;
    }

    public function setEstat($estat): self
    {
        $this->estat = $estat;

        return $this;
    }

    public function esFacturable(){
        
        return ($this->getAutoritzacio() && $this->getEstat() && !$this->getFactura());
    }



    public function getFactura(): ?Factura
    {
        return $this->factura;
    }

    public function setFactura(?Factura $factura): self
    {
        $this->factura = $factura;

        return $this;
    }

    public function __toString()
    {
        return "(".$this->getId().")";
    }

    
}
