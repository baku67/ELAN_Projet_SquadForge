<?php

namespace App\Entity;

use Doctrine\Common\Collections\Criteria;

use App\Repository\MediaPostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaPostRepository::class)]
class MediaPost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $publish_date = null;

    #[ORM\ManyToOne(inversedBy: 'mediaPosts')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'mediaPosts')]
    private ?Media $media = null;

    #[ORM\OneToMany(mappedBy: 'mediaPost', targetEntity: MediaPostLike::class, cascade: ["remove"])]
    private Collection $mediaPostLikes;

    public function __construct()
    {
        $this->mediaPostLikes = new ArrayCollection();
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

    public function getPublishDate(): ?\DateTimeInterface
    {
        return $this->publish_date;
    }

    public function setPublishDate(\DateTimeInterface $publish_date): self
    {
        $this->publish_date = $publish_date;

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

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): self
    {
        $this->media = $media;

        return $this;
    }

    /**
     * @return Collection<int, MediaPostLike>
     */
    public function getMediaPostLikes(): Collection
    {
        return $this->mediaPostLikes;
    }

    public function addMediaPostLike(MediaPostLike $mediaPostLike): self
    {
        if (!$this->mediaPostLikes->contains($mediaPostLike)) {
            $this->mediaPostLikes->add($mediaPostLike);
            $mediaPostLike->setMediaPost($this);
        }

        return $this;
    }

    public function removeMediaPostLike(MediaPostLike $mediaPostLike): self
    {
        if ($this->mediaPostLikes->removeElement($mediaPostLike)) {
            // set the owning side to null (unless already changed)
            if ($mediaPostLike->getMediaPost() === $this) {
                $mediaPostLike->setMediaPost(null);
            }
        }

        return $this;
    }



    public function getScore(): int {

        // // Compte des upvotes de la Collection mediaPostLike
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('state', "upvote"));

        $filteredCollection = $this->mediaPostLikes->matching($criteria);
        $upvoteCount = $filteredCollection->count();

        // Compte des downvotes de la Collection mediaPostLike
        $criteria2 = Criteria::create()
            ->where(Criteria::expr()->eq('state', "downvote"));

        $filteredCollection2 = $this->mediaPostLikes->matching($criteria2);
        $downvoteCount = $filteredCollection2->count();

        $score = $upvoteCount - $downvoteCount;

        return $score;

    }  
}
