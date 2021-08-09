<?php

namespace App\Entity;

use App\Entity\Mois;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use App\Repository\PayementRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PayementRepository::class)
 * @ApiResource(normalizationContext={"groups"={"pay"}})
 */
class Payement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"pay"})
     */
    private $id;

 

    /**
     * @ORM\Column(type="integer")
     * @Groups({"pay"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $montant;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"pay"})
     */
    private $numPaye;

    /**
     * @ORM\ManyToOne(targetEntity=Mois::class, inversedBy="payements")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"pay"})
     */
    private $mois;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"pay"})
     */
    private $dateDePayement;

    /**
     * @ORM\ManyToOne(targetEntity=Inscrire::class, inversedBy="payements")
     *@ORM\JoinColumn(nullable=true)
     * @Groups({"pay"})
     */
    private $inscrire;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"pay"})
     */
    private $montantMensualite;

    public function getId(): ?int
    {
        return $this->id;
    }

  

    


    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getNumPaye(): ?string
    {
        return $this->numPaye;
    }

    public function setNumPaye(string $numPaye): self
    {
        $this->numPaye = $numPaye;

        return $this;
    }

    public function getMois(): ?Mois
    {
        return $this->mois;
    }

    public function setMois(?Mois $mois): self
    {
        $this->mois = $mois;

        return $this;
    }

    public function getDateDePayement(): ?\DateTimeInterface
    {
        return $this->dateDePayement;
    }

    public function setDateDePayement(\DateTimeInterface $dateDePayement): self
    {
        $this->dateDePayement = $dateDePayement;

        return $this;
    }

    public function getInscrire(): ?Inscrire
    {
        return $this->inscrire;
    }

    public function setInscrire(?Inscrire $inscrire): self
    {
        $this->inscrire = $inscrire;

        return $this;
    }

    public function getMontantMensualite(): ?int
    {
        return $this->montantMensualite;
    }

    public function setMontantMensualite(?int $montantMensualite): self
    {
        $this->montantMensualite = $montantMensualite;

        return $this;
    }
}
