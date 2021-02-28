<?php

namespace App\Entity;

use App\Entity\Traits\AppTimesTampable;
use App\Repository\DecaisementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DecaisementRepository::class)
 * @ORM\Table(name="Ajeutchim_decaissements") 
 * @ORM\HasLifecycleCallbacks
 */
class Decaisement
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
     * @ORM\Column(type="float")
     */
    private $frais;


    /**
     * @ORM\Column(type="date")
     */
    private $jour;

    /**
     * @ORM\ManyToOne(targetEntity=Depense::class, inversedBy="decaisements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $depense;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="decaisements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Bureau::class, inversedBy="decaisements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bureau;

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

    public function getFrais(): ?float
    {
        return $this->frais;
    }

    public function setFrais(float $frais): self
    {
        $this->frais = $frais;

        return $this;
    }


    public function getJour(): ?\DateTimeInterface
    {
        return $this->jour;
    }

    public function setJour(\DateTimeInterface $jour): self
    {
        $this->jour = $jour;

        return $this;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getBureau(): ?Bureau
    {
        return $this->bureau;
    }

    public function setBureau(?Bureau $bureau): self
    {
        $this->bureau = $bureau;

        return $this;
    }
}