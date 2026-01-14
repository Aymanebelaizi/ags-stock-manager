<?php

namespace App\Entity;

use App\Repository\PurchaseRequestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseRequestRepository::class)]
class PurchaseRequest
{
    #[ORM\Id] #[ORM\GeneratedValue] #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(length: 50)]
    private ?string $status = 'pending';

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $justification = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "requested_by_id", referencedColumnName: "id", nullable: false)]
    private ?User $requestedBy = null;

    public function __construct() { $this->createdAt = new \DateTimeImmutable(); }

    public function getId(): ?int { return $this->id; }
    public function getProduct(): ?Product { return $this->product; }
    public function setProduct(?Product $product): static { $this->product = $product; return $this; }
    public function getQuantity(): ?int { return $this->quantity; }
    public function setQuantity(int $quantity): static { $this->quantity = $quantity; return $this; }
    public function getStatus(): ?string { return $this->status; }
    public function setStatus(string $status): static { $this->status = $status; return $this; }
    public function getJustification(): ?string { return $this->justification; }
    public function setJustification(?string $justification): static { $this->justification = $justification; return $this; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this;}
    public function getRequestedBy(): ?User { return $this->requestedBy; }
    public function setRequestedBy(?User $requestedBy): static { $this->requestedBy = $requestedBy; return $this; }
}