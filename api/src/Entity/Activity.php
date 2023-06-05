<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ActivityRepository::class)]
#[ApiResource]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 1000)]
    private ?string $name = null;

    #[ORM\Column(length: 3000)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThan('today')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $starting_time = null;

    #[ORM\Column]
    private ?bool $outdoor = null;

    #[ORM\Column(type: Types::BLOB, nullable:true)]
    private $image = null;

    #[ORM\Column]
    private ?bool $open = null;

    #[ORM\ManyToOne(inversedBy: 'initiateActivities')]
    private ?User $owner = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'joinActivities')]
    private Collection $participants;

    #[ORM\Column]
    private ?int $numberOfParticipants = null;

    #[ORM\OneToMany(mappedBy: 'activity', targetEntity: ActivityQuestion::class, orphanRemoval: true, cascade:['persist'])]
    private Collection $activityQuestions;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[ORM\OneToMany(mappedBy: 'activity', targetEntity: Conversation::class)]
    private Collection $conversations;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->activityQuestions = new ArrayCollection();
        $this->conversations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStartingTime(): ?\DateTimeInterface
    {
        return $this->starting_time;
    }

    public function setStartingTime(\DateTimeInterface $starting_time): self
    {
        $this->starting_time = $starting_time;

        return $this;
    }

    public function isOutdoor(): ?bool
    {
        return $this->outdoor;
    }

    public function setOutdoor(bool $outdoor): self
    {
        $this->outdoor = $outdoor;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function isOpen(): ?bool
    {
        return $this->open;
    }

    public function setOpen(bool $open): self
    {
        $this->open = $open;

        return $this;
    }


    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            $participant->addJoinActivity($this);
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            $participant->removeJoinActivity($this);
        }

        return $this;
    }


    public function getNumberOfParticipants(): ?int
    {
        return $this->numberOfParticipants;
    }

    public function setNumberOfParticipants(int $numberOfParticipants): self
    {
        $this->numberOfParticipants = $numberOfParticipants;

        return $this;
    }

    /**
     * @return Collection<int, ActivityQuestion>
     */
    public function getActivityQuestions(): Collection
    {
        return $this->activityQuestions;
    }

    public function addActivityQuestion(ActivityQuestion $activityQuestion): self
    {
        if (!$this->activityQuestions->contains($activityQuestion)) {
            $this->activityQuestions->add($activityQuestion);
            $activityQuestion->setActivity($this);
        }

        return $this;
    }

    public function removeActivityQuestion(ActivityQuestion $activityQuestion): self
    {
        if ($this->activityQuestions->removeElement($activityQuestion)) {
            // set the owning side to null (unless already changed)
            if ($activityQuestion->getActivity() === $this) {
                $activityQuestion->setActivity(null);
            }
        }

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return Collection<int, Conversation>
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations->add($conversation);
            $conversation->setActivity($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->removeElement($conversation)) {
            // set the owning side to null (unless already changed)
            if ($conversation->getActivity() === $this) {
                $conversation->setActivity(null);
            }
        }

        return $this;
    }
}
