<?php

namespace App\Entity;

use App\Repository\ReportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReportRepository::class)]
class Report
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reports')]
    private ?User $user_reporter = null;

    #[ORM\Column]
    private ?int $objectId = null;

    #[ORM\Column(length: 50)]
    private ?string $objectType = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creation_date = null;

    #[ORM\ManyToOne(inversedBy: 'reports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ReportMotif $reportMotif = null;

    
    public function __toString() {
        return $this->id;
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

    public function getUserReporter(): ?User
    {
        return $this->user_reporter;
    }

    public function setUserReporter(?User $user_reporter): self
    {
        $this->user_reporter = $user_reporter;

        return $this;
    }

    public function getObjectId(): ?int
    {
        return $this->objectId;
    }

    public function setObjectId(int $objectId): self
    {
        $this->objectId = $objectId;

        return $this;
    }

    public function getObjectType(): ?string
    {
        return $this->objectType;
    }

    public function setObjectType(string $objectType): self
    {
        $this->objectType = $objectType;

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

    public function getReportMotif(): ?ReportMotif
    {
        return $this->reportMotif;
    }

    public function setReportMotif(?ReportMotif $reportMotif): self
    {
        $this->reportMotif = $reportMotif;

        return $this;
    }
}
