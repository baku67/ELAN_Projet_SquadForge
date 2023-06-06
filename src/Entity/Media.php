<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $publish_date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column(length: 50)]
    private ?string $validated = null;

    #[ORM\ManyToOne(inversedBy: 'media')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'media')]
    private ?Game $game = null;

    #[ORM\OneToMany(mappedBy: 'media', targetEntity: MediaPost::class)]
    private Collection $mediaPosts;

    #[ORM\JoinTable(name: 'media_upvotes')]
    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $userUpvote;

    public function __construct()
    {
        $this->mediaPosts = new ArrayCollection();
        $this->userUpvote = new ArrayCollection();
    }

    // private $extension;

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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    // public function getExtension(): ?string {

    //     return $this->getUrl();
    // }

    /**
     * @return Collection<int, MediaPost>
     */
    public function getMediaPosts(): Collection
    {
        return $this->mediaPosts;
    }

    // Pas mappé
    public function getMediaPostsCount(): ?int
    {
        return count($this->mediaPosts);
    }
    

    public function addMediaPost(MediaPost $mediaPost): self
    {
        if (!$this->mediaPosts->contains($mediaPost)) {
            $this->mediaPosts->add($mediaPost);
            $mediaPost->setMedia($this);
        }

        return $this;
    }

    public function removeMediaPost(MediaPost $mediaPost): self
    {
        if ($this->mediaPosts->removeElement($mediaPost)) {
            // set the owning side to null (unless already changed)
            if ($mediaPost->getMedia() === $this) {
                $mediaPost->setMedia(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserUpvote(): Collection
    {
        return $this->userUpvote;
    }

    // Nombre de likes du Média 
    // Twig: {{ media.upvoteCount }} (pas de proriété, juste fonction)
    public function getUpvoteCount(): int
    {
        return count($this->userUpvote);
    }

    public function addUserUpvote(User $userUpvote): self
    {
        if (!$this->userUpvote->contains($userUpvote)) {
            $this->userUpvote->add($userUpvote);
        }

        return $this;
    }

    public function removeUserUpvote(User $userUpvote): self
    {
        $this->userUpvote->removeElement($userUpvote);

        return $this;
    }
}
