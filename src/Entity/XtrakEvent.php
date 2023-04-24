<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\XtrakEventRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: XtrakEventRepository::class)]
class XtrakEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    private ?string $label = null;

    #[ORM\Column(length: 50)]
    private ?string $perimeter = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: XtrakAction::class)]
    private Collection $actions;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: XtrakLog::class)]
    private Collection $logs;

    public function __construct()
    {
        $this->actions = new ArrayCollection();
        $this->logs = new ArrayCollection();
    }
    
    /**
     * getId
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    
    /**
     * getLabel
     *
     * @return string
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }
    
    /**
     * setLabel
     *
     * @param  mixed $label
     * @return self
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }
    
    /**
     * getPerimeter
     *
     * @return string
     */
    public function getPerimeter(): ?string
    {
        return $this->perimeter;
    }
    
    /**
     * setPerimeter
     *
     * @param  mixed $perimeter
     * @return self
     */
    public function setPerimeter(string $perimeter): self
    {
        $this->perimeter = $perimeter;

        return $this;
    }
    
    /**
     * getDescription
     *
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
    
    /**
     * setDescription
     *
     * @param  mixed $description
     * @return self
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
    
    /**
     * getCreatedAt
     *
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }
    
    /**
     * setCreatedAt
     *
     * @param  mixed $createdAt
     * @return self
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    
    /**
     * getUpdatedAt
     *
     * @return DateTimeImmutable
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
    
    /**
     * setUpdatedAt
     *
     * @param  mixed $updatedAt
     * @return self
     */
    public function setUpdatedAt(?DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, XtrakAction>
     */
    public function getActions(): Collection
    {
        return $this->actions;
    }
    
    /**
     * addAction
     *
     * @param  mixed $action
     * @return self
     */
    public function addAction(XtrakAction $action): self
    {
        if (!$this->actions->contains($action)) {
            $this->actions->add($action);
            $action->setEvent($this);
        }

        return $this;
    }
    
    /**
     * removeAction
     *
     * @param  mixed $action
     * @return self
     */
    public function removeAction(XtrakAction $action): self
    {
        if ($this->actions->removeElement($action)) {
            // set the owning side to null (unless already changed)
            if ($action->getEvent() === $this) {
                $action->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, XtrakLog>
     */
    public function getLogs(): Collection
    {
        return $this->logs;
    }

    public function addLog(XtrakLog $log): self
    {
        if (!$this->logs->contains($log)) {
            $this->logs->add($log);
            $log->setEvent($this);
        }

        return $this;
    }
    
    /**
     * removeLog
     *
     * @param  mixed $log
     * @return self
     */
    public function removeLog(XtrakLog $log): self
    {
        if ($this->logs->removeElement($log)) {
            // set the owning side to null (unless already changed)
            if ($log->getEvent() === $this) {
                $log->setEvent(null);
            }
        }

        return $this;
    }
}
