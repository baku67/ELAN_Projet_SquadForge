<?php

namespace App\Entity;

use App\Repository\GroupSessionDispoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupSessionDispoRepository::class)]
class GroupSessionDispo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'groupSessionDispos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GroupSession $session = null;

    #[ORM\ManyToOne(inversedBy: 'groupSessionDispos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $member = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $disponibility = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSession(): ?GroupSession
    {
        return $this->session;
    }

    public function setSession(?GroupSession $session): static
    {
        $this->session = $session;

        return $this;
    }

    public function getMember(): ?User
    {
        return $this->member;
    }

    public function setMember(?User $member): static
    {
        $this->member = $member;

        return $this;
    }

    public function getDisponibility(): ?string
    {
        return $this->disponibility;
    }

    public function setDisponibility(string $disponibility): static
    {
        $this->disponibility = $disponibility;

        return $this;
    }
}
