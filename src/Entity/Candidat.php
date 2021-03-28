<?php

namespace App\Entity;

use App\Entity\Traits\AppTimesTampable;
use App\Repository\CandidatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CandidatRepository::class)
 * @ORM\Table(name="Ajeutchim_candidats") 
 * @ORM\HasLifecycleCallbacks
 */
class Candidat
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombreVoix;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombreVoix2;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $annee;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tour1;
    /**
     * @ORM\Column(type="string", nullable=true, length=255)
     */
    private $tour2;

    /**
     * @ORM\Column(type="boolean")
     */
    private $fin;

    /**
     * @ORM\Column(type="boolean")
     */
    private $vuePublic;

    /**
     * @ORM\ManyToOne(targetEntity=Candidature::class, inversedBy="candidats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $candidature;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNombreVoix(): ?int
    {
        return $this->nombreVoix;
    }

    public function setNombreVoix(?int $nombreVoix): self
    {
        $this->nombreVoix = $nombreVoix;

        return $this;
    }
    public function getNombreVoix2(): ?int
    {
        return $this->nombreVoix2;
    }

    public function setNombreVoix2(?int $nombreVoix2): self
    {
        $this->nombreVoix2 = $nombreVoix2;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getAnnee(): ?string
    {
        return $this->annee;
    }

    public function setAnnee($annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getTour1(): ?string
    {
        return $this->tour1;
    }

    public function setTour1($tour1): self
    {
        $this->tour1 = $tour1;

        return $this;
    }
    public function getTour2(): ?string
    {
        return $this->tour2;
    }

    public function setTour2(?string $tour2): self
    {
        $this->tour2 = $tour2;

        return $this;
    }

    public function getFin(): ?bool
    {
        return $this->fin;
    }

    public function setFin($fin): self
    {
        $this->fin = $fin;

        return $this;
    }
    public function getVuePublic(): ?bool
    {
        return $this->vuePublic;
    }

    public function setVuePublic($vuePublic): self
    {
        $this->vuePublic = $vuePublic;

        return $this;
    }

    public function getCandidature(): ?Candidature
    {
        return $this->candidature;
    }

    public function setCandidature(?Candidature $candidature): self
    {
        $this->candidature = $candidature;

        return $this;
    }
}