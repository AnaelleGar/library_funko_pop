<?php

namespace App\Entity;

use App\Repository\CollectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CollectionRepository::class)]
class MyCollection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'collection', targetEntity: Figurine::class)]
    private MyCollection $figurine;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'mycollection', targetEntity: Figurine::class)]
    private Collection $figurines;

    public function __construct()
    {
        $this->figurine = new ArrayCollection();
        $this->figurines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return MyCollection
     */
    public function getFigurine(): self
    {
        return $this->figurine;
    }

    public function addFigurine(Figurine $figurine): static
    {
        if (!$this->figurine->contains($figurine)) {
            $this->figurine->add($figurine);
            $figurine->setCollection($this);
        }

        return $this;
    }

    public function removeFigurine(Figurine $figurine): static
    {
        if ($this->figurine->removeElement($figurine)) {
            // set the owning side to null (unless already changed)
            if ($figurine->getCollection() === $this) {
                $figurine->setCollection(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Figurine>
     */
    public function getFigurines(): Collection
    {
        return $this->figurines;
    }
}
