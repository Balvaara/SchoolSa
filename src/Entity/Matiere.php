<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MatiereRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=MatiereRepository::class)
 * @ApiResource(normalizationContext={"groups"={"mat"}})
 * @ApiFilter(SearchFilter::class, properties={"libellemat": "exact"})
 */
class Matiere
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"emp","mat","pro"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"emp","mat","cl_mt","note"})
     */
    private $libellemat;


   

    /**
     * @ORM\ManyToMany(targetEntity=Professeur::class, mappedBy="mats")
     * @Groups({"mat"})
     */
    private $professeurs;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="mats")
     */
    private $notes;

    /**
     * @ORM\OneToMany(targetEntity=EmploisDuTepms::class, mappedBy="mats")
     */
    private $emploisDuTepms;

    /**
     * @ORM\OneToMany(targetEntity=ClasseMatiere::class, mappedBy="matieres")
     * @Groups({"mat"})
     */
    private $classeMatieres;

    
   

    public function __construct()
    {
        $this->professeurs = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->emploisDuTepms = new ArrayCollection();
        $this->classeMatieres = new ArrayCollection();
    }

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibellemat(): ?string
    {
        return $this->libellemat;
    }

    public function setLibellemat(string $libellemat): self
    {
        $this->libellemat = $libellemat;

        return $this;
    }

    
 
    /**
     * @return Collection|Professeur[]
     */
    public function getProfesseurs(): Collection
    {
        return $this->professeurs;
    }

    public function addProfesseur(Professeur $professeur): self
    {
        if (!$this->professeurs->contains($professeur)) {
            $this->professeurs[] = $professeur;
            $professeur->addMat($this);
        }

        return $this;
    }

    public function removeProfesseur(Professeur $professeur): self
    {
        if ($this->professeurs->removeElement($professeur)) {
            $professeur->removeMat($this);
        }

        return $this;
    }

    /**
     * @return Collection|Note[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setMats($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getMats() === $this) {
                $note->setMats(null);
            }
        }

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
            $emploisDuTepm->setMats($this);
        }

        return $this;
    }

    public function removeEmploisDuTepm(EmploisDuTepms $emploisDuTepm): self
    {
        if ($this->emploisDuTepms->removeElement($emploisDuTepm)) {
            // set the owning side to null (unless already changed)
            if ($emploisDuTepm->getMats() === $this) {
                $emploisDuTepm->setMats(null);
            }
        }

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
            $classeMatiere->setMatieres($this);
        }

        return $this;
    }

    public function removeClasseMatiere(ClasseMatiere $classeMatiere): self
    {
        if ($this->classeMatieres->removeElement($classeMatiere)) {
            // set the owning side to null (unless already changed)
            if ($classeMatiere->getMatieres() === $this) {
                $classeMatiere->setMatieres(null);
            }
        }

        return $this;
    }

    
    
}
