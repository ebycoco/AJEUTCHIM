<?php

namespace App\Entity;

use App\Entity\Traits\AppTimesTampable;
use App\Repository\RejectProjectRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RejectProjectRepository::class)
 *  @ORM\Table(name="Ajeutchim_rejectproject") 
 * @ORM\HasLifecycleCallbacks
 */
class RejectProject
{
    use AppTimesTampable;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Depense::class, inversedBy="rejectProjects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $depense;

    /**
     * @ORM\Column(type="text")
     */
    private $motif;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="rejectProjects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepense(): ?Depense
    {
        return $this->depense;
    }

    public function setDepense(?Depense $depense): self
    {
        $this->depense = $depense;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

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