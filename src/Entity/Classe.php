<?php

namespace App\Entity;

use App\Entity\Serie;
use App\Entity\Niveau;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClasseRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Api\FilterInterface;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;



/**
 * @ORM\Entity(repositoryClass=ClasseRepository::class)
 *@ApiResource(normalizationContext={"groups"={"classe"}})
 *@ApiFilter(SearchFilter::class, properties={"codeclasse": "exact"})
 */
class Classe
{
    /**
     * @ORM\Id
     * @Groups({"classe"})
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"mat"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"classe","ele","emp","mat","ins","pay"})
     */
    private $codeclasse;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"classe","ele","emp","mat","ins","cl_mt","pay"})
     */
    private $libelleclasse;

    /**
     * @ORM\ManyToOne(targetEntity=Niveau::class, inversedBy="classes")
     * @Groups({"classe"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $niveaux;

    /**
     * @ORM\ManyToOne(targetEntity=Serie::class, inversedBy="classes")
     * @Groups({"classe"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $series;




    /**
     * @ORM\OneToMany(targetEntity=EmploisDuTepms::class, mappedBy="classes")
     */
    private $emploisDuTepms;

    /**
     * @ORM\OneToMany(targetEntity=Inscrire::class, mappedBy="classes")
    *@Groups({"classe"})
     */
    private $inscrires;

    /**
     * @ORM\Column(type="integer")
	*@Groups({"classe","ele","emp","mat","ins","cl_mt"})
     */
    private $montantIns;

    /**
     * @ORM\Column(type="integer")
     *@Groups({"classe","ele","emp","mat","ins","cl_mt"})
     */
    private $montantMens;

    /**
     * @ORM\OneToMany(targetEntity=ClasseMatiere::class, mappedBy="classes")
     */
    private $classeMatieres;

    public function __construct()
    {
        $this->emploisDuTepms = new ArrayCollection();
        $this->inscrires = new ArrayCollection();
        $this->classeMatieres = new ArrayCollection();
    }

   


  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeclasse(): ?string
    {
        return $this->codeclasse;
    }

    public function setCodeclasse(string $codeclasse): self
    {
        $this->codeclasse = $codeclasse;

        return $this;
    }

    public function getLibelleclasse(): ?string
    {
        return $this->libelleclasse;
    }

    public function setLibelleclasse(string $libelleclasse): self
    {
        $this->libelleclasse = $libelleclasse;

        return $this;
    }

    public function getNiveaux(): ?Niveau
    {
        return $this->niveaux;
    }

    public function setNiveaux(?Niveau $niveaux): self
    {
        $this->niveaux = $niveaux;

        return $this;
    }

    public function getSeries(): ?Serie
    {
        return $this->series;
    }

    public function setSeries(?Serie $series): self
    {
        $this->series = $series;

        return $this;
    }

    

  
    /**
     * @return Collection|EmploisDuTepms[]
     */
    public function getEmploisDuTepms(): Collection
    {
        return $this->emploisDuTepms;
    }

    public function addEmploisDuTepm(EmploisDuTepms $emploisDuTepm): self
    {
        if (!$this->emploisDuTepms->contains($emploisDuTepm)) {
            $this->emploisDuTepms[] = $emploisDuTepm;
            $emploisDuTepm->setClasses($this);
        }

        return $this;
    }

    public function removeEmploisDuTepm(EmploisDuTepms $emploisDuTepm): self
    {
        if ($this->emploisDuTepms->removeElement($emploisDuTepm)) {
            // set the owning side to null (unless already changed)
            if ($emploisDuTepm->getClasses() === $this) {
                $emploisDuTepm->setClasses(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Inscrire[]
     */
    public function getInscrires(): Collection
    {
        return $this->inscrires;
    }

    public function addInscrire(Inscrire $inscrire): self
    {
        if (!$this->inscrires->contains($inscrire)) {
            $this->inscrires[] = $inscrire;
            $inscrire->setClasses($this);
        }

        return $this;
    }

    public function removeInscrire(Inscrire $inscrire): self
    {
        if ($this->inscrires->removeElement($inscrire)) {
            // set the owning side to null (unless already changed)
            if ($inscrire->getClasses() === $this) {
                $inscrire->setClasses(null);
            }
        }

        return $this;
    }

    public function getMontantIns(): ?int
    {
        return $this->montantIns;
    }

    public function setMontantIns(int $montantIns): self
    {
        $this->montantIns = $montantIns;

        return $this;
    }

    public function getMontantMens(): ?int
    {
        return $this->montantMens;
    }

    public function setMontantMens(int $montantMens): self
    {
        $this->montantMens = $montantMens;

        return $this;
    }

    /**
     * @return Collection|ClasseMatiere[]
     */
    public function getClasseMatieres(): Collection
    {
        return $this->classeMatieres;
    }

    public function addClasseMatiere(ClasseMatiere $classeMatiere): self
    {
        if (!$this->classeMatieres->contains($classeMatiere)) {
            $this->classeMatieres[] = $classeMatiere;
            $classeMatiere->setClasses($this);
        }

        return $this;
    }

    public function removeClasseMatiere(ClasseMatiere $classeMatiere): self
    {
        if ($this->classeMatieres->removeElement($classeMatiere)) {
            // set the owning side to null (unless already changed)
            if ($classeMatiere->getClasses() === $this) {
                $classeMatiere->setClasses(null);
            }
        }

        return $this;
    }

  
  

  


   
}
