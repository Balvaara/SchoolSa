<?php

namespace App\Entity;

use App\Entity\TypeNote;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\NoteRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=NoteRepository::class)
 * @ApiResource(normalizationContext={"groups"={"note"}})
 */
class Note
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *@Groups({"note"})
     */
    private $id;

  

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"note"})
     */
    private $apreciation;

    /**
     * @ORM\ManyToOne(targetEntity=Eleve::class, inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"note"})
     */
    private $eleves;

    /**
     * @ORM\ManyToOne(targetEntity=Semestre::class, inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"note"})
     */
    private $sems;

    /**
     * @ORM\ManyToOne(targetEntity=Matiere::class, inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"note"})
     */
    private $mats;

    /**
     * @ORM\ManyToOne(targetEntity=TypeNote::class, inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"note"})
     */
    private $typeNote;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"note"})
     */
    private $valeur;

    /**
     * @ORM\ManyToOne(targetEntity=AnneeAcad::class, inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $annee;

   

    public function getId(): ?int
    {
        return $this->id;
    }

   

    public function getApreciation(): ?string
    {
        return $this->apreciation;
    }

    public function setApreciation(string $apreciation): self
    {
        $this->apreciation = $apreciation;

        return $this;
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

    public function getSems(): ?Semestre
    {
        return $this->sems;
    }

    public function setSems(?Semestre $sems): self
    {
        $this->sems = $sems;

        return $this;
    }

    public function getMats(): ?Matiere
    {
        return $this->mats;
    }

    public function setMats(?Matiere $mats): self
    {
        $this->mats = $mats;

        return $this;
    }

    public function getTypeNote(): ?TypeNote
    {
        return $this->typeNote;
    }

    public function setTypeNote(?TypeNote $typeNote): self
    {
        $this->typeNote = $typeNote;

        return $this;
    }

    public function getValeur(): ?int
    {
        return $this->valeur;
    }

    public function setValeur(int $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getAnnee(): ?AnneeAcad
    {
        return $this->annee;
    }

    public function setAnnee(?AnneeAcad $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

 

}
