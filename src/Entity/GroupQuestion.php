<?php

namespace App\Entity;

use App\Repository\GroupQuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupQuestionRepository::class)]
class GroupQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $text = null;

    #[ORM\Column]
    private ?bool $required = null;

    #[ORM\ManyToOne(inversedBy: 'groupQuestions')]
    private ?Group $groupe = null;

    #[ORM\OneToMany(mappedBy: 'groupQuestion', targetEntity: GroupAnswer::class)]
    private Collection $groupAnswers;

    public function __construct()
    {
        $this->groupAnswers = new ArrayCollection();
    }

    
    public function __toString() {
        return $this->text;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    // Fix EasyAdmin:
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
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

    public function isRequired(): ?bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): self
    {
        $this->required = $required;

        return $this;
    }

    public function getGroupe(): ?Group
    {
        return $this->groupe;
    }

    public function setGroupe(?Group $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * @return Collection<int, GroupAnswer>
     */
    public function getGroupAnswers(): Collection
    {
        return $this->groupAnswers;
    }

    public function addGroupAnswer(GroupAnswer $groupAnswer): self
    {
        if (!$this->groupAnswers->contains($groupAnswer)) {
            $this->groupAnswers->add($groupAnswer);
            $groupAnswer->setGroupQuestion($this);
        }

        return $this;
    }

    public function removeGroupAnswer(GroupAnswer $groupAnswer): self
    {
        if ($this->groupAnswers->removeElement($groupAnswer)) {
            // set the owning side to null (unless already changed)
            if ($groupAnswer->getGroupQuestion() === $this) {
                $groupAnswer->setGroupQuestion(null);
            }
        }

        return $this;
    }
}
