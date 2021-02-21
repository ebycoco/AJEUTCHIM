<?php

namespace App\Entity;

use App\Entity\Traits\AppTimesTampable;
use App\Repository\CotisationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CotisationRepository::class)
 * @ORM\Table(name="Ajeutchim_cotisations") 
 * @ORM\HasLifecycleCallbacks
 */
class Cotisation
{
    use AppTimesTampable;
    const STATUS = [
        0 => "Dans le temps",
        1 => "En retard",
        2 => "Attention",
    ];

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
     * @ORM\ManyToOne(targetEntity=Membre::class, inversedBy="cotisations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $membre;

    /**
     * @ORM\Column(type="date")
     */
    private $annee;

    /**
     * @ORM\ManyToOne(targetEntity=MontantAnnuelle::class, inversedBy="cotisations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $montantAnnuelle;

    /**
     * @ORM\Column(type="float",options={"default": 0})
     */
    private $resteMontant;

    /**
     * @ORM\Column(type="float")
     */
    private $montantTotalPaye;

    /**
     * @ORM\Column(type="integer",options={"default": 0})
     */
    private $status;

    /**
     * @ORM\Column(type="boolean",options={"default": 0})
     */
    private $neplus;

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

    public function getMembre(): ?Membre
    {
        return $this->membre;
    }

    public function setMembre(?Membre $membre): self
    {
        $this->membre = $membre;

        return $this;
    }

    public function getAnnee(): ?\DateTimeInterface
    {
        return $this->annee;
    }

    public function setAnnee(\DateTimeInterface $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getMontantAnnuelle(): ?MontantAnnuelle
    {
        return $this->montantAnnuelle;
    }

    public function setMontantAnnuelle(?MontantAnnuelle $montantAnnuelle): self
    {
        $this->montantAnnuelle = $montantAnnuelle;

        return $this;
    }

    public function getResteMontant(): ?float
    {
        return $this->resteMontant;
    }

    public function setResteMontant(float $resteMontant): self
    {
        $this->resteMontant = $resteMontant;

        return $this;
    }

    public function getMontantTotalPaye(): ?float
    {
        return $this->montantTotalPaye;
    }

    public function setMontantTotalPaye(float $montantTotalPaye): self
    {
        $this->montantTotalPaye = $montantTotalPaye;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getNeplus(): ?bool
    {
        return $this->neplus;
    }

    public function setNeplus(bool $neplus): self
    {
        $this->neplus = $neplus;

        return $this;
    }
}