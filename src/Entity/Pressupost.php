<?php

namespace App\Entity;

use App\Repository\PressupostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PressupostRepository::class)
 */
class Pressupost
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
    private $any ;

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
     * @ORM\ManyToOne(targetEntity=Vehicle::class, inversedBy="pressuposts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vehicle;

    /**
     * @ORM\OneToMany(targetEntity=LiniaPressupost::class, mappedBy="pressupost")
     */
    private $liniaPressuposts;

    /**
     * @ORM\Column(type="boolean")
     */
    private $estat;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="pressuposts")
     */
    private $treballador;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @ORM\OneToOne(targetEntity=OrdreReparacio::class, mappedBy="pressupost", cascade={"persist", "remove"})
     */
    private $ordreReparacio;

    public function __construct()
    {
        $this->liniaPressuposts = new ArrayCollection();
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

    /**
     * @return Collection|LiniaPressupost[]
     */
    public function getLiniaPressuposts(): Collection
    {
        return $this->liniaPressuposts;
    }

    public function addLiniaPressupost(LiniaPressupost $liniaPressupost): self
    {
        if (!$this->liniaPressuposts->contains($liniaPressupost)) {
            $this->liniaPressuposts[] = $liniaPressupost;
            $liniaPressupost->setPressupost($this);
        }

        return $this;
    }

    public function removeLiniaPressupost(LiniaPressupost $liniaPressupost): self
    {
        if ($this->liniaPressuposts->removeElement($liniaPressupost)) {
            // set the owning side to null (unless already changed)
            if ($liniaPressupost->getPressupost() === $this) {
                $liniaPressupost->setPressupost(null);
            }
        }

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

    public function getTreballador(): ?User
    {
        return $this->treballador;
    }

    public function setTreballador(?User $treballador): self
    {
        $this->treballador = $treballador;

        return $this;
    }

    public function __toString()
    {
        
        return (string)$this->id;
    }

    public function getTotalLinies(){
        return sizeof($this->getLiniaPressuposts());
    }

    public function setTotalLinies($total){
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

    public function getOrdreReparacio(): ?OrdreReparacio
    {
        return $this->ordreReparacio;
    }

    public function setOrdreReparacio(?OrdreReparacio $ordreReparacio): self
    {
        // unset the owning side of the relation if necessary
        if ($ordreReparacio === null && $this->ordreReparacio !== null) {
            $this->ordreReparacio->setPressupost(null);
        }

        // set the owning side of the relation if necessary
        if ($ordreReparacio !== null && $ordreReparacio->getPressupost() !== $this) {
            $ordreReparacio->setPressupost($this);
        }

        $this->ordreReparacio = $ordreReparacio;

        return $this;
    }
}
