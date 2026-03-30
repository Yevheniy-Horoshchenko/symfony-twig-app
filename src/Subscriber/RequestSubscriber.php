<?php

namespace App\Subscriber;

use App\Enum\Currency;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        $currency = $request->attributes->get('currency');

        $currencyEnum = Currency::tryFrom($currency);

        if (!$currencyEnum) {
            $request->attributes->set('currency', Currency::USD);
        }
    }
}
