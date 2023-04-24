<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\XtrakLogRepository;

#[ORM\Entity(repositoryClass: XtrakLogRepository::class)]
class XtrakLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'logs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?XtrakEvent $event = null;

    #[ORM\Column(nullable: true)]
    private array $data = [];

    #[ORM\Column(nullable: true)]
    private ?bool $success = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $message = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $origin = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $target = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne]
    private ?User $user = null;
    
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
     * getEvent
     *
     * @return XtrakEvent
     */
    public function getEvent(): ?XtrakEvent
    {
        return $this->event;
    }
    
    /**
     * setEvent
     *
     * @param  mixed $event
     * @return self
     */
    public function setEvent(?XtrakEvent $event): self
    {
        $this->event = $event;

        return $this;
    }
    
    /**
     * getData
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
    
    /**
     * setData
     *
     * @param  mixed $data
     * @return self
     */
    public function setData(?array $data): self
    {
        $this->data = $data;

        return $this;
    }
    
    /**
     * isSuccess
     *
     * @return bool
     */
    public function isSuccess(): ?bool
    {
        return $this->success;
    }
    
    /**
     * setSuccess
     *
     * @param  mixed $success
     * @return self
     */
    public function setSuccess(?bool $success): self
    {
        $this->success = $success;

        return $this;
    }
    
    /**
     * getMessage
     *
     * @return string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }
    
    /**
     * setMessage
     *
     * @param  mixed $message
     * @return self
     */
    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }
    
    /**
     * getOrigin
     *
     * @return string
     */
    public function getOrigin(): ?string
    {
        return $this->origin;
    }
    
    /**
     * setOrigin
     *
     * @param  mixed $origin
     * @return self
     */
    public function setOrigin(?string $origin): self
    {
        $this->origin = $origin;

        return $this;
    }
    
    /**
     * getTarget
     *
     * @return string
     */
    public function getTarget(): ?string
    {
        return $this->target;
    }
    
    /**
     * setTarget
     *
     * @param  mixed $target
     * @return self
     */
    public function setTarget(?string $target): self
    {
        $this->target = $target;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
