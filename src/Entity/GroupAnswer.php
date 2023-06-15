<?php

namespace App\Entity;

use App\Repository\GroupAnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupAnswerRepository::class)]
class GroupAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\ManyToOne(inversedBy: 'groupAnswers')]
    private ?GroupQuestion $groupQuestion = null;

    #[ORM\ManyToOne(inversedBy: 'groupAnswers')]
    private ?Candidature $candidature = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getGroupQuestion(): ?GroupQuestion
    {
        return $this->groupQuestion;
    }

    public function setGroupQuestion(?GroupQuestion $groupQuestion): self
    {
        $this->groupQuestion = $groupQuestion;

        return $this;
    }

    public function getCandidature(): ?Candidature
    {
        return $this->candidature;
    }

    public function setCandidature(?Candidature $candidature): self
    {
        $this->candidature = $candidature;

        return $this;
    }
}
