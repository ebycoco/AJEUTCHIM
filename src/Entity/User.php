<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @ORM\Table(name="Ajeutchim_users") 
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface
{

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
    }

    public function getId(): ?int
    {
        return $this->id;
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
}