<?php

namespace App\Entity;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(length: 100)]
    private ?string $editor = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $publish_date = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $color = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    private ?Genre $genre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $banner = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $fontColor = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'favoris')]
    private Collection $favUsers;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Topic::class)]
    private Collection $topics;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tinyLogo = null;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Notation::class)]
    private Collection $notations;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Media::class)]
    private Collection $media;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $subBanner = null;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Group::class, orphanRemoval: true)]
    private Collection $gameGroups;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $nbrMaxPlaces = null;

    #[ORM\Column(length: 255, options: ['default' => "logoSquadForge_v3.png"])]
    private ?string $siteLogo = null;

    public function __construct()
    {
        $this->favUsers = new ArrayCollection();
        $this->topics = new ArrayCollection();
        $this->notations = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->gameGroups = new ArrayCollection();
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

    public function getEditor(): ?string
    {
        return $this->editor;
    }

    public function setEditor(string $editor): self
    {
        $this->editor = $editor;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getBanner(): ?string
    {
        return $this->banner;
    }

    public function setBanner(?string $banner): self
    {
        $this->banner = $banner;

        return $this;
    }

    public function getFontColor(): ?string
    {
        return $this->fontColor;
    }

    public function setFontColor(?string $fontColor): self
    {
        $this->fontColor = $fontColor;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getFavUsers(): Collection
    {
        return $this->favUsers;
    }

    public function addFavUser(User $favUser): self
    {
        if (!$this->favUsers->contains($favUser)) {
            $this->favUsers->add($favUser);
            $favUser->addFavori($this);
        }

        return $this;
    }

    public function removeFavUser(User $favUser): self
    {
        if ($this->favUsers->removeElement($favUser)) {
            $favUser->removeFavori($this);
        }

        return $this;
    }


    // J'ai "raté" ici le nom donné à la fin de la création de la relation ducoup tout remplacé à la mano
    /**
     * @return Collection<int, Topic>
     */
    public function getTopics(): Collection
    {

        return $this->topics;
    }

    // /**
    //  * @return Collection<int, Topic>
    //  */
    // public function getTopicsDesc(): Collection
    // {
    //     // OrderBy PublishDate:

    //     return $this->createQueryBuilder('t')
    //         ->where('t.game = :game')
    //         ->setParameter('game', 1)
    //         ->orderBy('t.publish_date', 'DESC')
    //         ->getQuery()
    //         ->getResult();
    // }

    public function addTopic(Topic $topic): self
    {
        if (!$this->topics->contains($topic)) {
            $this->topics->add($topic);
            $topic->setGame($this);
        }

        return $this;
    }

    public function removeTopic(Topic $topic): self
    {
        if ($this->topics->removeElement($topic)) {
            // set the owning side to null (unless already changed)
            if ($topic->getGame() === $this) {
                $topic->setGame(null);
            }
        }

        return $this;
    }

    public function getTinyLogo(): ?string
    {
        return $this->tinyLogo;
    }

    public function setTinyLogo(?string $tinyLogo): self
    {
        $this->tinyLogo = $tinyLogo;

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
            $notation->setGame($this);
        }

        return $this;
    }

    public function removeNotation(Notation $notation): self
    {
        if ($this->notations->removeElement($notation)) {
            // set the owning side to null (unless already changed)
            if ($notation->getGame() === $this) {
                $notation->setGame(null);
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
            $medium->setGame($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): self
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getGame() === $this) {
                $medium->setGame(null);
            }
        }

        return $this;
    }

    public function getSubBanner(): ?string
    {
        return $this->subBanner;
    }

    public function setSubBanner(?string $subBanner): self
    {
        $this->subBanner = $subBanner;

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGameGroups(): Collection
    {
        return $this->gameGroups;
    }

    public function addGameGroup(Group $gameGroup): self
    {
        if (!$this->gameGroups->contains($gameGroup)) {
            $this->gameGroups->add($gameGroup);
            $gameGroup->setGame($this);
        }

        return $this;
    }

    public function removeGameGroup(Group $gameGroup): self
    {
        if ($this->gameGroups->removeElement($gameGroup)) {
            // set the owning side to null (unless already changed)
            if ($gameGroup->getGame() === $this) {
                $gameGroup->setGame(null);
            }
        }

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

    public function getSiteLogo(): ?string
    {
        return $this->siteLogo;
    }

    public function setSiteLogo(string $siteLogo): self
    {
        $this->siteLogo = $siteLogo;

        return $this;
    }
}
