<?php

namespace App\Entity;

use DateTime;
use ORM\JoinTable;
use ORM\JoinColumn;
use ORM\InverseJoinColumn;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'L\'email entré est déjà utilisé, veuillez vous connecter')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(length: 20)]
    private ?string $pseudo = null;

    #[ORM\ManyToMany(targetEntity: Game::class, inversedBy: 'favUsers')]
    // #[JoinTable(name: 'favoris')]
    // #[JoinColumn(name:'user_id', referencedColumnName:'id', nullable: false, onDelete: "cascade")]
    // #[InverseJoinColumn(name:'game_id', referencedColumnName:'id', nullable: false)]
    private Collection $favoris;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Topic::class)]
    private Collection $topics;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Notation::class)]
    private Collection $notations;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: TopicPost::class)]
    private Collection $topicPosts;

    // #[ORM\ManyToMany(targetEntity: TopicPost::class, mappedBy: 'postLikes')]
    // #[ORM\JoinTable(name: 'post_like')]
    // #[ORM\JoinColumn(name:'user_id', referencedColumnName:'id', nullable: false, onDelete: "cascade")]
    // #[ORM\InverseJoinColumn(name:'topic_post_id', referencedColumnName:'id', nullable: false)]
    // private Collection $likedTopicPosts;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: PostLike::class)]
    private Collection $postLikes;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Media::class)]
    private Collection $media;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: MediaPost::class)]
    private Collection $mediaPosts;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: MediaPostLike::class)]
    private Collection $mediaPostLikes;

    #[ORM\Column(nullable: true, options: ['default' => true])]
    private ?bool $autoPlayGifs = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Censure::class)]
    private Collection $censures;

    #[ORM\OneToMany(mappedBy: 'leader', targetEntity: Group::class)]
    private Collection $leadedGroups;

    #[ORM\ManyToMany(targetEntity: Group::class, mappedBy: 'members')]
    // #[ORM\JoinTable(name: 'membre_group')]
    // #[ORM\JoinColumn(name:'user_id', referencedColumnName:'id', nullable: false)]
    // #[ORM\InverseJoinColumn(name:'group_id', referencedColumnName:'id', nullable: false)]
    private Collection $groupes;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Candidature::class)]
    private Collection $candidatures;

    #[ORM\ManyToMany(targetEntity: Group::class, mappedBy: 'blacklistedUsers')]
    // #[ORM\JoinTable(name: 'group_blacklist')]
    // #[ORM\JoinColumn(name:'user_id', referencedColumnName:'id', nullable: false)]
    // #[ORM\InverseJoinColumn(name:'group_id', referencedColumnName:'id', nullable: false)]
    private Collection $groupsWhereBlackisted;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Notification::class, orphanRemoval: true)]
    private Collection $notifications;

    #[ORM\OneToMany(mappedBy: 'user_reporter', targetEntity: Report::class)]
    private Collection $reports;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $end_date_status = null;

    private bool $banned;
    private bool $muted;

    #[ORM\Column(options: ['default' => 0])]
    private ?int $nbrCensures = null;

    #[ORM\OneToMany(mappedBy: 'member', targetEntity: GroupSessionDispo::class, orphanRemoval: true)]
    private Collection $groupSessionDispos;

    #[ORM\Column(nullable: true)]
    private ?int $twitch_id = null;

    #[ORM\Column(name: 'last_co', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $last_co = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $previous_co = null;

    public function __construct()
    {
        $this->favoris = new ArrayCollection();
        $this->topics = new ArrayCollection();
        $this->notations = new ArrayCollection();
        $this->topicPosts = new ArrayCollection();
        // $this->likedTopicPosts = new ArrayCollection();
        $this->postLikes = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->mediaPosts = new ArrayCollection();
        $this->mediaPostLikes = new ArrayCollection();
        $this->censures = new ArrayCollection();
        $this->leadedGroups = new ArrayCollection();
        $this->groupes = new ArrayCollection();
        $this->candidatures = new ArrayCollection();
        $this->groupsWhereBlackisted = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->reports = new ArrayCollection();
        $this->groupSessionDispos = new ArrayCollection();
    }

    
    public function __toString() {
        return $this->pseudo;
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

    // Check si User banned ou Muted
    public function isBanned(): bool
    {
        if($this->status == "banned") {
            if(new DateTime < $this->end_date_status) {
                $this->banned = true;
                return $this->banned;
            }
            else {
                $this->banned = false;
                return $this->banned;
            }
        }
        else {
            $this->banned = false;
            return $this->banned;
        }
    }

    public function isMuted(): bool
    {
        if($this->status == "muted") {
            if(new DateTime < $this->end_date_status) {
                $this->muted = true;
                return $this->muted;
            }
            else {
                $this->muted = false;
                return $this->muted;
            }
        }
        else {
            $this->muted = false;
            return $this->muted;
        }
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

    // /**
    //  * @return Collection<int, TopicPost>
    //  */
    // public function getLikedTopicPosts(): Collection
    // {
    //     return $this->likedTopicPosts;
    // }

    // public function addLikedTopicPost(TopicPost $likedTopicPost): self
    // {
    //     if (!$this->likedTopicPosts->contains($likedTopicPost)) {
    //         $this->likedTopicPosts->add($likedTopicPost);
    //         $likedTopicPost->addPostLike($this);
    //     }

    //     return $this;
    // }

    // public function removeLikedTopicPost(TopicPost $likedTopicPost): self
    // {
    //     if ($this->likedTopicPosts->removeElement($likedTopicPost)) {
    //         $likedTopicPost->removePostLike($this);
    //     }

    //     return $this;
    // }

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

    /**
     * @return Collection<int, Group>
     */
    public function getLeadedGroups(): Collection
    {
        return $this->leadedGroups;
    }

    public function addLeadedGroup(Group $leadedGroup): self
    {
        if (!$this->leadedGroups->contains($leadedGroup)) {
            $this->leadedGroups->add($leadedGroup);
            $leadedGroup->setLeader($this);
        }

        return $this;
    }

    public function removeLeadedGroup(Group $leadedGroup): self
    {
        if ($this->leadedGroups->removeElement($leadedGroup)) {
            // set the owning side to null (unless already changed)
            if ($leadedGroup->getLeader() === $this) {
                $leadedGroup->setLeader(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Group $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes->add($groupe);
            $groupe->addMember($this);
        }

        return $this;
    }

    public function removeGroupe(Group $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeMember($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Candidature>
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): self
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures->add($candidature);
            $candidature->setUser($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): self
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getUser() === $this) {
                $candidature->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroupsWhereBlackisted(): Collection
    {
        return $this->groupsWhereBlackisted;
    }

    public function addGroupsWhereBlackisted(Group $groupsWhereBlackisted): self
    {
        if (!$this->groupsWhereBlackisted->contains($groupsWhereBlackisted)) {
            $this->groupsWhereBlackisted->add($groupsWhereBlackisted);
            $groupsWhereBlackisted->addBlacklistedUser($this);
        }

        return $this;
    }

    public function removeGroupsWhereBlackisted(Group $groupsWhereBlackisted): self
    {
        if ($this->groupsWhereBlackisted->removeElement($groupsWhereBlackisted)) {
            $groupsWhereBlackisted->removeBlacklistedUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Report>
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports->add($report);
            $report->setUserReporter($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getUserReporter() === $this) {
                $report->setUserReporter(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getEndDateStatus(): ?\DateTimeInterface
    {
        return $this->end_date_status;
    }

    public function setEndDateStatus(?\DateTimeInterface $end_date_status): self
    {
        $this->end_date_status = $end_date_status;

        return $this;
    }

    public function getNbrCensures(): ?int
    {
        return $this->nbrCensures;
    }

    public function setNbrCensures(int $nbrCensures): static
    {
        $this->nbrCensures = $nbrCensures;

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
            $groupSessionDispo->setMember($this);
        }

        return $this;
    }

    public function removeGroupSessionDispo(GroupSessionDispo $groupSessionDispo): static
    {
        if ($this->groupSessionDispos->removeElement($groupSessionDispo)) {
            // set the owning side to null (unless already changed)
            if ($groupSessionDispo->getMember() === $this) {
                $groupSessionDispo->setMember(null);
            }
        }

        return $this;
    }

    public function getTwitchId(): ?int
    {
        return $this->twitch_id;
    }

    public function setTwitchId(?int $twitch_id): static
    {
        $this->twitch_id = $twitch_id;

        return $this;
    }

    public function getLastCo(): ?\DateTimeInterface
    {
        return $this->last_co;
    }

    public function setLastCo(?\DateTimeInterface $last_co): static
    {
        $this->last_co = $last_co;

        return $this;
    }

    public function getPreviousCo(): ?\DateTimeInterface
    {
        return $this->previous_co;
    }

    public function setPreviousCo(?\DateTimeInterface $previous_co): static
    {
        $this->previous_co = $previous_co;

        return $this;
    }
}
