<?php

namespace App\Entity;

use App\Repository\WishlistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WishlistRepository::class)]
class Wishlist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'wishlist', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'wishlist', targetEntity: Figurine::class)]
    private Collection $figurine;

    public function __construct()
    {
        $this->figurine = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return MyCollection<int, Figurine>
     */
    public function getFigurine(): Collection
    {
        return $this->figurine;
    }

    public function addFigurine(Figurine $figurine): static
    {
        if (!$this->figurine->contains($figurine)) {
            $this->figurine->add($figurine);
            $figurine->setWishlist($this);
        }

        return $this;
    }

    public function removeFigurine(Figurine $figurine): static
    {
        if ($this->figurine->removeElement($figurine)) {
            // set the owning side to null (unless already changed)
            if ($figurine->getWishlist() === $this) {
                $figurine->setWishlist(null);
            }
        }

        return $this;
    }
}
