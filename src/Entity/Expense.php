<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ExpenseRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExpenseRepository::class)]
class Expense
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['api_dashboard'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['api_dashboard'])]
    #[Assert\NotBlank(message: 'Ce champ est obligatoire')]
    #[Assert\Type('float', message: '{{ value }} n\'est pas un nombre valide')]
    private ?float $amount = null;

    #[ORM\ManyToOne(inversedBy: 'expenses')]
    private ?User $user = null;

    #[ORM\Column(length: 80)]
    #[Assert\NotBlank(message: 'Ce champ est obligatoire')]
    #[Groups(['api_dashboard'])]
    private ?string $label = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['api_dashboard'])]
    private ?bool $isPaid = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['api_dashboard'])]
    private ?\DateTimeImmutable $paidAt = null;

    #[ORM\ManyToOne(inversedBy: 'expenses')]
    #[Groups(['api_dashboard'])]
    private ?Category $category = null;

    #[ORM\Column]
    #[Groups(['api_dashboard'])]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function isIsPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setIsPaid(?bool $isPaid): self
    {
        $this->isPaid = $isPaid;

        return $this;
    }

    public function getPaidAt(): ?\DateTimeImmutable
    {
        return $this->paidAt;
    }

    public function setPaidAt(?\DateTimeImmutable $paidAt): self
    {
        $this->paidAt = $paidAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
