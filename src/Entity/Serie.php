<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SerieRepository;
use Doctrine\ORM\Mapping\JoinColumn;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;


/**
 * @ORM\Entity(repositoryClass=SerieRepository::class)
 * @ApiResource(normalizationContext={"groups"={"ser"}})
 * @ApiFilter(SearchFilter::class, properties={"codeserie": "exact"})
 */
class Serie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"classe","ser"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"classe","ser"})
     */
    private $codeserie;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"classe","ser"})
     */
    private $libelleserie;

    /**
     * @ORM\OneToMany(targetEntity=Classe::class, mappedBy="series",cascade={"remove"})
     *@ORM\JoinColumn(nullable=true)
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

    public function getCodeserie(): ?string
    {
        return $this->codeserie;
    }

    public function setCodeserie(string $codeserie): self
    {
        $this->codeserie = $codeserie;

        return $this;
    }

    public function getLibelleserie(): ?string
    {
        return $this->libelleserie;
    }

    public function setLibelleserie(string $libelleserie): self
    {
        $this->libelleserie = $libelleserie;

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
            $class->setSeries($this);
        }

        return $this;
    }

    public function removeClass(Classe $class): self
    {
        if ($this->classes->removeElement($class)) {
            // set the owning side to null (unless already changed)
            if ($class->getSeries() === $this) {
                $class->setSeries(null);
            }
        }

        return $this;
    }

  
}
