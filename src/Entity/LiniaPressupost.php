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

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="liniaPressuposts")
     */
    private $article;

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function __toString()
    {
        return $this->id;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getTotalLinia(){
        return ($this->getPreu()*$this->getQuantitat());
    }
}
