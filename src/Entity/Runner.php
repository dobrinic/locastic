<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\Repository\RunnerRepository;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RunnerRepository::class)]
#[ORM\Table(name: 'runners')]
#[ApiResource(
    normalizationContext: ['groups' => ['runner:read']],
    denormalizationContext: ['groups' => ['runner:write']],
)]
#[ApiResource(
    uriTemplate: '/races/{id}/runners.{_format}', 
    uriVariables: [
        'id' => new Link(
            fromClass: Race::class,
            fromProperty: 'runners'
        )
    ], 
    operations: [new GetCollection()]
)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial', 'distance' => 'partial', 'ageCategory' => 'partial'])]
#[ApiFilter(OrderFilter::class, properties: ['name', 'finishTime', 'ageCategory', 'distance', 'placement', 'agePlacement'], arguments: ['orderParameterName' => 'order'])]
class Runner
{
    use TimestampableEntity;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Groups(['runner:read', 'runner:write'])]
    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[Assert\NotBlank]
    #[Groups(['runner:read', 'runner:write'])]
    #[ORM\Column(length: 6)]
    private ?string $ageCategory = null;

    #[Assert\NotBlank]
    #[Groups(['runner:read', 'runner:write'])]
    #[ORM\Column(length: 10)]
    private ?string $distance = null;

    #[Assert\NotBlank]
    #[Groups(['runner:read', 'runner:write'])]
    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $finishTime = null;

    #[Groups(['runner:read'])]
    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $placement = null;

    #[Groups(['runner:read'])]
    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $agePlacement = null;

    #[Groups(['runner:read', 'runner:write'])]
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

    
    public function getFinishTime(): string
    {
        return $this->finishTime->format('H:i:s');
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
