<?php

namespace App\Entity;

use App\Entity\Traits\AppTimesTampable;
use App\Repository\BilanRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BilanRepository::class)
 * @ORM\Table(name="Ajeutchim_bilan") 
 * @ORM\HasLifecycleCallbacks
 */
class Bilan
{
    use AppTimesTampable;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $depense;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $adhesion;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cotisation;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $versement;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $annee;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepense(): ?float
    {
        return $this->depense;
    }

    public function setDepense(?float $depense): self
    {
        $this->depense = $depense;

        return $this;
    }

    public function getAdhesion(): ?float
    {
        return $this->adhesion;
    }

    public function setAdhesion(?float $adhesion): self
    {
        $this->adhesion = $adhesion;

        return $this;
    }

    public function getCotisation(): ?float
    {
        return $this->cotisation;
    }

    public function setCotisation(?float $cotisation): self
    {
        $this->cotisation = $cotisation;

        return $this;
    }

    public function getVersement(): ?float
    {
        return $this->versement;
    }

    public function setVersement(?float $versement): self
    {
        $this->versement = $versement;

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