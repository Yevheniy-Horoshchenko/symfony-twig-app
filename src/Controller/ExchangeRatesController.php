<?php

namespace App\Controller;

use App\Enum\Currency;
use App\Handler\GetExchangeRateApiDataHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class ExchangeRatesController extends AbstractController
{
    #[Route('/exchange-rates', name: 'exchange_rates', methods: [Request::METHOD_GET])]
    public function getExchangeRates(): Response
    {
        return $this->render('exchange_rates.html.twig');
    }

    #[Route('/exchange-rates/{currency}', name: 'currency_rate', methods: [Request::METHOD_GET])]
    public function getExchangeRateCurrencies(
        Currency $currency,
        GetExchangeRateApiDataHandler $getExchangeRateApiDataHandler,
    ): Response {
        $rates = $getExchangeRateApiDataHandler($currency);

        return $this->render('rates.html.twig', ['rates' => $rates]);
    }
}
