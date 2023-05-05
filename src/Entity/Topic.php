<?php

namespace App\Entity;

use App\Repository\TopicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TopicRepository::class)]
class Topic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $publish_date = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column(length: 50)]
    private ?string $validated = null;

    #[ORM\ManyToOne(inversedBy: 'user')]
    private ?Game $game = null;

    #[ORM\ManyToOne(inversedBy: 'topics')]
    private ?User $user = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $firstMsg = null;

    #[ORM\OneToMany(mappedBy: 'topic', targetEntity: TopicPost::class)]
    private Collection $topicPosts;

    // Non mappé
    private $topicPostsCount;

    public function __construct()
    {
        $this->topicPosts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPublishDate(): ?\DateTimeInterface
    {
        return $this->publish_date;
    }

    public function setPublishDate(\DateTimeInterface $publish_date): self
    {
        $this->publish_date = $publish_date;

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

    public function getValidated(): ?string
    {
        return $this->validated;
    }

    public function setValidated(string $validated): self
    {
        $this->validated = $validated;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

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

    public function getFirstMsg(): ?string
    {
        return $this->firstMsg;
    }

    public function setFirstMsg(?string $firstMsg): self
    {
        $this->firstMsg = $firstMsg;

        return $this;
    }

    /**
     * @return Collection<int, TopicPost>
     */
    public function getTopicPosts(): Collection
    {
        return $this->topicPosts;
    }

    // Pas mappé
    public function getTopicPostsCount(): ?int
    {
        return count($this->topicPosts);
    }

    public function addTopicPost(TopicPost $topicPost): self
    {
        if (!$this->topicPosts->contains($topicPost)) {
            $this->topicPosts->add($topicPost);
            $topicPost->setTopic($this);
        }

        return $this;
    }

    public function removeTopicPost(TopicPost $topicPost): self
    {
        if ($this->topicPosts->removeElement($topicPost)) {
            // set the owning side to null (unless already changed)
            if ($topicPost->getTopic() === $this) {
                $topicPost->setTopic(null);
            }
        }

        return $this;
    }
}
