<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Pressupost::class, mappedBy="treballador")
     */
    private $pressuposts;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cognom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telefon;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dni;

    /**
     * @ORM\OneToMany(targetEntity=Agenda::class, mappedBy="treballador")
     */
    private $agendas;

   

    public function __construct()
    {
        $this->pressuposts = new ArrayCollection();
        $this->agendas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function hasRole(string $role):bool{
        if(!empty($this->getRoles())){
            return in_array(strtoupper($role), $this->getRoles(), true);
        }
        return false;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function directorHeredaRols():self{
        $roles = $this->getRoles();
        $rolesNou= array_push($roles,"ROL_MECANIC","ROL_ADMIN");
        $this->setRoles($roles);
        return $this;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $pressupost->setTreballador($this);
        }

        return $this;
    }

    public function removePressupost(Pressupost $pressupost): self
    {
        if ($this->pressuposts->removeElement($pressupost)) {
            // set the owning side to null (unless already changed)
            if ($pressupost->getTreballador() === $this) {
                $pressupost->setTreballador(null);
            }
        }

        return $this;
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

    public function getCognom(): ?string
    {
        return $this->cognom;
    }

    public function setCognom(string $cognom): self
    {
        $this->cognom = $cognom;

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

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(string $dni): self
    {
        $this->dni = $dni;

        return $this;
    }

    public function __toString()
    {
        return "[".$this->id."] ".$this->nom." ".$this->cognom;
    }

    /**
     * @return Collection|Agenda[]
     */
    public function getAgendas(): Collection
    {
        return $this->agendas;
    }

    public function addAgenda(Agenda $agenda): self
    {
        if (!$this->agendas->contains($agenda)) {
            $this->agendas[] = $agenda;
            $agenda->setTreballador($this);
        }

        return $this;
    }

    public function removeAgenda(Agenda $agenda): self
    {
        if ($this->agendas->removeElement($agenda)) {
            // set the owning side to null (unless already changed)
            if ($agenda->getTreballador() === $this) {
                $agenda->setTreballador(null);
            }
        }

        return $this;
    }

   

  

    
}
