<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(length: 255)]
    private ?string $ref = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tva $tva = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Picturs::class, orphanRemoval: true)]
    private Collection $picturs;

    public function __construct()
    {
        $this->picturs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): static
    {
        $this->ref = $ref;

        return $this;
    }

    public function getTva(): ?Tva
    {
        return $this->tva;
    }

    public function setTva(?Tva $tva): static
    {
        $this->tva = $tva;

        return $this;
    }

    public function getImage(): ?string
        {
            return $this->image;
        }

        public function setImage(?string $image): self
        {
            $this->image = $image;

            return $this;
        }

    /**
     * @return Collection<int, Picturs>
     */
    public function getPicturs(): Collection
    {
        return $this->picturs;
    }

    public function addPictur(Picturs $pictur): static
    {
        if (!$this->picturs->contains($pictur)) {
            $this->picturs->add($pictur);
            $pictur->setProduct($this);
        }

        return $this;
    }

    public function removePictur(Picturs $pictur): static
    {
        if ($this->picturs->removeElement($pictur)) {
            // set the owning side to null (unless already changed)
            if ($pictur->getProduct() === $this) {
                $pictur->setProduct(null);
            }
        }

        return $this;
    }
}
