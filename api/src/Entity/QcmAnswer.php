<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\QcmAnswerRepository;
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

    #[ORM\ManyToOne(inversedBy: 'qcmAnswers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Qcm $question = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Qcm $qcm = null;

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

    public function getQuestion(): ?Qcm
    {
        return $this->question;
    }

    public function setQuestion(?Qcm $question): self
    {
        $this->question = $question;

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
}
