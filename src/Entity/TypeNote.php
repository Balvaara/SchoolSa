<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TypeNoteRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TypeNoteRepository::class)
 * @ApiResource(normalizationContext={"groups"={"tn"}})
 */
class TypeNote
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"note","tn"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"note","tn"})
     */
    private $libtn;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="typeNote")
     */
    private $notes;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibtn(): ?string
    {
        return $this->libtn;
    }

    public function setLibtn(string $libtn): self
    {
        $this->libtn = $libtn;

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
            $note->setTypeNote($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getTypeNote() === $this) {
                $note->setTypeNote(null);
            }
        }

        return $this;
    }

  
}
