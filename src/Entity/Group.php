<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $nbrPlaces = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creation_date = null;

    #[ORM\Column(nullable: true)]
    private ?bool $restriction_18 = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $restriction_lang = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imgUrl = null;

    #[ORM\ManyToOne(inversedBy: 'leadedGroups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $leader = null;

    #[ORM\ManyToOne(inversedBy: 'gameGroups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    #[ORM\Column(nullable: true)]
    private ?bool $restriction_mic = null;

    #[ORM\JoinTable(name: 'membre_group')]
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'groupes')]
    private Collection $members;

    #[ORM\OneToMany(mappedBy: 'groupe', targetEntity: GroupQuestion::class)]
    private Collection $groupQuestions;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?bool $candidature_description = null;

    #[ORM\Column]
    private ?bool $restriction_imgProof = null;

    #[ORM\OneToMany(mappedBy: 'groupe', targetEntity: Candidature::class)]
    private Collection $candidatures;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'groupsWhereBlackisted')]
    private Collection $blacklistedUsers;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->groupQuestions = new ArrayCollection();
        $this->candidatures = new ArrayCollection();
        $this->blacklistedUsers = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNbrPlaces(): ?int
    {
        return $this->nbrPlaces;
    }

    public function setNbrPlaces(int $nbrPlaces): self
    {
        $this->nbrPlaces = $nbrPlaces;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function isRestriction18(): ?bool
    {
        return $this->restriction_18;
    }

    public function setRestriction18(?bool $restriction_18): self
    {
        $this->restriction_18 = $restriction_18;

        return $this;
    }

    public function getRestrictionLang(): ?string
    {
        return $this->restriction_lang;
    }

    public function setRestrictionLang(?string $restriction_lang): self
    {
        $this->restriction_lang = $restriction_lang;

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

    public function getImgUrl(): ?string
    {
        return $this->imgUrl;
    }

    public function setImgUrl(?string $imgUrl): self
    {
        $this->imgUrl = $imgUrl;

        return $this;
    }

    public function getLeader(): ?User
    {
        return $this->leader;
    }

    public function setLeader(?User $leader): self
    {
        $this->leader = $leader;

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

    public function isRestrictionMic(): ?bool
    {
        return $this->restriction_mic;
    }

    public function setRestrictionMic(?bool $restriction_mic): self
    {
        $this->restriction_mic = $restriction_mic;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(User $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
        }

        return $this;
    }

    public function removeMember(User $member): self
    {
        $this->members->removeElement($member);

        return $this;
    }

    /**
     * @return Collection<int, GroupQuestion>
     */
    public function getGroupQuestions(): Collection
    {
        return $this->groupQuestions;
    }

    public function addGroupQuestion(GroupQuestion $groupQuestion): self
    {
        if (!$this->groupQuestions->contains($groupQuestion)) {
            $this->groupQuestions->add($groupQuestion);
            $groupQuestion->setGroupe($this);
        }

        return $this;
    }

    public function removeGroupQuestion(GroupQuestion $groupQuestion): self
    {
        if ($this->groupQuestions->removeElement($groupQuestion)) {
            // set the owning side to null (unless already changed)
            if ($groupQuestion->getGroupe() === $this) {
                $groupQuestion->setGroupe(null);
            }
        }

        return $this;
    }

    public function getCandidatureDescription(): ?string
    {
        return $this->candidature_description;
    }

    public function setCandidatureDescription(?string $candidature_description): self
    {
        $this->candidature_description = $candidature_description;

        return $this;
    }

    public function isRestrictionImgProof(): ?bool
    {
        return $this->restriction_imgProof;
    }

    public function setRestrictionImgProof(bool $restriction_imgProof): self
    {
        $this->restriction_imgProof = $restriction_imgProof;

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
            $candidature->setGroupe($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): self
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getGroupe() === $this) {
                $candidature->setGroupe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getBlacklistedUsers(): Collection
    {
        return $this->blacklistedUsers;
    }

    public function addBlacklistedUser(User $blacklistedUser): self
    {
        if (!$this->blacklistedUsers->contains($blacklistedUser)) {
            $this->blacklistedUsers->add($blacklistedUser);
        }

        return $this;
    }

    public function removeBlacklistedUser(User $blacklistedUser): self
    {
        $this->blacklistedUsers->removeElement($blacklistedUser);

        return $this;
    }



}
