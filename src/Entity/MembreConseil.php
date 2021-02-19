<?php

namespace App\Entity;

use App\Entity\Traits\AppTimesTampable;
use App\Repository\MembreConseilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MembreConseilRepository::class)
 * @ORM\Table(name="Ajeutchim_membreconseils") 
 * @ORM\HasLifecycleCallbacks
 */
class MembreConseil
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
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity=Membre::class, inversedBy="membreConseil")
     * @ORM\JoinColumn(nullable=false)
     */
    private $membre;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getPost(): ?string
    {
        return $this->post;
    }

    public function setPost(string $post): self
    {
        $this->post = $post;

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