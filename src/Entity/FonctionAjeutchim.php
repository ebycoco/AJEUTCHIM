<?php

namespace App\Entity;

use App\Entity\Traits\AppTimesTampable;
use App\Repository\FonctionAjeutchimRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FonctionAjeutchimRepository::class)
 * @ORM\Table(name="Ajeutchim_fonctions") 
 * @ORM\HasLifecycleCallbacks
 */
class FonctionAjeutchim
{
    use AppTimesTampable;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     */
    private $contenue;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="fonctionAjeutchims")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContenue(): ?string
    {
        return $this->contenue;
    }

    public function setContenue(string $contenue): self
    {
        $this->contenue = $contenue;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}