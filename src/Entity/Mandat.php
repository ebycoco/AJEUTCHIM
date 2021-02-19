<?php

namespace App\Entity;

use App\Entity\Traits\AppTimesTampable;
use App\Repository\MandatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MandatRepository::class)
 * @ORM\Table(name="Ajeutchim_mandats") 
 * @ORM\HasLifecycleCallbacks
 */
class Mandat
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
     * @ORM\ManyToOne(targetEntity=Membre::class, inversedBy="mandats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $membre; 

    /**
     * @ORM\ManyToOne(targetEntity=PostAjeutchim::class, inversedBy="mandats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $postAjeutchim;

    /**
     * @ORM\Column(type="boolean",options={"default": false})
     */
    private $active;

    public function __construct()
    {
        $this->bureaus = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}