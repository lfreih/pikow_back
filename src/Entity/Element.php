<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\ElementRepository;
use App\State\ElementRandomProvider;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/elements/random',
            provider: ElementRandomProvider::class,
            normalizationContext: ['groups' => ['element:read']],
            paginationEnabled: false
        )
    ]
)]
#[ORM\Entity(repositoryClass: ElementRepository::class)]
class Element
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['element:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['element:read'])]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    #[Groups(['element:read'])]
    private ?string $value = null;

    #[ORM\Column(length: 100)]
    #[Groups(['element:read'])]
    private ?string $theme = null;

    #[ORM\Column]
    private ?int $ageMin = null;

    #[ORM\Column(nullable: true)]
    private ?int $ageMax = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

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

    public function getAgeMin(): ?int
    {
        return $this->ageMin;
    }

    public function setAgeMin(int $ageMin): static
    {
        $this->ageMin = $ageMin;

        return $this;
    }

    public function getAgeMax(): ?int
    {
        return $this->ageMax;
    }

    public function setAgeMax(?int $ageMax): static
    {
        $this->ageMax = $ageMax;

        return $this;
    }
}
