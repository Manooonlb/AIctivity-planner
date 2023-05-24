<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ActivityQuestionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityQuestionRepository::class)]
#[ApiResource]
class ActivityQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'activityQuestions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Activity $activity = null;

    #[ORM\ManyToOne(inversedBy: 'activityQuestions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Qcm $question = null;

    #[ORM\ManyToOne(inversedBy: 'activityQuestions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?QcmAnswer $answer = null;

    #[ORM\ManyToOne(inversedBy: 'activityQuestions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    public function getQuestion(): ?Qcm
    {
        return $this->question;
    }

    public function setQuestion(?Qcm $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?QcmAnswer
    {
        return $this->answer;
    }

    public function setAnswer(?QcmAnswer $answer): self
    {
        $this->answer = $answer;

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
}
