<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SemestreRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=SemestreRepository::class)
 * @ApiResource(normalizationContext={"groups"={"sem"}})
 * @ApiFilter(SearchFilter::class, properties={"codesem": "exact"})
 */
class Semestre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *  @Groups({"dev","sem","note"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups({"dev","sem","note"})
     */
    private $codesem;

  

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="sems")
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

    public function getCodesem(): ?string
    {
        return $this->codesem;
    }

    public function setCodesem(string $codesem): self
    {
        $this->codesem = $codesem;

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
            $note->setSems($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getSems() === $this) {
                $note->setSems(null);
            }
        }

        return $this;
    }

}
