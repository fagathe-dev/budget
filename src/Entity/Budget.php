<?php

namespace App\Entity;

use App\Entity\Category;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BudgetRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BudgetRepository::class)]
class Budget
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['api_dashboard'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['api_dashboard'])]
    private ?int $amount = null;

    #[ORM\ManyToOne(inversedBy: 'budgets')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'budget')]
    #[Groups(['api_dashboard'])]
    private ?Category $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

}
