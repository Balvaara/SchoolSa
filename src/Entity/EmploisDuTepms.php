<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EmploisDuTepmsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EmploisDuTepmsRepository::class)
 * @ApiResource(normalizationContext={"groups"={"emp"}})
 */
class EmploisDuTepms
{
    /**
     * @ORM\Id
     * @Groups({"emp"})
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     * @Groups({"emp"})
     */
    private $heurDebut;

    /**
     * @ORM\Column(type="time")
     * @Groups({"emp"})
     */
    private $heurFin;

    /**
     * @ORM\ManyToOne(targetEntity=Professeur::class, inversedBy="emploisDuTepms")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"emp"})
     */
    private $professeurs;

    /**
     * @ORM\ManyToOne(targetEntity=Classe::class, inversedBy="emploisDuTepms")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"emp"})
     */
    private $classes;

 

    /**
     * @ORM\ManyToOne(targetEntity=Matiere::class, inversedBy="emploisDuTepms")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"emp"})
     */
    private $mats;



    /**
     * @ORM\Column(type="string", length=255)
	* @Groups({"emp"})
     */
    private $jours;

    /**
     * @ORM\ManyToOne(targetEntity=AnneeAcad::class, inversedBy="emploisDuTepms")
     * @ORM\JoinColumn(nullable=false)
     */
    private $annees;

  
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeurDebut(): ?\DateTimeInterface
    {
        return $this->heurDebut;
    }

    public function setHeurDebut(\DateTimeInterface $heurDebut): self
    {
        $this->heurDebut = $heurDebut;

        return $this;
    }

    public function getHeurFin(): ?\DateTimeInterface
    {
        return $this->heurFin;
    }

    public function setHeurFin(\DateTimeInterface $heurFin): self
    {
        $this->heurFin = $heurFin;

        return $this;
    }

    public function getProfesseurs(): ?Professeur
    {
        return $this->professeurs;
    }

    public function setProfesseurs(?Professeur $professeurs): self
    {
        $this->professeurs = $professeurs;

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

   

    public function getMats(): ?Matiere
    {
        return $this->mats;
    }

    public function setMats(?Matiere $mats): self
    {
        $this->mats = $mats;

        return $this;
    }

 

    public function getJours(): ?string
    {
        return $this->jours;
    }

    public function setJours(string $jours): self
    {
        $this->jours = $jours;

        return $this;
    }

    public function getAnnees(): ?AnneeAcad
    {
        return $this->annees;
    }

    public function setAnnees(?AnneeAcad $annees): self
    {
        $this->annees = $annees;

        return $this;
    }

 

}
