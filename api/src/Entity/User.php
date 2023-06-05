<?php

namespace App\Entity;

use App\Entity\ProfilePicture;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
#[ApiResource(mercure: true)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['message_notification'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['message_notification'])]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\LessThan('-18 years', message: '🔞 NOPE TOO YOUNG 🔞')]
    private ?\DateTimeInterface $birthday = null;


    #[ORM\OneToOne(targetEntity: ProfilePicture::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?ProfilePicture $profilePicture = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(length: 255)]
    #[Groups(['message_notification'])]
    private ?string $username = null;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Activity::class)]
    private Collection $initiateActivities;

    #[ORM\ManyToMany(targetEntity: Activity::class, inversedBy: 'participants')]
    private Collection $joinActivities;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: ActivityQuestion::class, orphanRemoval: true)]
    private Collection $activityQuestions;

    #[ORM\OneToMany(mappedBy: 'recipient', targetEntity: Message::class)]
    private Collection $received;

    #[ORM\OneToMany(mappedBy: 'sent', targetEntity: Message::class)]
    private Collection $sended;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatarPath = null;

    public function __construct()
    {
        $this->initiateActivities = new ArrayCollection();
        $this->joinActivities = new ArrayCollection();
        $this->activityQuestions = new ArrayCollection();
        $this->received = new ArrayCollection();
        $this->sended = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getProfilePicture(): ?ProfilePicture
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?ProfilePicture $profilePicture): self
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }


    /**
     * @return Collection<int, Activity>
     */
    public function getInitiateActivities(): Collection
    {
        return $this->initiateActivities;
    }

    public function addInitiateActivity(Activity $initiateActivity): self
    {
        if (!$this->initiateActivities->contains($initiateActivity)) {
            $this->initiateActivities->add($initiateActivity);
            $initiateActivity->setOwner($this);
        }

        return $this;
    }

    public function removeInitiateActivity(Activity $initiateActivity): self
    {
        if ($this->initiateActivities->removeElement($initiateActivity)) {
            // set the owning side to null (unless already changed)
            if ($initiateActivity->getOwner() === $this) {
                $initiateActivity->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getJoinActivities(): Collection
    {
        return $this->joinActivities;
    }

    public function addJoinActivity(Activity $joinActivity): self
    {
        if (!$this->joinActivities->contains($joinActivity)) {
            $this->joinActivities->add($joinActivity);
        }

        return $this;
    }

    public function removeJoinActivity(Activity $joinActivity): self
    {
        $this->joinActivities->removeElement($joinActivity);

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
            $activityQuestion->setOwner($this);
        }

        return $this;
    }

    public function removeActivityQuestion(ActivityQuestion $activityQuestion): self
    {
        if ($this->activityQuestions->removeElement($activityQuestion)) {
            // set the owning side to null (unless already changed)
            if ($activityQuestion->getOwner() === $this) {
                $activityQuestion->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getReceived(): Collection
    {
        return $this->received;
    }

    public function addReceived(Message $received): self
    {
        if (!$this->received->contains($received)) {
            $this->received->add($received);
            $received->setRecipient($this);
        }

        return $this;
    }

    public function removeReceived(Message $received): self
    {
        if ($this->received->removeElement($received)) {
            // set the owning side to null (unless already changed)
            if ($received->getRecipient() === $this) {
                $received->setRecipient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getSended(): Collection
    {
        return $this->sended;
    }

    public function addSended(Message $sended): self
    {
        if (!$this->sended->contains($sended)) {
            $this->sended->add($sended);
            $sended->setSent($this);
        }

        return $this;
    }

    public function removeSended(Message $sended): self
    {
        if ($this->sended->removeElement($sended)) {
            // set the owning side to null (unless already changed)
            if ($sended->getSent() === $this) {
                $sended->setSent(null);
            }
        }

        return $this;
    }

    public function getAvatarPath(): ?string
    {
        return $this->avatarPath;
    }

    public function setAvatarPath(?string $avatarPath): self
    {
        $this->avatarPath = $avatarPath;

        return $this;
    }

}