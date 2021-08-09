<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClasseMatiereRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ClasseMatiereRepository::class)
 * @ApiResource(normalizationContext={"groups"={"cl_mt"}})
 */
class ClasseMatiere
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"cl_mt"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Classe::class, inversedBy="classeMatieres")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"cl_mt"})
     */
    private $classes;

    /**
     * @ORM\ManyToOne(targetEntity=Matiere::class, inversedBy="classeMatieres")
     * @ORM\JoinColumn(nullable=false)
      *@Groups({"cl_mt"})
     */
    private $matieres;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"cl_mt","mat"})
     */
    private $coef;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMatieres(): ?Matiere
    {
        return $this->matieres;
    }

    public function setMatieres(?Matiere $matieres): self
    {
        $this->matieres = $matieres;

        return $this;
    }

    public function getCoef(): ?int
    {
        return $this->coef;
    }

    public function setCoef(int $coef): self
    {
        $this->coef = $coef;

        return $this;
    }
}
