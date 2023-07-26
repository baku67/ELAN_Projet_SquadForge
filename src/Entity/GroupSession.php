<?php

namespace App\Entity;

use App\Repository\GroupSessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupSessionRepository::class)]
class GroupSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'groupSessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Group $team = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateStart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEnd = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $comfirmNeeded = null;

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: GroupSessionDispo::class, orphanRemoval: true)]
    private Collection $groupSessionDispos;

    public function __construct()
    {
        $this->groupSessionDispos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeam(): ?Group
    {
        return $this->team;
    }

    public function setTeam(?Group $team): static
    {
        $this->team = $team;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): static
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): static
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function isComfirmNeeded(): ?bool
    {
        return $this->comfirmNeeded;
    }

    public function setComfirmNeeded(bool $comfirmNeeded): static
    {
        $this->comfirmNeeded = $comfirmNeeded;

        return $this;
    }

    /**
     * @return Collection<int, GroupSessionDispo>
     */
    public function getGroupSessionDispos(): Collection
    {
        return $this->groupSessionDispos;
    }

    public function addGroupSessionDispo(GroupSessionDispo $groupSessionDispo): static
    {
        if (!$this->groupSessionDispos->contains($groupSessionDispo)) {
            $this->groupSessionDispos->add($groupSessionDispo);
            $groupSessionDispo->setSession($this);
        }

        return $this;
    }

    public function removeGroupSessionDispo(GroupSessionDispo $groupSessionDispo): static
    {
        if ($this->groupSessionDispos->removeElement($groupSessionDispo)) {
            // set the owning side to null (unless already changed)
            if ($groupSessionDispo->getSession() === $this) {
                $groupSessionDispo->setSession(null);
            }
        }

        return $this;
    }
}
