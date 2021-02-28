<?php

namespace App\Entity;

use App\Entity\Traits\AppTimesTampable;
use App\Repository\BureauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BureauRepository::class)
 * @ORM\Table(name="Ajeutchim_bureaux") 
 * @ORM\HasLifecycleCallbacks
 */
class Bureau
{
    use AppTimesTampable;

    const ETAT = [
        0 => "En cours",
        1 => "Fin"
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity=Membre::class, inversedBy="bureaus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $membre;

    /**
     * @ORM\ManyToOne(targetEntity=PostAjeutchim::class, inversedBy="bureaus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $postAjeutchim;

    /**
     * @ORM\ManyToOne(targetEntity=President::class, inversedBy="bureaus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $president;

    /**
     * @ORM\Column(type="integer")
     */
    private $etat;

    /**
     * @ORM\OneToMany(targetEntity=Decaisement::class, mappedBy="bureau", orphanRemoval=true)
     */
    private $decaisements;

    public function __construct()
    {
        $this->decaisements = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
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

    public function getPostAjeutchim(): ?PostAjeutchim
    {
        return $this->postAjeutchim;
    }

    public function setPostAjeutchim(?PostAjeutchim $postAjeutchim): self
    {
        $this->postAjeutchim = $postAjeutchim;

        return $this;
    }

    public function getPresident(): ?President
    {
        return $this->president;
    }

    public function setPresident(?President $president): self
    {
        $this->president = $president;

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
            $decaisement->setBureau($this);
        }

        return $this;
    }

    public function removeDecaisement(Decaisement $decaisement): self
    {
        if ($this->decaisements->removeElement($decaisement)) {
            // set the owning side to null (unless already changed)
            if ($decaisement->getBureau() === $this) {
                $decaisement->setBureau(null);
            }
        }

        return $this;
    }
}