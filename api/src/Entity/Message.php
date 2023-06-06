<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Controller\CreateMessageController;
use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ApiResource(mercure:true, operations: [
    new Get(),
    new Post(
        controller: CreateMessageController::class
    )
])]
#[ORM\HasLifecycleCallbacks()]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['message_notification'])]
    private ?int $id = null;

    #[ORM\Column(length: 3000, nullable: true)]
    #[Groups(['message_notification'])]
    private ?string $content = null;

    #[ORM\Column(type: 'boolean', options: ['default' => 'false'], nullable: false)]
    #[Groups(['message_notification'])]
    private ?bool $isRead = null;

    #[ORM\ManyToOne(inversedBy: 'received')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['message_notification'])]
    private ?User $recipient = null;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['message_notification'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'sended')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['message_notification'])]
    private ?User $sent = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[Groups(['message_notification'])]
    private ?Conversation $conversation = null;

    #[ORM\PrePersist]
    public function onCreate(): void
    {
        $this->setCreatedAt(new \DateTimeImmutable());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function isIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $recipient): self
    {
        $this->recipient = $recipient;

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

    public function getSent(): ?User
    {
        return $this->sent;
    }

    public function setSent(?User $sent): self
    {
        $this->sent = $sent;

        return $this;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }

}
