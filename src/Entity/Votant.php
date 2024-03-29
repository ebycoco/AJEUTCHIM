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
     * @ORM\Column(type="string", length=255)
     */
    private $matricule;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }
}