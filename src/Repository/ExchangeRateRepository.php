<?php

namespace App\Repository;

use App\Entity\ExchangeRate;
use App\Enum\Currency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ExchangeRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, ExchangeRate::class);
    }

    public function getConversionRates(Currency $currency): array
    {
        $queryBuilder = $this->createQueryBuilder('er')
            ->select('er.conversionRates')
            ->where('er.currency = :currency')
            ->setParameter('currency', $currency->value)
            ->getQuery()
            ->getOneOrNullResult();

        return $queryBuilder['conversionRates'] ?? [];
    }
}
