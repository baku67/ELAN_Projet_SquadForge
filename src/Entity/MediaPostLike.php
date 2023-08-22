<?php

namespace App\Entity;

use App\Repository\MediaPostLikeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaPostLikeRepository::class)]
class MediaPostLike
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $state = null;

    #[ORM\ManyToOne(inversedBy: 'mediaPostLikes')]
    #[ORM\JoinColumn(nullable: false, onDelete: "cascade")]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'mediaPostLikes')]
    private ?MediaPost $mediaPost = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

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

    public function getMediaPost(): ?MediaPost
    {
        return $this->mediaPost;
    }

    public function setMediaPost(?MediaPost $mediaPost): self
    {
        $this->mediaPost = $mediaPost;

        return $this;
    }
}
