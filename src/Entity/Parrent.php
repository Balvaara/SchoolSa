<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ParrentRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ParrentRepository::class)
 * @ApiResource(normalizationContext={"groups"={"pr"}})
 * @ApiFilter(SearchFilter::class, properties={"telp": "exact"})
 */
class Parrent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"ele","pr","ins"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
	* @Groups({"ele","pr","ins"})
     */
    private $nomp;

    /**
     * @ORM\Column(type="string", length=255)
       * @Groups({"ele","pr","ins"})
     */
    private $prenomp;

    /**
     * @ORM\Column(type="string", length=255)
	* @Groups({"ele","pr","ins"})
     */
    private $adressep;

    /**
     * @ORM\Column(type="string", length=255)
	* @Groups({"ele","pr","ins"})
     */
    private $telp;

    /**
     * @ORM\OneToMany(targetEntity=Eleve::class, mappedBy="parrents")
	** @Groups({"ele","pr","ins"})
     */
    private $eleves;

    public function __construct()
    {
        $this->eleves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomp(): ?string
    {
        return $this->nomp;
    }

    public function setNomp(string $nomp): self
    {
        $this->nomp = $nomp;

        return $this;
    }

    public function getPrenomp(): ?string
    {
        return $this->prenomp;
    }

    public function setPrenomp(string $prenomp): self
    {
        $this->prenomp = $prenomp;

        return $this;
    }

    public function getAdressep(): ?string
    {
        return $this->adressep;
    }

    public function setAdressep(string $adressep): self
    {
        $this->adressep = $adressep;

        return $this;
    }

    public function getTelp(): ?string
    {
        return $this->telp;
    }

    public function setTelp(string $telp): self
    {
        $this->telp = $telp;

        return $this;
    }

    /**
     * @return Collection|Eleve[]
     */
    public function getEleves(): Collection
    {
        return $this->eleves;
    }

    public function addElefe(Eleve $elefe): self
    {
        if (!$this->eleves->contains($elefe)) {
            $this->eleves[] = $elefe;
            $elefe->setParrents($this);
        }

        return $this;
    }

    public function removeElefe(Eleve $elefe): self
    {
        if ($this->eleves->removeElement($elefe)) {
            // set the owning side to null (unless already changed)
            if ($elefe->getParrents() === $this) {
                $elefe->setParrents(null);
            }
        }

        return $this;
    }
}
