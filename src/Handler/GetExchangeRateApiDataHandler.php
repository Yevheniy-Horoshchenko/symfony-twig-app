<?php

namespace App\Handler;

use App\Entity\ExchangeRate;
use App\Enum\Currency;
use App\Repository\ExchangeRateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetExchangeRateApiDataHandler
{
    private const API_KEY = '41fe2f8ae3de80d44c33aa2b';

    private const CURRENCIES = ['UAH', 'USD', 'EUR', 'JPY'];

    public function __construct(
        protected HttpClientInterface $client,
        protected ExchangeRateRepository $exchangeRateRepository,
        protected EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(Currency $currency): array
    {
        $necessaryRateCurrencies = $this->exchangeRateRepository->getConversionRates($currency);

        if (!$necessaryRateCurrencies) {
            $conversionRates = $this->getConversionRatesFromApi($currency);

            $necessaryRateCurrencies = $this->getNecessaryRateCurrencies($conversionRates);

            $this->saveNecessaryRateCurrencies($necessaryRateCurrencies, $currency);
        }

        return $necessaryRateCurrencies;
    }

    protected function getConversionRatesFromApi(Currency $currency): array
    {
        $apiUrl = 'https://v6.exchangerate-api.com/v6/'.static::API_KEY.'/latest/'.$currency->value;

        try {
            $response = $this->client->request('GET', $apiUrl);

            $data = $response->toArray();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

        return $data['conversion_rates'];
    }

    protected function getNecessaryRateCurrencies(array $conversionRates): array
    {
        $necessaryRateCurrencies = [];

        foreach (static::CURRENCIES as $currency) {
            $necessaryRateCurrencies[$currency] = $conversionRates[$currency] ?? null;
        }

        return $necessaryRateCurrencies;
    }

    protected function saveNecessaryRateCurrencies(array $rateCurrencies, Currency $currency): void
    {
        $exchangeRate = (new ExchangeRate())
            ->setCurrency($currency->value)
            ->setConversionRates($rateCurrencies);

        $this->entityManager->persist($exchangeRate);
        $this->entityManager->flush();
    }
}
