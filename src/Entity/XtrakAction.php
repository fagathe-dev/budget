<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\XtrakActionRepository;

#[ORM\Entity(repositoryClass: XtrakActionRepository::class)]
class XtrakAction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'actions')]
    private ?XtrakEvent $event = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $origin = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $message = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $target = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

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
    public function setOrigin(string $origin): self
    {
        $this->origin = $origin;

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
}
