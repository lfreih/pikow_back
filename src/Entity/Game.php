<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Get;
use App\State\GamePitchesProvider;
use App\State\GameUpdateProcessor;
use App\State\GameCollectionProvider;
use App\Repository\GameRepository;
use App\State\GameCreateProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/games',
            processor: GameCreateProcessor::class,
            normalizationContext: ['groups' => ['game:read']],
            denormalizationContext: ['groups' => ['game:create']]
        ),
        new GetCollection(
            uriTemplate: '/games',
            provider: GameCollectionProvider::class,
            normalizationContext: ['groups' => ['game:read']],
            paginationEnabled: false
        ),
        new Patch(
            uriTemplate: '/games/{id}',
            processor: GameUpdateProcessor::class,
            normalizationContext: ['groups' => ['game:read']],
            denormalizationContext: ['groups' => ['game:update']]
        ),
        new Get(
            uriTemplate: '/games/{id}/pitches',
            provider: GamePitchesProvider::class,
            normalizationContext: ['groups' => ['pitch:read']],
            output: Pitch::class,
            paginationEnabled: false
        )
    ]
)]
#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['game:read'])]
    private ?Uuid $id = null;

    #[ORM\Column]
    #[Groups(['game:read'])]
    private ?\DateTime $date = null;

    #[ORM\Column(length: 100)]
    #[Groups(['game:create', 'game:read'])]
    private ?string $theme = null;

    #[ORM\Column]
    #[Groups(['game:create', 'game:read'])]
    private ?int $nbPlayers = null;

    #[ORM\Column(length: 50)]
    #[Groups(['game:read', 'game:update'])]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, Pitch>
     */
    #[ORM\OneToMany(targetEntity: Pitch::class, mappedBy: 'game')]
    private Collection $pitches;

    public function __construct()
    {
        $this->pitches = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function getNbPlayers(): ?int
    {
        return $this->nbPlayers;
    }

    public function setNbPlayers(int $nbPlayers): static
    {
        $this->nbPlayers = $nbPlayers;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Pitch>
     */
    public function getPitches(): Collection
    {
        return $this->pitches;
    }

    public function addPitch(Pitch $pitch): static
    {
        if (!$this->pitches->contains($pitch)) {
            $this->pitches->add($pitch);
            $pitch->setGame($this);
        }

        return $this;
    }

    public function removePitch(Pitch $pitch): static
    {
        if ($this->pitches->removeElement($pitch)) {
            // set the owning side to null (unless already changed)
            if ($pitch->getGame() === $this) {
                $pitch->setGame(null);
            }
        }

        return $this;
    }
}
