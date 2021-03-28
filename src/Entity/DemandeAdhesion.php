<?php

namespace App\Entity;

use App\Entity\Traits\AppTimesTampable;
use App\Repository\DemandeAdhesionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DemandeAdhesionRepository::class)
 * @ORM\Table(name="Ajeutchim_demandeAdhesions") 
 * @ORM\HasLifecycleCallbacks
 */
class DemandeAdhesion
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
     * @ORM\Column(type="string", length=255)
     */
    private $profession;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $contact;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomPere;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomMere;

    /**
     * @ORM\Column(type="text")
     */
    private $motivation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $droit;


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

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(string $profession): self
    {
        $this->profession = $profession;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getNomPere(): ?string
    {
        return $this->nomPere;
    }

    public function setNomPere(string $nomPere): self
    {
        $this->nomPere = $nomPere;

        return $this;
    }

    public function getNomMere(): ?string
    {
        return $this->nomMere;
    }

    public function setNomMere(string $nomMere): self
    {
        $this->nomMere = $nomMere;

        return $this;
    }

    public function getMotivation(): ?string
    {
        return $this->motivation;
    }

    public function setMotivation(string $motivation): self
    {
        $this->motivation = $motivation;

        return $this;
    }

    public function getDroit(): ?bool
    {
        return $this->droit;
    }

    public function setDroit(bool $droit): self
    {
        $this->droit = $droit;

        return $this;
    }
}