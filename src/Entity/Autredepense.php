<?php

namespace App\Entity;

use App\Entity\Traits\TimesTampable;
use App\Repository\AutredepenseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AutredepenseRepository::class)
 * @ORM\Table(name="Ajeutchim_autredepenses") 
 * @ORM\HasLifecycleCallbacks
 */
class Autredepense
{
    use TimesTampable;
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
     * @ORM\Column(type="string", length=255)
     */
    private $motif;

    /**
     * @ORM\Column(type="date")
     */
    private $jourpris;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="autredepenses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $annee;

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

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getJourpris(): ?\DateTimeInterface
    {
        return $this->jourpris;
    }

    public function setJourpris(\DateTimeInterface $jourpris): self
    {
        $this->jourpris = $jourpris;

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

    public function getAnnee(): ?string
    {
        return $this->annee;
    }

    public function setAnnee(string $annee): self
    {
        $this->annee = $annee;

        return $this;
    }
}