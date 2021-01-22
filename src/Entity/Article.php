<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descripcio;

    /**
     * @ORM\Column(type="float")
     */
    private $preu;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $iva;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stock;

    /**
     * @ORM\ManyToOne(targetEntity=Categoria::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categoria;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $referencia;

    /**
     * @ORM\OneToMany(targetEntity=LiniaPressupost::class, mappedBy="article")
     */
    private $liniaPressuposts;

    public function __construct()
    {
        $this->liniaPressuposts = new ArrayCollection();
    }

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescripcio(): ?string
    {
        return $this->descripcio;
    }

    public function setDescripcio(string $descripcio): self
    {
        $this->descripcio = $descripcio;

        return $this;
    }

    public function getPreu(): ?float
    {
        return $this->preu;
    }

    public function setPreu(float $preu): self
    {
        $this->preu = $preu;

        return $this;
    }

    public function getIva(): ?float
    {
        return $this->iva;
    }

    public function setIva(?float $iva): self
    {
        $this->iva = $iva;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    

    public function getReferencia(): ?string
    {
        return $this->referencia;
    }

    public function setReferencia(?string $referencia): self
    {
        $this->referencia = $referencia;

        return $this;
    }

    public function __toString(){
        return $this->getNom();
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
            $liniaPressupost->setArticle($this);
        }

        return $this;
    }

    public function removeLiniaPressupost(LiniaPressupost $liniaPressupost): self
    {
        if ($this->liniaPressuposts->removeElement($liniaPressupost)) {
            // set the owning side to null (unless already changed)
            if ($liniaPressupost->getArticle() === $this) {
                $liniaPressupost->setArticle(null);
            }
        }

        return $this;
    }
}
