<?php

namespace App\Entity;

use App\Entity\Traits\AppTimesTampable;
use App\Repository\VersementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VersementRepository::class)
 * @ORM\Table(name="Ajeutchim_versements") 
 * @ORM\HasLifecycleCallbacks
 */
class Versement
{
    use AppTimesTampable;

    const OBJET = [
        0 => "Versement",
        1 => "Don",
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
     * @ORM\Column(type="integer")
     */
    private $objet;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

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

    public function getObjet(): ?int
    {
        return $this->objet;
    }

    public function setObjet(int $objet): self
    {
        $this->objet = $objet;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
}