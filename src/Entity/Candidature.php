<?php

namespace App\Entity;

use App\Entity\Traits\AppTimesTampable;
use App\Repository\CandidatureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CandidatureRepository::class)
 * @Vich\Uploadable
 * @ORM\Table(name="Ajeutchim_candidatures") 
 * @ORM\HasLifecycleCallbacks
 */
class Candidature
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
    private $matriculeAjeutchim;

    /**
     * @ORM\Column(type="integer")
     */
    private $droit;

    /** 
     * @Vich\UploadableField(mapping="imagecandidat", fileNameProperty="imageCandidat")
     * @Assert\Image(maxSize = "8M")
     * 
     *  
     * @var File|null
     */
    private $imageFile;
    
    /**
     * @ORM\Column(type="string")
     *
     * @var string|null
     */
    private $imageCandidat;

    /** 
     * @Vich\UploadableField(mapping="imageProgramme", fileNameProperty="imageProgramme")
     * @Assert\Image(maxSize = "8M")
     * 
     *  
     * @var File|null
     */
    private $imageProgrammeFile;
    
    /**
     * @ORM\Column(type="string")
     *
     * @var string|null
     */
    private $imageProgramme;

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

    public function getMatriculeAjeutchim(): ?string
    {
        return $this->matriculeAjeutchim;
    }

    public function setMatriculeAjeutchim(string $matriculeAjeutchim): self
    {
        $this->matriculeAjeutchim = $matriculeAjeutchim;

        return $this;
    }

    public function getDroit(): ?int
    {
        return $this->droit;
    }

    public function setDroit(int $droit): self
    {
        $this->droit = $droit;

        return $this;
    }
    /** 
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
    
    public function setImageCandidat(?string $imageCandidat): void
    {
        $this->imageCandidat = $imageCandidat;
    }

    public function getImageCandidat(): ?string
    {
        return $this->imageCandidat;
    }
    
    /** 
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageProgrammeFile
     */
    public function setImageProgrammeFile(?File $imageProgrammeFile = null): void
    {
        $this->imageProgrammeFile = $imageProgrammeFile;

        if (null !== $imageProgrammeFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }
    
    public function getImageProgrammeFile(): ?File
    {
        return $this->imageProgrammeFile;
    }
    
    public function setImageProgramme(?string $imageProgramme): void
    {
        $this->imageProgramme = $imageProgramme;
    }

    public function getImageProgramme(): ?string
    {
        return $this->imageProgramme;
    }
}