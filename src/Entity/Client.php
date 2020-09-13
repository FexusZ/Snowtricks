<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 */
class Client implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, options={"unique":true})
     */
    private $userName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, options={"unique":true})
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Figures::class, mappedBy="id_client", orphanRemoval=true)
     */
    private $figures;

    private $confirm_password;

    public function __construct()
    {
        $this->figures = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->userName;
    }

    public function setUsername(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password =  $password;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirm_password;
    }

    public function setConfirmPassword(string $password): self
    {
        $this->confirm_password =  $confirm_password;

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
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /**
     * @return Collection|Figures[]
     */
    public function getFigures(): Collection
    {
        return $this->figures;
    }

    public function addFigure(Figures $figure): self
    {
        if (!$this->figures->contains($figure)) {
            $this->figures[] = $figure;
            $figure->setIdClient($this);
        }

        return $this;
    }

    public function removeFigure(Figures $figure): self
    {
        if ($this->figures->contains($figure)) {
            $this->figures->removeElement($figure);
            // set the owning side to null (unless already changed)
            if ($figure->getIdClient() === $this) {
                $figure->setIdClient(null);
            }
        }

        return $this;
    }
}
