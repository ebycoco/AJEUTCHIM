<?php

namespace App\Entity;

use App\Entity\Traits\AppTimesTampable;
use App\Repository\AproposRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AproposRepository::class)
 * @ORM\Table(name="Ajeutchim_apropos") 
 * @ORM\HasLifecycleCallbacks
 */
class Apropos
{
    use AppTimesTampable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contenue;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="apropos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenue(): ?string
    {
        return $this->contenue;
    }

    public function setContenue(?string $contenue): self
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