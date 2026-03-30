<?php

namespace App\Entity;

use App\Repository\ExchangeRateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(ExchangeRateRepository::class)]
#[ORM\Table('`exchange_rate`')]
class ExchangeRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $currency = null;

    #[ORM\Column(name: 'conversion_rates', type: Types::JSON)]
    private array $conversionRates = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getConversionRates(): array
    {
        return $this->conversionRates;
    }

    public function setConversionRates(array $conversionRates): static
    {
        $this->conversionRates = $conversionRates;

        return $this;
    }
}
