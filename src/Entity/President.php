<?php

namespace App\Entity;

use App\Entity\Traits\AppTimesTampable;
use App\Repository\PresidentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PresidentRepository::class)
 * @Vich\Uploadable
 * @ORM\Table(name="Ajeutchim_presidents") 
 * @ORM\HasLifecycleCallbacks
 */
class President
{
    use AppTimesTampable;

    const ETAT = [
        0 => "En cours",
        1 => "Fin mandat"
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $debutedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $finedAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contenue;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="president", fileNameProperty="imageName")
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="presidents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $etat;

    /**
     * @ORM\OneToMany(targetEntity=Bureau::class, mappedBy="president", orphanRemoval=true)
     */
    private $bureaus;

    /**
     * @ORM\ManyToOne(targetEntity=Membre::class, inversedBy="presidents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $membre;

    public function __construct()
    {
        $this->bureaus = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDebutedAt(): ?\DateTimeInterface
    {
        return $this->debutedAt;
    }

    public function setDebutedAt(?\DateTimeInterface $debutedAt): self
    {
        $this->debutedAt = $debutedAt;

        return $this;
    }

    public function getFinedAt(): ?\DateTimeInterface
    {
        return $this->finedAt;
    }

    public function setFinedAt(?\DateTimeInterface $finedAt): self
    {
        $this->finedAt = $finedAt;

        return $this;
    }

    public function getContenue(): ?string
    {
        return $this->contenue;
    }

    public function setContenue(?string $contenue): self
    {
        $this->contenue = $contenue;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
            $bureau->setPresident($this);
        }

        return $this;
    }

    public function removeBureau(Bureau $bureau): self
    {
        if ($this->bureaus->removeElement($bureau)) {
            // set the owning side to null (unless already changed)
            if ($bureau->getPresident() === $this) {
                $bureau->setPresident(null);
            }
        }

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
}