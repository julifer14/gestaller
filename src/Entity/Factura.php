<?php

namespace App\Entity;

use App\Repository\FacturaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FacturaRepository::class)
 */
class Factura
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
    private $data;

    /**
     * @ORM\Column(type="float")
     */
    private $iva;

    /**
     * @ORM\ManyToOne(targetEntity=Vehicle::class, inversedBy="facturas")
     */
    private $vehicle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $estat;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $formaPagament;

    /**
     * @ORM\Column(type="float")
     */
    private $quilometres;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $treballador;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

   

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observacions;

    /**
     * @ORM\OneToOne(targetEntity=OrdreReparacio::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $ordre;

    /**
     * @ORM\OneToMany(targetEntity=LiniaFactura::class, mappedBy="factura", orphanRemoval=true)
     */
    private $liniaFacturas;

    public function __construct()
    {
        $this->liniaFacturas = new ArrayCollection();
    }

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

    public function getData(): ?\DateTimeInterface
    {
        return $this->data;
    }

    public function setData(\DateTimeInterface $data): self
    {
        $this->data = $data;

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

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): self
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    public function getEstat(): ?bool
    {
        return $this->estat;
    }

    public function setEstat(bool $estat): self
    {
        $this->estat = $estat;

        return $this;
    }

    public function getFormaPagament(): ?int
    {
        return $this->formaPagament;
    }

    public function setFormaPagament(int $formaPagament): self
    {
        $this->formaPagament = $formaPagament;

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

    public function getTreballador(): ?User
    {
        return $this->treballador;
    }

    public function setTreballador(?User $treballador): self
    {
        $this->treballador = $treballador;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

   

   

    public function getObservacions(): ?string
    {
        return $this->observacions;
    }

    public function setObservacions(string $observacions): self
    {
        $this->observacions = $observacions;

        return $this;
    }

    public function getOrdre(): ?OrdreReparacio
    {
        return $this->ordre;
    }

    public function setOrdre(OrdreReparacio $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * @return Collection|LiniaFactura[]
     */
    public function getLiniaFacturas(): Collection
    {
        return $this->liniaFacturas;
    }

    public function addLiniaFactura(LiniaFactura $liniaFactura): self
    {
        if (!$this->liniaFacturas->contains($liniaFactura)) {
            $this->liniaFacturas[] = $liniaFactura;
            $liniaFactura->setFactura($this);
        }

        return $this;
    }

    public function removeLiniaFactura(LiniaFactura $liniaFactura): self
    {
        if ($this->liniaFacturas->removeElement($liniaFactura)) {
            // set the owning side to null (unless already changed)
            if ($liniaFactura->getFactura() === $this) {
                $liniaFactura->setFactura(null);
            }
        }

        return $this;
    }


    public function getTotalLinies(){
        return sizeof($this->getLiniaFacturas());
    }

    public function setTotalLinies($total){
    }
}
