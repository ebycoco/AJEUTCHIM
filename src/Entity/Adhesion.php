<?php

namespace App\Entity;

use App\Entity\Traits\AppTimesTampable;
use App\Repository\AdhesionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdhesionRepository::class)
 * @ORM\Table(name="Ajeutchim_adhesions") 
 * @ORM\HasLifecycleCallbacks
 */
class Adhesion
{
    use AppTimesTampable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $montant;
 

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="adhesions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

     

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

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