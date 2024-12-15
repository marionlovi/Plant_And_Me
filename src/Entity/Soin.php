<?php

namespace App\Entity;

use App\Repository\SoinRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SoinRepository::class)]
class Soin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $cycle = null;

    #[ORM\Column(length: 255)]
    private ?string $lumiere = null;

    #[ORM\Column(length: 255)]
    private ?string $temp = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getCycle(): ?string
    {
        return $this->cycle;
    }

    public function setCycle(string $cycle): static
    {
        $this->cycle = $cycle;

        return $this;
    }

    public function getLumiere(): ?string
    {
        return $this->lumiere;
    }

    public function setLumiere(string $lumiere): static
    {
        $this->lumiere = $lumiere;

        return $this;
    }

    public function getTemp(): ?string
    {
        return $this->temp;
    }

    public function setTemp(string $temp): static
    {
        $this->temp = $temp;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }
}
