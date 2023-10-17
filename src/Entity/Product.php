<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

  

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Cart::class)]
    private Collection $cart;

    public function __construct()
    {
        $this->cart = new ArrayCollection();
    }

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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getTupe(): ?string
    {
        return $this->type;
    }

    public function setTupe(string $tupe): static
    {
        $this->type = $tupe;

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

    

    /**
     * @return Collection<int, Cart>
     */
    public function getCart(): Collection
    {
        return $this->cart;
    }

    public function addCart(Cart $cart): static
    {
        if (!$this->cart->contains($cart)) {
            $this->cart->add($cart);
            $cart->setProduct($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): static
    {
        if ($this->cart->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getProduct() === $this) {
                $cart->setProduct(null);
            }
        }

        return $this;
    }
}
