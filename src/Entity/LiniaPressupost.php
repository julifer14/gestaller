<?php

namespace App\Entity;

use App\Repository\LiniaPressupostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LiniaPressupostRepository::class)
 */
class LiniaPressupost
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="liniaPressupost")
     */
    private $article;

    /**
     * @ORM\Column(type="float")
     */
    private $quantitat;

    /**
     * @ORM\Column(type="float")
     */
    private $preu;

    /**
     * @ORM\ManyToOne(targetEntity=Pressupost::class, inversedBy="liniaPressuposts")
     */
    private $pressupost;

    public function __construct()
    {
        $this->article = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticle(): Collection
    {
        return $this->article;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->article->contains($article)) {
            $this->article[] = $article;
            $article->setLiniaPressupost($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->article->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getLiniaPressupost() === $this) {
                $article->setLiniaPressupost(null);
            }
        }

        return $this;
    }

    public function getQuantitat(): ?float
    {
        return $this->quantitat;
    }

    public function setQuantitat(float $quantitat): self
    {
        $this->quantitat = $quantitat;

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

    public function getPressupost(): ?Pressupost
    {
        return $this->pressupost;
    }

    public function setPressupost(?Pressupost $pressupost): self
    {
        $this->pressupost = $pressupost;

        return $this;
    }
}
