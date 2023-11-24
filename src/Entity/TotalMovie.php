<?php

namespace App\Entity;

use App\Repository\TotalMovieRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TotalMovieRepository::class)]
class TotalMovie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbMovie = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbMovie(): ?int
    {
        return $this->nbMovie;
    }

    public function setNbMovie(?int $nbMovie): static
    {
        $this->nbMovie = $nbMovie;

        return $this;
    }
}
