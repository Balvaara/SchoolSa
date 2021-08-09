<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\NiveauRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=NiveauRepository::class)
 * @ApiResource(normalizationContext={"groups"={"niv"}})
 */
class Niveau
{
    /** 
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"classe","niv"})
     *@ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"classe","niv"})
     */
    private $libelleniveau;

    /**
     * @ORM\OneToMany(targetEntity=Classe::class, mappedBy="niveaux",cascade={"remove"})
     */
    private $classes;

    public function __construct()
    {
        $this->classes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleniveau(): ?string
    {
        return $this->libelleniveau;
    }

    public function setLibelleniveau(string $libelleniveau): self
    {
        $this->libelleniveau = $libelleniveau;

        return $this;
    }

    /**
     * @return Collection|Classe[]
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    public function addClass(Classe $class): self
    {
        if (!$this->classes->contains($class)) {
            $this->classes[] = $class;
            $class->setNiveaux($this);
        }

        return $this;
    }

    public function removeClass(Classe $class): self
    {
        if ($this->classes->removeElement($class)) {
            // set the owning side to null (unless already changed)
            if ($class->getNiveaux() === $this) {
                $class->setNiveaux(null);
            }
        }

        return $this;
    }
}
