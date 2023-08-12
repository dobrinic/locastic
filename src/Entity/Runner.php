<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RunnerRepository;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RunnerRepository::class)]
#[ORM\Table(name: 'runners')]
#[ApiResource]
class Runner
{
    use TimestampableEntity;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 6)]
    private ?string $ageCategory = null;

    #[ORM\Column(length: 10)]
    private ?string $distance = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $finishTime = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $placement = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $agePlacement = null;

    #[ORM\ManyToOne(inversedBy: 'runners')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Race $race = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAgeCategory(): ?string
    {
        return $this->ageCategory;
    }

    public function setAgeCategory(string $ageCategory): static
    {
        $this->ageCategory = $ageCategory;

        return $this;
    }

    public function getDistance(): ?string
    {
        return $this->distance;
    }

    public function setDistance(string $distance): static
    {
        $this->distance = $distance;

        return $this;
    }

    
    public function getFinishTime(): ?\DateTimeImmutable
    {
        return $this->finishTime;
    }

    public function setFinishTime(\DateTimeImmutable $finishTime): static
    {
        $this->finishTime = $finishTime;

        return $this;
    }

    public function getPlacement(): ?int
    {
        return $this->placement;
    }

    public function setPlacement(?int $placement): static
    {
        $this->placement = $placement;

        return $this;
    }

    public function getAgePlacement(): ?int
    {
        return $this->agePlacement;
    }

    public function setAgePlacement(?int $agePlacement): static
    {
        $this->agePlacement = $agePlacement;

        return $this;
    }

    public function getRace(): ?Race
    {
        return $this->race;
    }

    public function setRace(?Race $race): static
    {
        $this->race = $race;

        return $this;
    }

}
