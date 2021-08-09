<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MoisRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MoisRepository::class)
 * @ApiResource(normalizationContext={"groups"={"mois"}})
 */
class Mois
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *  @Groups({"mois","pay"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"mois","pay"})
     */
    private $libellemois;

    /**
     * @ORM\OneToMany(targetEntity=Payement::class, mappedBy="mois")
     */
    private $payements;

    public function __construct()
    {
        $this->payements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibellemois(): ?string
    {
        return $this->libellemois;
    }

    public function setLibellemois(string $libellemois): self
    {
        $this->libellemois = $libellemois;

        return $this;
    }

    /**
     * @return Collection|Payement[]
     */
    public function getPayements(): Collection
    {
        return $this->payements;
    }

    public function addPayement(Payement $payement): self
    {
        if (!$this->payements->contains($payement)) {
            $this->payements[] = $payement;
            $payement->setMois($this);
        }

        return $this;
    }

    public function removePayement(Payement $payement): self
    {
        if ($this->payements->removeElement($payement)) {
            // set the owning side to null (unless already changed)
            if ($payement->getMois() === $this) {
                $payement->setMois(null);
            }
        }

        return $this;
    }
}
