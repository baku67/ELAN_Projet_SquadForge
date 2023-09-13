<?php

namespace App\Entity;

use App\Repository\PostLikeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostLikeRepository::class)]
class PostLike
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $state = null;

    #[ORM\ManyToOne(inversedBy: 'postLikes')]
    #[ORM\JoinColumn(nullable: false, onDelete: "cascade")]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'postLikes')]
    private ?TopicPost $topicPost = null;

    
    public function __toString() {
        return $this->state;
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

    public function getTopicPost(): ?TopicPost
    {
        return $this->topicPost;
    }

    public function setTopicPost(?TopicPost $topicPost): self
    {
        $this->topicPost = $topicPost;

        return $this;
    }
}
