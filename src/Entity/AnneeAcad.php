<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AnneeAcadRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AnneeAcadRepository::class)
 * @ApiResource(normalizationContext={"groups"={"ann"}})
 */
class AnneeAcad
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"ins","ann","pay"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups({"ins","ann","pay"})
     */
    private $libAnn;

    /**
     * @ORM\OneToMany(targetEntity=Inscrire::class, mappedBy="session")
     */
    private $inscrires;

    /**
     * @ORM\OneToMany(targetEntity=EmploisDuTepms::class, mappedBy="annees")
     */
    private $emploisDuTepms;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="annee")
     */
    private $notes;

    public function __construct()
    {
        $this->inscrires = new ArrayCollection();
        $this->emploisDuTepms = new ArrayCollection();
        $this->notes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibAnn(): ?string
    {
        return $this->libAnn;
    }

    public function setLibAnn(string $libAnn): self
    {
        $this->libAnn = $libAnn;

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
            $inscrire->setSession($this);
        }

        return $this;
    }

    public function removeInscrire(Inscrire $inscrire): self
    {
        if ($this->inscrires->removeElement($inscrire)) {
            // set the owning side to null (unless already changed)
            if ($inscrire->getSession() === $this) {
                $inscrire->setSession(null);
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
            $emploisDuTepm->setAnnees($this);
        }

        return $this;
    }

    public function removeEmploisDuTepm(EmploisDuTepms $emploisDuTepm): self
    {
        if ($this->emploisDuTepms->removeElement($emploisDuTepm)) {
            // set the owning side to null (unless already changed)
            if ($emploisDuTepm->getAnnees() === $this) {
                $emploisDuTepm->setAnnees(null);
            }
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
            $note->setAnnee($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getAnnee() === $this) {
                $note->setAnnee(null);
            }
        }

        return $this;
    }
}
