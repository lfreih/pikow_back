<?php

namespace App\Entity;

use App\Repository\PitchRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PitchRepository::class)]
class Pitch
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 100)]
    private ?string $playerName = null;

    #[ORM\Column]
    private ?int $playerAge = null;

    #[ORM\Column]
    private ?int $turnNumber = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\Column(nullable: true)]
    private ?int $score = null;

    #[ORM\ManyToOne(inversedBy: 'pitches')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Element $word1 = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Element $word2 = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getPlayerName(): ?string
    {
        return $this->playerName;
    }

    public function setPlayerName(string $playerName): static
    {
        $this->playerName = $playerName;

        return $this;
    }

    public function getPlayerAge(): ?int
    {
        return $this->playerAge;
    }

    public function setPlayerAge(int $playerAge): static
    {
        $this->playerAge = $playerAge;

        return $this;
    }

    public function getTurnNumber(): ?int
    {
        return $this->turnNumber;
    }

    public function setTurnNumber(int $turnNumber): static
    {
        $this->turnNumber = $turnNumber;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    public function getWord1(): ?Element
    {
        return $this->word1;
    }

    public function setWord1(?Element $word1): static
    {
        $this->word1 = $word1;

        return $this;
    }

    public function getWord2(): ?Element
    {
        return $this->word2;
    }

    public function setWord2(?Element $word2): static
    {
        $this->word2 = $word2;

        return $this;
    }
}
