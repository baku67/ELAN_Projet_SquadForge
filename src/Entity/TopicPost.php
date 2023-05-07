<?php

namespace App\Entity;

use Doctrine\Common\Collections\Criteria;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\FormTypeInterface;
use App\Repository\TopicPostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TopicPostRepository::class)]
class TopicPost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $publish_date = null;

    #[ORM\ManyToOne(inversedBy: 'topicPosts')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'topicPosts')]
    private ?Topic $topic = null;

    #[ORM\ManyToOne(targetEntity: TopicPost::class, inversedBy: 'topicPosts')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', nullable: true)]
    private ?TopicPost $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $topicPosts;

    #[ORM\ManyToOne(targetEntity: TopicPost::class, inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'topic_post_id', referencedColumnName: 'id', nullable: true)]
    private ?TopicPost $topicPost = null;

    #[ORM\OneToMany(mappedBy: 'topicPost', targetEntity: self::class, orphanRemoval: true)]
    private Collection $children;

    // Nouveau sytème avec entité cette fois
    #[ORM\OneToMany(mappedBy: 'topicPost', targetEntity: PostLike::class)]
    private Collection $postLikes;


    public function __construct()
    {
        $this->topicPosts = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->postLikes = new ArrayCollection();

    }



    public function getId(): ?int
    {
        return $this->id;
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

    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    public function setTopic(?Topic $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getTopicPosts(): Collection
    {
        return $this->topicPosts;
    }

    public function addTopicPost(self $topicPost): self
    {
        if (!$this->topicPosts->contains($topicPost)) {
            $this->topicPosts->add($topicPost);
            $topicPost->setParent($this);
        }

        return $this;
    }

    public function removeTopicPost(self $topicPost): self
    {
        if ($this->topicPosts->removeElement($topicPost)) {
            // set the owning side to null (unless already changed)
            if ($topicPost->getParent() === $this) {
                $topicPost->setParent(null);
            }
        }

        return $this;
    }

    public function getTopicPost(): ?self
    {
        return $this->topicPost;
    }

    public function setTopicPost(?self $topicPost): self
    {
        $this->topicPost = $topicPost;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setTopicPost($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getTopicPost() === $this) {
                $child->setTopicPost(null);
            }
        }

        return $this;
    }




    // Nouveau sytème avec entité cette fois
    /**
     * @return Collection<int, PostLike>
     */
    public function getPostLikes(): Collection
    {
        return $this->postLikes;
    }



    public function getScore(): int {

        // // Compte des upvotes de la Collection postLikes
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('state', "upvote"));

        $filteredCollection = $this->postLikes->matching($criteria);
        $upvoteCount = $filteredCollection->count();

        // Compte des downvotes de la Collection postLikes
        $criteria2 = Criteria::create()
            ->where(Criteria::expr()->eq('state', "downvote"));

        $filteredCollection2 = $this->postLikes->matching($criteria2);
        $downvoteCount = $filteredCollection2->count();

        $score = $upvoteCount - $downvoteCount;

        return $score;

    }   




}
