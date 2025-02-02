<?php

namespace App\Domain\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'locations')]
#[ORM\Index(name: 'location__name__idx', columns: ['name'])]
#[ORM\Index(name: 'location__type__idx', columns: ['type'])]
#[ORM\Index(name: 'location__coordinates__idx', columns: ['latitude', 'longitude'])]
class Location
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: Types::BIGINT, unique: true)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(name: 'name', type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(name: 'type', type: 'string', length: 50)]
    private string $type;

    #[ORM\Column(name: 'latitude', type: 'decimal', precision: 10, scale: 8)]
    private string $latitude;

    #[ORM\Column(name: 'longitude', type: 'decimal', precision: 11, scale: 8)]
    private string $longitude;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }
}
