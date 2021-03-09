<?php

namespace App\Entity;

use App\Entity\Traits\AppTimesTampable;
use App\Repository\VotantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VotantRepository::class)
 * @ORM\Table(name="Ajeutchim_votants") 
 * @ORM\HasLifecycleCallbacks
 */
class Votant
{
    use AppTimesTampable;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Membre::class, inversedBy="votants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $membre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMembre(): ?Membre
    {
        return $this->membre;
    }

    public function setMembre(?Membre $membre): self
    {
        $this->membre = $membre;

        return $this;
    }
}