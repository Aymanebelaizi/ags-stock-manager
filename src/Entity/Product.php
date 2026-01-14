<?php
namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id] #[ORM\GeneratedValue] #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)] private ?string $name = null;
    #[ORM\Column(length: 100)] private ?string $reference = null;
    #[ORM\Column] private ?float $purchasePrice = null;
    #[ORM\Column] private ?float $salesPrice = null;
    #[ORM\Column] private ?int $quantity = null;
    #[ORM\Column] private ?int $alertThreshold = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    // LA RELATION SUPPLIER A ÉTÉ SUPPRIMÉE ICI

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getReference(): ?string { return $this->reference; }
    public function setReference(string $reference): static { $this->reference = $reference; return $this; }
    public function getPurchasePrice(): ?float { return $this->purchasePrice; }
    public function setPurchasePrice(float $purchasePrice): static { $this->purchasePrice = $purchasePrice; return $this; }
    public function getSalesPrice(): ?float { return $this->salesPrice; }
    public function setSalesPrice(float $salesPrice): static { $this->salesPrice = $salesPrice; return $this; }
    public function getQuantity(): ?int { return $this->quantity; }
    public function setQuantity(int $quantity): static { $this->quantity = $quantity; return $this; }
    public function getAlertThreshold(): ?int { return $this->alertThreshold; }
    public function setAlertThreshold(int $alertThreshold): static { $this->alertThreshold = $alertThreshold; return $this; }
    public function getCategory(): ?Category { return $this->category; }
    public function setCategory(?Category $category): static { $this->category = $category; return $this; }
}