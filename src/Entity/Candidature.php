<?php

namespace App\Entity;

use App\Repository\CandidatureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\FormTypeInterface;

#[ORM\Entity(repositoryClass: CandidatureRepository::class)]
class Candidature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $text = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creation_date = null;

    #[ORM\ManyToOne(inversedBy: 'candidatures', cascade: ['remove'])]
    #[ORM\JoinColumn(nullable: false, onDelete: "cascade")]
    private ?Group $groupe = null;

    #[ORM\ManyToOne(inversedBy: 'candidatures')]
    private ?User $user = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\OneToMany(mappedBy: 'candidature', targetEntity: GroupAnswer::class, cascade: ['remove'])]
    private Collection $groupAnswers;

    public function __construct()
    {
        $this->groupAnswers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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
            $groupAnswer->setCandidature($this);
        }

        return $this;
    }

    public function removeGroupAnswer(GroupAnswer $groupAnswer): self
    {
        if ($this->groupAnswers->removeElement($groupAnswer)) {
            // set the owning side to null (unless already changed)
            if ($groupAnswer->getCandidature() === $this) {
                $groupAnswer->setCandidature(null);
            }
        }

        return $this;
    }
}
