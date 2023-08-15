<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\RaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RaceRepository::class)]
#[ORM\Table(name: 'races')]
#[ApiResource(
    normalizationContext: ['groups' => ['race:read']],
    denormalizationContext: ['groups' => ['race:write']],
)]
#[ApiFilter(OrderFilter::class, properties: ['title', 'date', 'averageTimeMedium', 'averageTimeLong'], arguments: ['orderParameterName' => 'order'])]
class Race
{
    use TimestampableEntity;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    #[Assert\NotBlank]
    #[Groups(['race:read', 'race:write'])]
    #[ORM\Column(length: 50)]
    private ?string $title = null;

    #[Assert\NotBlank]
    #[Groups(['race:read', 'race:write'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[Groups(['race:read'])]
    #[ORM\Column(type: Types::TIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $averageTimeMedium = null;

    #[Groups(['race:read'])]
    #[ORM\Column(type: Types::TIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $averageTimeLong = null;

    #[Assert\NotNull]
    #[Groups(['race:read'])]
    #[ORM\OneToMany(mappedBy: 'race', targetEntity: Runner::class, orphanRemoval: true)]
    private Collection $runners;

    public function __construct()
    {
        $this->runners = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDate(): string
    {
        return $this->date->format('d.m.Y H:i:s');
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getAverageTimeMedium(): string
    {
        return $this->averageTimeMedium->format('H:i:s');
    }

    public function setAverageTimeMedium(?\DateTimeImmutable $averageTimeMedium): static
    {
        $this->averageTimeMedium = $averageTimeMedium;

        return $this;
    }

    public function getAverageTimeLong(): string
    {
        return $this->averageTimeLong->format('H:i:s');
    }

    public function setAverageTimeLong(?\DateTimeImmutable $averageTimeLong): static
    {
        $this->averageTimeLong = $averageTimeLong;

        return $this;
    }

    /**
     * @return Collection<int, Runner>
     */
    public function getRunner(): Collection
    {
        return $this->runners;
    }

    public function addRunner(Runner $runners): static
    {
        if (!$this->runners->contains($runners)) {
            $this->runners->add($runners);
            $runners->setRace($this);
        }

        return $this;
    }

    public function removeRunner(Runner $runners): static
    {
        if ($this->runners->removeElement($runners)) {
            // set the owning side to null (unless already changed)
            if ($runners->getRace() === $this) {
                $runners->setRace(null);
            }
        }

        return $this;
    }
}
