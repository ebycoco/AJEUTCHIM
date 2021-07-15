<?php

namespace App\Entity;

use App\Entity\Traits\AppTimesTampable;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class) 
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @Vich\Uploadable
 * @ORM\Table(name="Ajeutchim_users") 
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface, Serializable
{
    use AppTimesTampable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=Ajeutchim::class, mappedBy="user", orphanRemoval=true)
     */
    private $ajeutchims;

    /**
     * @ORM\OneToMany(targetEntity=Apropos::class, mappedBy="user", orphanRemoval=true)
     */
    private $apropos;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="user", orphanRemoval=true)
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity=Categorie::class, mappedBy="user", orphanRemoval=true)
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=EvenementRealiser::class, mappedBy="user", orphanRemoval=true)
     */
    private $evenementRealisers;

    /**
     * @ORM\OneToMany(targetEntity=Flash::class, mappedBy="user", orphanRemoval=true)
     */
    private $flashes;

    /**
     * @ORM\OneToMany(targetEntity=ImageAccueil::class, mappedBy="user", orphanRemoval=true)
     */
    private $imageAccueils;

    /**
     * @ORM\OneToMany(targetEntity=President::class, mappedBy="user", orphanRemoval=true)
     */
    private $presidents;

    /**
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="user", orphanRemoval=true)
     */
    private $videos;

    /**
     * @ORM\OneToMany(targetEntity=Village::class, mappedBy="user", orphanRemoval=true)
     */
    private $villages;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pseudo;

    /**
     * @ORM\OneToMany(targetEntity=Adhesion::class, mappedBy="user", orphanRemoval=true)
     */
    private $adhesions;

    /**
     * @ORM\OneToMany(targetEntity=PostAjeutchim::class, mappedBy="user", orphanRemoval=true)
     */
    private $postAjeutchims;

    /**
     * @ORM\OneToMany(targetEntity=MontantAnnuelle::class, mappedBy="user", orphanRemoval=true)
     */
    private $montantAnnuelles;

    /**
     * @ORM\OneToMany(targetEntity=Depense::class, mappedBy="user", orphanRemoval=true)
     */
    private $depenses;

    /**
     * @ORM\OneToMany(targetEntity=Decaisement::class, mappedBy="user", orphanRemoval=true)
     */
    private $decaisements;

    /**
     * @ORM\OneToMany(targetEntity=RejectProject::class, mappedBy="user", orphanRemoval=true)
     */
    private $rejectProjects;

    /**
     * @ORM\OneToMany(targetEntity=Membre::class, mappedBy="user", orphanRemoval=true)
     */
    private $membres;


    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private $matricule;

    /**
     * @ORM\OneToMany(targetEntity=Autredepense::class, mappedBy="user", orphanRemoval=true)
     */
    private $autredepenses;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="utilisateurs", fileNameProperty="imageName")
     * @Assert\Image(maxSize = "8M")
     * 
     *  
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string|null
     */
    private $imageName;

    /**
     * @ORM\ManyToOne(targetEntity=Membre::class, inversedBy="users")
     */
    private $membre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contact;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profession;

    /**
     * @ORM\OneToMany(targetEntity=FonctionAjeutchim::class, mappedBy="user", orphanRemoval=true)
     */
    private $fonctionAjeutchims;

    /**
     * @ORM\OneToMany(targetEntity=Galery::class, mappedBy="user", orphanRemoval=true)
     */
    private $galeries;




    public function __construct()
    {
        $this->ajeutchims = new ArrayCollection();
        $this->apropos = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->evenementRealisers = new ArrayCollection();
        $this->flashes = new ArrayCollection();
        $this->imageAccueils = new ArrayCollection();
        $this->presidents = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->villages = new ArrayCollection();
        $this->adhesions = new ArrayCollection();
        $this->postAjeutchims = new ArrayCollection();
        $this->montantAnnuelles = new ArrayCollection();
        $this->depenses = new ArrayCollection();
        $this->decaisements = new ArrayCollection();
        $this->rejectProjects = new ArrayCollection();
        $this->membres = new ArrayCollection();
        $this->autredepenses = new ArrayCollection();
        $this->fonctionAjeutchims = new ArrayCollection();
        $this->galeries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->pseudo;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }
    /**
     * @return Collection|Ajeutchim[]
     */
    public function getAjeutchims(): Collection
    {
        return $this->ajeutchims;
    }

    public function addAjeutchim(Ajeutchim $ajeutchim): self
    {
        if (!$this->ajeutchims->contains($ajeutchim)) {
            $this->ajeutchims[] = $ajeutchim;
            $ajeutchim->setUser($this);
        }

        return $this;
    }

    public function removeAjeutchim(Ajeutchim $ajeutchim): self
    {
        if ($this->ajeutchims->removeElement($ajeutchim)) {
            // set the owning side to null (unless already changed)
            if ($ajeutchim->getUser() === $this) {
                $ajeutchim->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Apropos[]
     */
    public function getApropos(): Collection
    {
        return $this->apropos;
    }

    public function addApropo(Apropos $apropo): self
    {
        if (!$this->apropos->contains($apropo)) {
            $this->apropos[] = $apropo;
            $apropo->setUser($this);
        }

        return $this;
    }

    public function removeApropo(Apropos $apropo): self
    {
        if ($this->apropos->removeElement($apropo)) {
            // set the owning side to null (unless already changed)
            if ($apropo->getUser() === $this) {
                $apropo->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Categorie[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categorie $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setUser($this);
        }

        return $this;
    }

    public function removeCategory(Categorie $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getUser() === $this) {
                $category->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EvenementRealiser[]
     */
    public function getEvenementRealisers(): Collection
    {
        return $this->evenementRealisers;
    }

    public function addEvenementRealiser(EvenementRealiser $evenementRealiser): self
    {
        if (!$this->evenementRealisers->contains($evenementRealiser)) {
            $this->evenementRealisers[] = $evenementRealiser;
            $evenementRealiser->setUser($this);
        }

        return $this;
    }

    public function removeEvenementRealiser(EvenementRealiser $evenementRealiser): self
    {
        if ($this->evenementRealisers->removeElement($evenementRealiser)) {
            // set the owning side to null (unless already changed)
            if ($evenementRealiser->getUser() === $this) {
                $evenementRealiser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Flash[]
     */
    public function getFlashes(): Collection
    {
        return $this->flashes;
    }

    public function addFlash(Flash $flash): self
    {
        if (!$this->flashes->contains($flash)) {
            $this->flashes[] = $flash;
            $flash->setUser($this);
        }

        return $this;
    }

    public function removeFlash(Flash $flash): self
    {
        if ($this->flashes->removeElement($flash)) {
            // set the owning side to null (unless already changed)
            if ($flash->getUser() === $this) {
                $flash->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ImageAccueil[]
     */
    public function getImageAccueils(): Collection
    {
        return $this->imageAccueils;
    }

    public function addImageAccueil(ImageAccueil $imageAccueil): self
    {
        if (!$this->imageAccueils->contains($imageAccueil)) {
            $this->imageAccueils[] = $imageAccueil;
            $imageAccueil->setUser($this);
        }

        return $this;
    }

    public function removeImageAccueil(ImageAccueil $imageAccueil): self
    {
        if ($this->imageAccueils->removeElement($imageAccueil)) {
            // set the owning side to null (unless already changed)
            if ($imageAccueil->getUser() === $this) {
                $imageAccueil->setUser(null);
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
            $president->setUser($this);
        }

        return $this;
    }

    public function removePresident(President $president): self
    {
        if ($this->presidents->removeElement($president)) {
            // set the owning side to null (unless already changed)
            if ($president->getUser() === $this) {
                $president->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setUser($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getUser() === $this) {
                $video->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Village[]
     */
    public function getVillages(): Collection
    {
        return $this->villages;
    }

    public function addVillage(Village $village): self
    {
        if (!$this->villages->contains($village)) {
            $this->villages[] = $village;
            $village->setUser($this);
        }

        return $this;
    }

    public function removeVillage(Village $village): self
    {
        if ($this->villages->removeElement($village)) {
            // set the owning side to null (unless already changed)
            if ($village->getUser() === $this) {
                $village->setUser(null);
            }
        }

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection|Adhesion[]
     */
    public function getAdhesions(): Collection
    {
        return $this->adhesions;
    }

    public function addAdhesion(Adhesion $adhesion): self
    {
        if (!$this->adhesions->contains($adhesion)) {
            $this->adhesions[] = $adhesion;
            $adhesion->setUser($this);
        }

        return $this;
    }

    public function removeAdhesion(Adhesion $adhesion): self
    {
        if ($this->adhesions->removeElement($adhesion)) {
            // set the owning side to null (unless already changed)
            if ($adhesion->getUser() === $this) {
                $adhesion->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PostAjeutchim[]
     */
    public function getPostAjeutchims(): Collection
    {
        return $this->postAjeutchims;
    }

    public function addPostAjeutchim(PostAjeutchim $postAjeutchim): self
    {
        if (!$this->postAjeutchims->contains($postAjeutchim)) {
            $this->postAjeutchims[] = $postAjeutchim;
            $postAjeutchim->setUser($this);
        }

        return $this;
    }

    public function removePostAjeutchim(PostAjeutchim $postAjeutchim): self
    {
        if ($this->postAjeutchims->removeElement($postAjeutchim)) {
            // set the owning side to null (unless already changed)
            if ($postAjeutchim->getUser() === $this) {
                $postAjeutchim->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MontantAnnuelle[]
     */
    public function getMontantAnnuelles(): Collection
    {
        return $this->montantAnnuelles;
    }

    public function addMontantAnnuelle(MontantAnnuelle $montantAnnuelle): self
    {
        if (!$this->montantAnnuelles->contains($montantAnnuelle)) {
            $this->montantAnnuelles[] = $montantAnnuelle;
            $montantAnnuelle->setUser($this);
        }

        return $this;
    }

    public function removeMontantAnnuelle(MontantAnnuelle $montantAnnuelle): self
    {
        if ($this->montantAnnuelles->removeElement($montantAnnuelle)) {
            // set the owning side to null (unless already changed)
            if ($montantAnnuelle->getUser() === $this) {
                $montantAnnuelle->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Depense[]
     */
    public function getDepenses(): Collection
    {
        return $this->depenses;
    }

    public function addDepense(Depense $depense): self
    {
        if (!$this->depenses->contains($depense)) {
            $this->depenses[] = $depense;
            $depense->setUser($this);
        }

        return $this;
    }

    public function removeDepense(Depense $depense): self
    {
        if ($this->depenses->removeElement($depense)) {
            // set the owning side to null (unless already changed)
            if ($depense->getUser() === $this) {
                $depense->setUser(null);
            }
        }

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
            $decaisement->setUser($this);
        }

        return $this;
    }

    public function removeDecaisement(Decaisement $decaisement): self
    {
        if ($this->decaisements->removeElement($decaisement)) {
            // set the owning side to null (unless already changed)
            if ($decaisement->getUser() === $this) {
                $decaisement->setUser(null);
            }
        }

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
            $rejectProject->setUser($this);
        }

        return $this;
    }

    public function removeRejectProject(RejectProject $rejectProject): self
    {
        if ($this->rejectProjects->removeElement($rejectProject)) {
            // set the owning side to null (unless already changed)
            if ($rejectProject->getUser() === $this) {
                $rejectProject->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Membre[]
     */
    public function getMembres(): Collection
    {
        return $this->membres;
    }

    public function addMembre(Membre $membre): self
    {
        if (!$this->membres->contains($membre)) {
            $this->membres[] = $membre;
            $membre->setUser($this);
        }

        return $this;
    }

    public function removeMembre(Membre $membre): self
    {
        if ($this->membres->removeElement($membre)) {
            // set the owning side to null (unless already changed)
            if ($membre->getUser() === $this) {
                $membre->setUser(null);
            }
        }

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(?string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    /**
     * @return Collection|Autredepense[]
     */
    public function getAutredepenses(): Collection
    {
        return $this->autredepenses;
    }

    public function addAutredepense(Autredepense $autredepense): self
    {
        if (!$this->autredepenses->contains($autredepense)) {
            $this->autredepenses[] = $autredepense;
            $autredepense->setUser($this);
        }

        return $this;
    }

    public function removeAutredepense(Autredepense $autredepense): self
    {
        if ($this->autredepenses->removeElement($autredepense)) {
            // set the owning side to null (unless already changed)
            if ($autredepense->getUser() === $this) {
                $autredepense->setUser(null);
            }
        }

        return $this;
    }
    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
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

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(?string $profession): self
    {
        $this->profession = $profession;

        return $this;
    }

    /**
     * @return Collection|FonctionAjeutchim[]
     */
    public function getFonctionAjeutchims(): Collection
    {
        return $this->fonctionAjeutchims;
    }

    public function addFonctionAjeutchim(FonctionAjeutchim $fonctionAjeutchim): self
    {
        if (!$this->fonctionAjeutchims->contains($fonctionAjeutchim)) {
            $this->fonctionAjeutchims[] = $fonctionAjeutchim;
            $fonctionAjeutchim->setUser($this);
        }

        return $this;
    }

    public function removeFonctionAjeutchim(FonctionAjeutchim $fonctionAjeutchim): self
    {
        if ($this->fonctionAjeutchims->removeElement($fonctionAjeutchim)) {
            // set the owning side to null (unless already changed)
            if ($fonctionAjeutchim->getUser() === $this) {
                $fonctionAjeutchim->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Galery[]
     */
    public function getGaleries(): Collection
    {
        return $this->galeries;
    }

    public function addGalery(Galery $galery): self
    {
        if (!$this->galeries->contains($galery)) {
            $this->galeries[] = $galery;
            $galery->setUser($this);
        }

        return $this;
    }

    public function removeGalery(Galery $galery): self
    {
        if ($this->galeries->removeElement($galery)) {
            // set the owning side to null (unless already changed)
            if ($galery->getUser() === $this) {
                $galery->setUser(null);
            }
        }

        return $this;
    }
}
