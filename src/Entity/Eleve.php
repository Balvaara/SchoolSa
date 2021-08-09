<?php

namespace App\Entity;

use App\Entity\Note;
use App\Entity\Payement;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EleveRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
//use ApiPlatform\Core\Api\FilterInterface;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=EleveRepository::class)
 * @ApiResource(normalizationContext={"groups"={"ele"}})
 * @ApiFilter(SearchFilter::class, properties={"matriculeEleve": "exact"})

 */
class Eleve
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"ele","ins","note"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups({"ele","ins","pay","note"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups({"ele","ins","pay","note"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="date")
     *  @Groups({"ele","ins","pay","note"})
     */
    private $datenais;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups({"ele","ins","pay","note"})
     */
    private $lieunaiss;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups({"ele","ins","pay","note"})
     */
    private $sexe;


    /**
     * @ORM\ManyToOne(targetEntity=Parrent::class, inversedBy="eleves",cascade={"persist"})
       * @Groups({"ele","ins"})	
     * 
     */
    private $parrents;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="eleves")
     */
    private $notes;

 

    /**
     * @ORM\Column(type="string", length=255)
      *	@Groups({"ins","ele","note","pay"})
     */
    private $matriculeEleve;

    /**
     * @ORM\OneToMany(targetEntity=Inscrire::class, mappedBy="eleves",cascade={"remove"})
	*@Groups({"ins","ele","note"})
     */
    private $inscrires;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->inscrires = new ArrayCollection();
    }

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDatenais(): ?\DateTimeInterface
    {
        return $this->datenais;
    }

    public function setDatenais(\DateTimeInterface $datenais): self
    {
        $this->datenais = $datenais;

        return $this;
    }

    public function getLieunaiss(): ?string
    {
        return $this->lieunaiss;
    }

    public function setLieunaiss(string $lieunaiss): self
    {
        $this->lieunaiss = $lieunaiss;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

   

    public function getParrents(): ?Parrent
    {
        return $this->parrents;
    }

    public function setParrents(?Parrent $parrents): self
    {
        $this->parrents = $parrents;

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
            $note->setEleves($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getEleves() === $this) {
                $note->setEleves(null);
            }
        }

        return $this;
    }


    public function getMatriculeEleve(): ?string
    {
        return $this->matriculeEleve;
    }

    public function setMatriculeEleve(string $matriculeEleve): self
    {
        $this->matriculeEleve = $matriculeEleve;

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
            $inscrire->setEleves($this);
        }

        return $this;
    }

    public function removeInscrire(Inscrire $inscrire): self
    {
        if ($this->inscrires->removeElement($inscrire)) {
            // set the owning side to null (unless already changed)
            if ($inscrire->getEleves() === $this) {
                $inscrire->setEleves(null);
            }
        }

        return $this;
    }

  
}
