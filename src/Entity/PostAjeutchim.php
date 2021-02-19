<?php

namespace App\Entity;

use App\Entity\Traits\AppTimesTampable;
use App\Repository\PostAjeutchimRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostAjeutchimRepository::class)
 * @ORM\Table(name="Ajeutchim_postajeutchims") 
 * @ORM\HasLifecycleCallbacks
 */
class PostAjeutchim
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
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="postAjeutchims")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Bureau::class, mappedBy="postAjeutchim", orphanRemoval=true)
     */
    private $bureaus;

    /**
     * @ORM\OneToMany(targetEntity=Mandat::class, mappedBy="postAjeutchim", orphanRemoval=true)
     */
    private $mandats;

    public function __construct()
    {
        $this->bureaus = new ArrayCollection();
        $this->mandats = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
            $bureau->setPostAjeutchim($this);
        }

        return $this;
    }

    public function removeBureau(Bureau $bureau): self
    {
        if ($this->bureaus->removeElement($bureau)) {
            // set the owning side to null (unless already changed)
            if ($bureau->getPostAjeutchim() === $this) {
                $bureau->setPostAjeutchim(null);
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
            $mandat->setPostAjeutchim($this);
        }

        return $this;
    }

    public function removeMandat(Mandat $mandat): self
    {
        if ($this->mandats->removeElement($mandat)) {
            // set the owning side to null (unless already changed)
            if ($mandat->getPostAjeutchim() === $this) {
                $mandat->setPostAjeutchim(null);
            }
        }

        return $this;
    }
}