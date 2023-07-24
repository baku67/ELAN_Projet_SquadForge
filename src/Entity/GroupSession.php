<?php

namespace App\Entity;

use App\Repository\GroupSessionRepository;
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
}
