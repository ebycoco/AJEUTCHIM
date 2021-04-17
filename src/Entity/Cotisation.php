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
        2 => "En avance",
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

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $an;
    /**
     * @ORM\Column(type="string")
     */
    private $couleurProfile;

    /**
     * @ORM\Column(type="float")
     */
    private $montantannuelle;

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

    public function getAn(): ?string
    {
        return $this->an;
    }

    public function setAn(string $an): self
    {
        $this->an = $an;

        return $this;
    }

    public function getCouleurProfile(): ?string
    {
        return $this->couleurProfile;
    }

    public function setCouleurProfile($couleurProfile): self
    {
        $this->couleurProfile = $couleurProfile;

        return $this;
    }

    public function getMontantannuelle(): ?float
    {
        return $this->montantannuelle;
    }

    public function setMontantannuelle(float $montantannuelle): self
    {
        $this->montantannuelle = $montantannuelle;

        return $this;
    }
}