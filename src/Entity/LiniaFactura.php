<?php

namespace App\Entity;

use App\Repository\LiniaFacturaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LiniaFacturaRepository::class)
 */
class LiniaFactura
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
     * @ORM\Column(type="float")
     */
    private $descompte;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    /**
     * @ORM\ManyToOne(targetEntity=Factura::class, inversedBy="liniaFacturas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $factura;

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

    public function getDescompte(): ?float
    {
        return $this->descompte;
    }

    public function setDescompte(float $descompte): self
    {
        $this->descompte = $descompte;

        return $this;
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

    public function getFactura(): ?Factura
    {
        return $this->factura;
    }

    public function setFactura(?Factura $factura): self
    {
        $this->factura = $factura;

        return $this;
    }
}
