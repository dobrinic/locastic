<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RaceRepository::class)]
#[ORM\Table(name: 'races')]
#[ApiResource]
class Race
{
    use TimestampableEntity;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $title = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $averageTimeMedium = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $averageTimeLong = null;

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

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getAverageTimeMedium(): ?\DateTimeImmutable
    {
        return $this->averageTimeMedium;
    }

    public function setAverageTimeMedium(?\DateTimeImmutable $averageTimeMedium): static
    {
        $this->averageTimeMedium = $averageTimeMedium;

        return $this;
    }

    public function getAverageTimeLong(): ?\DateTimeImmutable
    {
        return $this->averageTimeLong;
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
