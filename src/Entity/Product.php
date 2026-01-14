<?php
namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $purchasePrice = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $salesPrice = null;

    #[ORM\Column(type: 'integer', options: ['default' => 5])]
    private ?int $alertThreshold = 5;

    #[ORM\Id] #[ORM\GeneratedValue] #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $reference = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Supplier $supplier = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: StockMovement::class)]
    private Collection $stockMovements;

    public function __construct() {
        $this->stockMovements = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function getReference(): ?string { return $this->reference; }
    public function setReference(?string $reference): self { $this->reference = $reference; return $this; }
    public function getQuantity(): ?int { return $this->quantity; }
    public function setQuantity(int $quantity): self { $this->quantity = $quantity; return $this; }
    public function getCategory(): ?Category { return $this->category; }
    public function setCategory(?Category $category): self { $this->category = $category; return $this; }
    public function getSupplier(): ?Supplier { return $this->supplier; }
    public function setSupplier(?Supplier $supplier): self { $this->supplier = $supplier; return $this; }
    public function getStockMovements(): Collection { return $this->stockMovements; }
    public function getPurchasePrice(): ?string { return $this->purchasePrice; }
    public function setPurchasePrice(?string $purchasePrice): self { $this->purchasePrice = $purchasePrice; return $this; }
    public function getSalesPrice(): ?string { return $this->salesPrice; }
    public function setSalesPrice(?string $salesPrice): self { $this->salesPrice = $salesPrice; return $this; }
    public function getAlertThreshold(): ?int { return $this->alertThreshold; }
    public function setAlertThreshold(int $alertThreshold): self { $this->alertThreshold = $alertThreshold; return $this; }
}