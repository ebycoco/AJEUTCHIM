<?php

namespace App\Entity;

use App\Entity\Traits\AppTimesTampable;
use App\Repository\MembreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MembreRepository::class)
 * @ORM\Table(name="Ajeutchim_membres") 
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks
 */
class Membre
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
    private $ville;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contact;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $profession;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $referenceAjeutchim;


    /**
     * @ORM\OneToMany(targetEntity=Cotisation::class, mappedBy="membre", orphanRemoval=true)
     */
    private $cotisations;

    /**
     * @ORM\OneToMany(targetEntity=AnnuelleCotisation::class, mappedBy="membre", orphanRemoval=true)
     */
    private $annuelleCotisations;


    /**
     * @ORM\OneToMany(targetEntity=MembreConseil::class, mappedBy="membre", orphanRemoval=true)
     */
    private $membreConseil;

    /**
     * @ORM\OneToMany(targetEntity=Bureau::class, mappedBy="membre", orphanRemoval=true)
     */
    private $bureaus;

    /**
     * @ORM\OneToMany(targetEntity=Mandat::class, mappedBy="membre", orphanRemoval=true)
     */
    private $mandats;

    /**
     * @ORM\OneToMany(targetEntity=President::class, mappedBy="membre", orphanRemoval=true)
     */
    private $presidents;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $annee;

    /**
     * @ORM\Column(type="float")
     */
    private $adhesion;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="membres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="membre")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Candidat::class, mappedBy="membre")
     */
    private $candidats;



    public function __construct()
    {
        $this->cotisations = new ArrayCollection();
        $this->annuelleCotisations = new ArrayCollection();
        $this->bureaus = new ArrayCollection();
        $this->membreConseil = new ArrayCollection();
        $this->mandats = new ArrayCollection();
        $this->presidents = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->candidats = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->prenom;
    }

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

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

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

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(string $profession): self
    {
        $this->profession = $profession;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getReferenceAjeutchim(): ?string
    {
        return $this->referenceAjeutchim;
    }

    public function setReferenceAjeutchim(string $referenceAjeutchim): self
    {
        $this->referenceAjeutchim = $referenceAjeutchim;

        return $this;
    }


    /**
     * @return Collection|Cotisation[]
     */
    public function getCotisations(): Collection
    {
        return $this->cotisations;
    }

    public function addCotisation(Cotisation $cotisation): self
    {
        if (!$this->cotisations->contains($cotisation)) {
            $this->cotisations[] = $cotisation;
            $cotisation->setMembre($this);
        }

        return $this;
    }

    public function removeCotisation(Cotisation $cotisation): self
    {
        if ($this->cotisations->removeElement($cotisation)) {
            // set the owning side to null (unless already changed)
            if ($cotisation->getMembre() === $this) {
                $cotisation->setMembre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AnnuelleCotisation[]
     */
    public function getAnnuelleCotisations(): Collection
    {
        return $this->annuelleCotisations;
    }

    public function addAnnuelleCotisation(AnnuelleCotisation $annuelleCotisation): self
    {
        if (!$this->annuelleCotisations->contains($annuelleCotisation)) {
            $this->annuelleCotisations[] = $annuelleCotisation;
            $annuelleCotisation->setMembre($this);
        }

        return $this;
    }

    public function removeAnnuelleCotisation(AnnuelleCotisation $annuelleCotisation): self
    {
        if ($this->annuelleCotisations->removeElement($annuelleCotisation)) {
            // set the owning side to null (unless already changed)
            if ($annuelleCotisation->getMembre() === $this) {
                $annuelleCotisation->setMembre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MembreConseil[]
     */
    public function getMembreConseil(): Collection
    {
        return $this->membreConseil;
    }

    public function addMembreConseil(MembreConseil $membreConseil): self
    {
        if (!$this->membreConseil->contains($membreConseil)) {
            $this->membreConseil[] = $membreConseil;
            $membreConseil->setMembre($this);
        }

        return $this;
    }

    public function removeMembreConseil(MembreConseil $membreConseil): self
    {
        if ($this->membreConseil->removeElement($membreConseil)) {
            // set the owning side to null (unless already changed)
            if ($membreConseil->getMembre() === $this) {
                $membreConseil->setMembre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Bureau[]
     */
    public function getBureaus(): Collection
    {
        return $this->bureaus;
    }

    public function addBureau(Bureau $bureau): self
    {
        if (!$this->bureaus->contains($bureau)) {
            $this->bureaus[] = $bureau;
            $bureau->setMembre($this);
        }

        return $this;
    }

    public function removeBureau(Bureau $bureau): self
    {
        if ($this->bureaus->removeElement($bureau)) {
            // set the owning side to null (unless already changed)
            if ($bureau->getMembre() === $this) {
                $bureau->setMembre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Mandat[]
     */
    public function getMandats(): Collection
    {
        return $this->mandats;
    }

    public function addMandat(Mandat $mandat): self
    {
        if (!$this->mandats->contains($mandat)) {
            $this->mandats[] = $mandat;
            $mandat->setMembre($this);
        }

        return $this;
    }

    public function removeMandat(Mandat $mandat): self
    {
        if ($this->mandats->removeElement($mandat)) {
            // set the owning side to null (unless already changed)
            if ($mandat->getMembre() === $this) {
                $mandat->setMembre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|President[]
     */
    public function getPresidents(): Collection
    {
        return $this->presidents;
    }

    public function addPresident(President $president): self
    {
        if (!$this->presidents->contains($president)) {
            $this->presidents[] = $president;
            $president->setMembre($this);
        }

        return $this;
    }

    public function removePresident(President $president): self
    {
        if ($this->presidents->removeElement($president)) {
            // set the owning side to null (unless already changed)
            if ($president->getMembre() === $this) {
                $president->setMembre(null);
            }
        }

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

    public function getAdhesion(): ?float
    {
        return $this->adhesion;
    }

    public function setAdhesion(float $adhesion): self
    {
        $this->adhesion = $adhesion;

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
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setMembre($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getMembre() === $this) {
                $user->setMembre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Candidat[]
     */
    public function getCandidats(): Collection
    {
        return $this->candidats;
    }

    public function addCandidat(Candidat $candidat): self
    {
        if (!$this->candidats->contains($candidat)) {
            $this->candidats[] = $candidat;
            $candidat->setMembre($this);
        }

        return $this;
    }

    public function removeCandidat(Candidat $candidat): self
    {
        if ($this->candidats->removeElement($candidat)) {
            // set the owning side to null (unless already changed)
            if ($candidat->getMembre() === $this) {
                $candidat->setMembre(null);
            }
        }

        return $this;
    }
}