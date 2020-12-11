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
     * @ORM\Column(type="string", length=15,unique=true)
     */
    private $Matricula;

   
   

    /**
     * @ORM\Column(type="float")
     */
    private $Kilometres;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="vehicles")
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=Model::class, inversedBy="vehicles")
     */
    private $Model;

    /**
     * @ORM\OneToMany(targetEntity=Pressupost::class, mappedBy="vehicle")
     */
    private $pressuposts;

    public function __construct()
    {
        $this->pressuposts = new ArrayCollection();
    }

   

    

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

    public function getModel(): ?Model
    {
        return $this->Model;
    }

    public function setModel(?Model $Model): self
    {
        $this->Model = $Model;

        return $this;
    }

    /**
     * @return Collection|Pressupost[]
     */
    public function getPressuposts(): Collection
    {
        return $this->pressuposts;
    }

    public function addPressupost(Pressupost $pressupost): self
    {
        if (!$this->pressuposts->contains($pressupost)) {
            $this->pressuposts[] = $pressupost;
            $pressupost->setVehicle($this);
        }

        return $this;
    }

    public function removePressupost(Pressupost $pressupost): self
    {
        if ($this->pressuposts->removeElement($pressupost)) {
            // set the owning side to null (unless already changed)
            if ($pressupost->getVehicle() === $this) {
                $pressupost->setVehicle(null);
            }
        }

        return $this;
    }

    
   

    
}
