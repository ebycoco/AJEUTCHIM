<?php

namespace App\Entity;

use App\Entity\Traits\AppTimesTampable;
use App\Repository\DepenseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DepenseRepository::class)
 * @ORM\Table(name="Ajeutchim_depenses") 
 * @ORM\HasLifecycleCallbacks
 */
class Depense
{
    use AppTimesTampable;

    const ETAT = [
        0 => "En cours",
        1 => "En traitement",
        2 => "Terminer",
        3 => "Historique",
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean",options={"default": false})
     */
    private $confirme;

    /**
     * @ORM\Column(type="integer",options={"default": 0})
     */
    private $etat;

    /**
     * @ORM\Column(type="float")
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="depenses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Decaisement::class, mappedBy="depense", orphanRemoval=true)
     */
    private $decaisements;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $montanpaye;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $annee;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $rejeter;

    /**
     * @ORM\OneToMany(targetEntity=RejectProject::class, mappedBy="depense", orphanRemoval=true)
     */
    private $rejectProjects;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visible;

    public function __construct()
    {
        $this->decaisements = new ArrayCollection();
        $this->rejectProjects = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    public function getConfirme(): ?bool
    {
        return $this->confirme;
    }

    public function setConfirme(bool $confirme): self
    {
        $this->confirme = $confirme;

        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): self
    {
        $this->etat = $etat;

        return $this;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Decaisement[]
     */
    public function getDecaisements(): Collection
    {
        return $this->decaisements;
    }

    public function addDecaisement(Decaisement $decaisement): self
    {
        if (!$this->decaisements->contains($decaisement)) {
            $this->decaisements[] = $decaisement;
            $decaisement->setDepense($this);
        }

        return $this;
    }

    public function removeDecaisement(Decaisement $decaisement): self
    {
        if ($this->decaisements->removeElement($decaisement)) {
            // set the owning side to null (unless already changed)
            if ($decaisement->getDepense() === $this) {
                $decaisement->setDepense(null);
            }
        }

        return $this;
    }

    public function getMontanpaye(): ?float
    {
        return $this->montanpaye;
    }

    public function setMontanpaye(?float $montanpaye): self
    {
        $this->montanpaye = $montanpaye;

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

    public function getRejeter(): ?bool
    {
        return $this->rejeter;
    }

    public function setRejeter(?bool $rejeter): self
    {
        $this->rejeter = $rejeter;

        return $this;
    }

    /**
     * @return Collection|RejectProject[]
     */
    public function getRejectProjects(): Collection
    {
        return $this->rejectProjects;
    }

    public function addRejectProject(RejectProject $rejectProject): self
    {
        if (!$this->rejectProjects->contains($rejectProject)) {
            $this->rejectProjects[] = $rejectProject;
            $rejectProject->setDepense($this);
        }

        return $this;
    }

    public function removeRejectProject(RejectProject $rejectProject): self
    {
        if ($this->rejectProjects->removeElement($rejectProject)) {
            // set the owning side to null (unless already changed)
            if ($rejectProject->getDepense() === $this) {
                $rejectProject->setDepense(null);
            }
        }

        return $this;
    }

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }
}