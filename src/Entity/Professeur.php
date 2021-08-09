<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use App\Repository\ProfesseurRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=ProfesseurRepository::class)
 * @ApiResource(normalizationContext={"groups"={"pro"}})
 *  @ApiFilter(SearchFilter::class, properties={"matriculepr": "exact"})
 */
class Professeur
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
     * @Groups({"emp","mat","pro"})
     */
    private $prenompr;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"emp","mat","pro"})
     */
    private $nompr;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"emp","mat","pro"})
     */
    private $adressepr;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"emp","mat","pro"})
     */
    private $telpr;

    /**
     * @ORM\Column(type="date", length=255)
     * @Groups({"emp","mat","pro"})
     */
    private $datenaisspr;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"emp","mat","pro"})
     */
    private $lieunaisspr;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"emp","mat","pro"})
     */
    private $matriculepr;

    /**
     * @ORM\ManyToMany(targetEntity=Matiere::class, inversedBy="professeurs")
     * @Groups({"pro"})
     * @ORM\JoinTable(name="professeur_matiere")
     */
    private $mats;

    /**
     * @ORM\OneToMany(targetEntity=EmploisDuTepms::class, mappedBy="professeurs")
     */
    private $emploisDuTepms;

    public function __construct()
    {
        $this->mats = new ArrayCollection();
        $this->emploisDuTepms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenompr(): ?string
    {
        return $this->prenompr;
    }

    public function setPrenompr(string $prenompr): self
    {
        $this->prenompr = $prenompr;

        return $this;
    }

    public function getNompr(): ?string
    {
        return $this->nompr;
    }

    public function setNompr(string $nompr): self
    {
        $this->nompr = $nompr;

        return $this;
    }

    public function getAdressepr(): ?string
    {
        return $this->adressepr;
    }

    public function setAdressepr(string $adressepr): self
    {
        $this->adressepr = $adressepr;

        return $this;
    }

    public function getTelpr(): ?string
    {
        return $this->telpr;
    }

    public function setTelpr(string $telpr): self
    {
        $this->telpr = $telpr;

        return $this;
    }

    public function getDatenaisspr():  ?\DateTimeInterface
    {
        return $this->datenaisspr;
    }

    public function setDatenaisspr(\DateTimeInterface $datenaisspr): self
    {
        $this->datenaisspr = $datenaisspr;

        return $this;
    }

    public function getLieunaisspr(): ?string
    {
        return $this->lieunaisspr;
    }

    public function setLieunaisspr(string $lieunaisspr): self
    {
        $this->lieunaisspr = $lieunaisspr;

        return $this;
    }

    public function getMatriculepr(): ?string
    {
        return $this->matriculepr;
    }

    public function setMatriculepr(string $matriculepr): self
    {
        $this->matriculepr = $matriculepr;

        return $this;
    }

    /**
     * @return Collection|Matiere[]
     */
    public function getMats(): Collection
    {
        return $this->mats;
    }

    public function addMat(Matiere $mat): self
    {
        if (!$this->mats->contains($mat)) {
            $this->mats[] = $mat;
        }

        return $this;
    }

    public function removeMat(Matiere $mat): self
    {
        $this->mats->removeElement($mat);

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
            $emploisDuTepm->setProfesseurs($this);
        }

        return $this;
    }

    public function removeEmploisDuTepm(EmploisDuTepms $emploisDuTepm): self
    {
        if ($this->emploisDuTepms->removeElement($emploisDuTepm)) {
            // set the owning side to null (unless already changed)
            if ($emploisDuTepm->getProfesseurs() === $this) {
                $emploisDuTepm->setProfesseurs(null);
            }
        }

        return $this;
    }
}
