<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(length: 20)]
    private ?string $pseudo = null;

    #[ORM\ManyToMany(targetEntity: Game::class, inversedBy: 'favUsers')]
    private Collection $favoris;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Topic::class)]
    private Collection $topics;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Notation::class)]
    private Collection $notations;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: TopicPost::class)]
    private Collection $topicPosts;

    #[ORM\ManyToMany(targetEntity: TopicPost::class, mappedBy: 'postLike')]
    private Collection $likedTopicPosts;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: PostLike::class)]
    private Collection $postLikes;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Media::class)]
    private Collection $media;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: MediaPost::class)]
    private Collection $mediaPosts;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: MediaPostLike::class)]
    private Collection $mediaPostLikes;

    #[ORM\Column(options: ['default' => true])]
    private ?bool $autoPlayGifs = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Censure::class)]
    private Collection $censures;

    public function __construct()
    {
        $this->favoris = new ArrayCollection();
        $this->topics = new ArrayCollection();
        $this->notations = new ArrayCollection();
        $this->topicPosts = new ArrayCollection();
        $this->likedTopicPosts = new ArrayCollection();
        $this->postLikes = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->mediaPosts = new ArrayCollection();
        $this->mediaPostLikes = new ArrayCollection();
        $this->censures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Game $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris->add($favori);
        }

        return $this;
    }

    public function removeFavori(Game $favori): self
    {
        $this->favoris->removeElement($favori);

        return $this;
    }

    /**
     * @return Collection<int, Topic>
     */
    public function getTopics(): Collection
    {
        return $this->topics;
    }

    public function addTopic(Topic $topic): self
    {
        if (!$this->topics->contains($topic)) {
            $this->topics->add($topic);
            $topic->setUser($this);
        }

        return $this;
    }

    public function removeTopic(Topic $topic): self
    {
        if ($this->topics->removeElement($topic)) {
            // set the owning side to null (unless already changed)
            if ($topic->getUser() === $this) {
                $topic->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notation>
     */
    public function getNotations(): Collection
    {
        return $this->notations;
    }

    public function addNotation(Notation $notation): self
    {
        if (!$this->notations->contains($notation)) {
            $this->notations->add($notation);
            $notation->setUser($this);
        }

        return $this;
    }

    public function removeNotation(Notation $notation): self
    {
        if ($this->notations->removeElement($notation)) {
            // set the owning side to null (unless already changed)
            if ($notation->getUser() === $this) {
                $notation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TopicPost>
     */
    public function getTopicPosts(): Collection
    {
        return $this->topicPosts;
    }

    public function addTopicPost(TopicPost $topicPost): self
    {
        if (!$this->topicPosts->contains($topicPost)) {
            $this->topicPosts->add($topicPost);
            $topicPost->setUser($this);
        }

        return $this;
    }

    public function removeTopicPost(TopicPost $topicPost): self
    {
        if ($this->topicPosts->removeElement($topicPost)) {
            // set the owning side to null (unless already changed)
            if ($topicPost->getUser() === $this) {
                $topicPost->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TopicPost>
     */
    public function getLikedTopicPosts(): Collection
    {
        return $this->likedTopicPosts;
    }

    public function addLikedTopicPost(TopicPost $likedTopicPost): self
    {
        if (!$this->likedTopicPosts->contains($likedTopicPost)) {
            $this->likedTopicPosts->add($likedTopicPost);
            $likedTopicPost->addPostLike($this);
        }

        return $this;
    }

    public function removeLikedTopicPost(TopicPost $likedTopicPost): self
    {
        if ($this->likedTopicPosts->removeElement($likedTopicPost)) {
            $likedTopicPost->removePostLike($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, PostLike>
     */
    public function getPostLikes(): Collection
    {
        return $this->postLikes;
    }

    public function addPostLike(PostLike $postLike): self
    {
        if (!$this->postLikes->contains($postLike)) {
            $this->postLikes->add($postLike);
            $postLike->setUser($this);
        }

        return $this;
    }

    public function removePostLike(PostLike $postLike): self
    {
        if ($this->postLikes->removeElement($postLike)) {
            // set the owning side to null (unless already changed)
            if ($postLike->getUser() === $this) {
                $postLike->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media->add($medium);
            $medium->setUser($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): self
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getUser() === $this) {
                $medium->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MediaPost>
     */
    public function getMediaPosts(): Collection
    {
        return $this->mediaPosts;
    }

    public function addMediaPost(MediaPost $mediaPost): self
    {
        if (!$this->mediaPosts->contains($mediaPost)) {
            $this->mediaPosts->add($mediaPost);
            $mediaPost->setUser($this);
        }

        return $this;
    }

    public function removeMediaPost(MediaPost $mediaPost): self
    {
        if ($this->mediaPosts->removeElement($mediaPost)) {
            // set the owning side to null (unless already changed)
            if ($mediaPost->getUser() === $this) {
                $mediaPost->setUser(null);
            }
        }

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
            $mediaPostLike->setUser($this);
        }

        return $this;
    }

    public function removeMediaPostLike(MediaPostLike $mediaPostLike): self
    {
        if ($this->mediaPostLikes->removeElement($mediaPostLike)) {
            // set the owning side to null (unless already changed)
            if ($mediaPostLike->getUser() === $this) {
                $mediaPostLike->setUser(null);
            }
        }

        return $this;
    }

    public function isAutoPlayGifs(): ?bool
    {
        return $this->autoPlayGifs;
    }

    public function setAutoPlayGifs(bool $autoPlayGifs): self
    {
        $this->autoPlayGifs = $autoPlayGifs;

        return $this;
    }

    /**
     * @return Collection<int, Censure>
     */
    public function getCensures(): Collection
    {
        return $this->censures;
    }

    public function addCensure(Censure $censure): self
    {
        if (!$this->censures->contains($censure)) {
            $this->censures->add($censure);
            $censure->setUser($this);
        }

        return $this;
    }

    public function removeCensure(Censure $censure): self
    {
        if ($this->censures->removeElement($censure)) {
            // set the owning side to null (unless already changed)
            if ($censure->getUser() === $this) {
                $censure->setUser(null);
            }
        }

        return $this;
    }
}
