<?php

namespace App\Entity;

use DateTimeInterface;
use App\Entity\AnneeAcad;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\InscrireRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=InscrireRepository::class)
 * @ApiResource(normalizationContext={"groups"={"ins"}})
 *  @ApiFilter(SearchFilter::class, properties={"numIns": "exact"})
 */
class Inscrire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
	*@Groups({"ins","pay"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Eleve::class, inversedBy="inscrires")
     * @ORM\JoinColumn(nullable=false)
     *  @Groups({"ins","pay"})
     */
    private $eleves;




    /**
     * @ORM\Column(type="date")
     *@Groups({"ins"})
     */
    private $dateIns;

    /**
     * @ORM\ManyToOne(targetEntity=AnneeAcad::class, inversedBy="inscrires")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"ins","pay"})
     */
    private $session;

    /**
     * @ORM\ManyToOne(targetEntity=Classe::class, inversedBy="inscrires")
     * @ORM\JoinColumn(nullable=false)
     *@Groups({"ins","pay"})
     */
    private $classes;

    /**
     * @ORM\OneToMany(targetEntity=Payement::class, mappedBy="inscrire",cascade={"remove"})
     */
    private $payements;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"ins","pay"})
     */
    private $numIns;

    public function __construct()
    {
        $this->payements = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEleves(): ?Eleve
    {
        return $this->eleves;
    }

    public function setEleves(?Eleve $eleves): self
    {
        $this->eleves = $eleves;

        return $this;
    }





    public function getDateIns(): ?\DateTimeInterface
    {
        return $this->dateIns;
    }

    public function setDateIns(DateTimeInterface $dateIns): self
    {
        $this->dateIns = $dateIns;

        return $this;
    }

    public function getSession(): ?AnneeAcad
    {
        return $this->session;
    }

    public function setSession(?AnneeAcad $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function getClasses(): ?Classe
    {
        return $this->classes;
    }

    public function setClasses(?Classe $classes): self
    {
        $this->classes = $classes;

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
            $payement->setInscrire($this);
        }

        return $this;
    }

    public function removePayement(Payement $payement): self
    {
        if ($this->payements->removeElement($payement)) {
            // set the owning side to null (unless already changed)
            if ($payement->getInscrire() === $this) {
                $payement->setInscrire(null);
            }
        }

        return $this;
    }

    public function getNumIns(): ?string
    {
        return $this->numIns;
    }

    public function setNumIns(string $numIns): self
    {
        $this->numIns = $numIns;

        return $this;
    }
}
