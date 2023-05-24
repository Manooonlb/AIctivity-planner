<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\QcmAnswerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QcmAnswerRepository::class)]
#[ApiResource]
class QcmAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 1000)]
    private ?string $answer = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Qcm $qcm = null;

    #[ORM\OneToMany(mappedBy: 'answer', targetEntity: ActivityQuestion::class, orphanRemoval: true)]
    private Collection $activityQuestions;

    public function __construct()
    {
        $this->activityQuestions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }
   

    public function getQcm(): ?Qcm
    {
        return $this->qcm;
    }

    public function setQcm(?Qcm $qcm): self
    {
        $this->qcm = $qcm;

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
            $activityQuestion->setAnswer($this);
        }

        return $this;
    }

    public function removeActivityQuestion(ActivityQuestion $activityQuestion): self
    {
        if ($this->activityQuestions->removeElement($activityQuestion)) {
            // set the owning side to null (unless already changed)
            if ($activityQuestion->getAnswer() === $this) {
                $activityQuestion->setAnswer(null);
            }
        }

        return $this;
    }
}
