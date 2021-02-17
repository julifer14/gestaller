<?php

namespace App\Entity;

use App\Repository\EmpresaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmpresaRepository::class)
 */
class Empresa
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
    private $direccio;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codipostal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ciutat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $provincia;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telefon;

    /**
     * @ORM\Column(type="string", length=9)
     */
    private $nif;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $condicionsOrdre;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $email;

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

    public function getDireccio(): ?string
    {
        return $this->direccio;
    }

    public function setDireccio(string $direccio): self
    {
        $this->direccio = $direccio;

        return $this;
    }

    public function getCodipostal(): ?string
    {
        return $this->codipostal;
    }

    public function setCodipostal(string $codipostal): self
    {
        $this->codipostal = $codipostal;

        return $this;
    }

    public function getCiutat(): ?string
    {
        return $this->ciutat;
    }

    public function setCiutat(string $ciutat): self
    {
        $this->ciutat = $ciutat;

        return $this;
    }

    public function getProvincia(): ?string
    {
        return $this->provincia;
    }

    public function setProvincia(string $provincia): self
    {
        $this->provincia = $provincia;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getTelefon(): ?string
    {
        return $this->telefon;
    }

    public function setTelefon(string $telefon): self
    {
        $this->telefon = $telefon;

        return $this;
    }

    public function getNif(): ?string
    {
        return $this->nif;
    }

    public function setNif(string $nif): self
    {
        $this->nif = $nif;

        return $this;
    }

    public function getCondicionsOrdre(): ?string
    {
        return $this->condicionsOrdre;
    }

    public function setCondicionsOrdre(?string $condicionsOrdre): self
    {
        $this->condicionsOrdre = $condicionsOrdre;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
