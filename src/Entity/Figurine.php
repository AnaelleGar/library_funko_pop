<?php

namespace App\Entity;

use App\Repository\FigurineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FigurineRepository::class)]
class Figurine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'figurines')]
    private ?Family $family = null;

    #[ORM\ManyToOne(inversedBy: 'figurine')]
    private ?Wishlist $wishlist = null;

    #[ORM\ManyToOne(inversedBy: 'figurine')]
    private ?MyCollection $collection = null;

    #[ORM\ManyToOne(inversedBy: 'figurines')]
    private ?MyCollection $mycollection = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getFamily(): ?Family
    {
        return $this->family;
    }

    public function setFamily(?Family $family): static
    {
        $this->family = $family;

        return $this;
    }

    public function getWishlist(): ?Wishlist
    {
        return $this->wishlist;
    }

    public function setWishlist(?Wishlist $wishlist): static
    {
        $this->wishlist = $wishlist;

        return $this;
    }

    public function getCollection(): ?MyCollection
    {
        return $this->collection;
    }

    public function setCollection(?MyCollection $collection): static
    {
        $this->collection = $collection;

        return $this;
    }

    public function getMycollection(): ?MyCollection
    {
        return $this->mycollection;
    }

    public function setMycollection(?MyCollection $mycollection): static
    {
        $this->mycollection = $mycollection;

        return $this;
    }
}
