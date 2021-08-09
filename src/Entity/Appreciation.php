<?php

namespace App\Entity;

use App\Repository\AppreciationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AppreciationRepository::class)
 */
class Appreciation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libapp;

    /**
     * @ORM\Column(type="integer")
     */
    private $valInf;

    /**
     * @ORM\Column(type="integer")
     */
    private $valSup;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibapp(): ?string
    {
        return $this->libapp;
    }

    public function setLibapp(string $libapp): self
    {
        $this->libapp = $libapp;

        return $this;
    }

    public function getValInf(): ?int
    {
        return $this->valInf;
    }

    public function setValInf(int $valInf): self
    {
        $this->valInf = $valInf;

        return $this;
    }

    public function getValSup(): ?int
    {
        return $this->valSup;
    }

    public function setValSup(int $valSup): self
    {
        $this->valSup = $valSup;

        return $this;
    }
}
