<?php

namespace App\Services;

use App\Services\PaymentProviders\MontonioProvider;
use App\Services\PaymentProviders\PayseraProvider;
use App\Services\PaymentProviders\MakeCommerceProvider;
use App\Services\PaymentProviders\TestProvider;
use App\Services\PaymentProviders\PaymentProviderInterface;
use InvalidArgumentException;

class PaymentService
{
    private ?PaymentProviderInterface $provider = null;

    public function getProvider(): PaymentProviderInterface
    {
        if ($this->provider !== null) {
            return $this->provider;
        }

        $name = config('payments.default');

        return $this->provider = match ($name) {
            'montonio' => new MontonioProvider(),
            'paysera' => new PayseraProvider(),
            'makecommerce' => new MakeCommerceProvider(),
            'test' => new TestProvider(),
            default => throw new InvalidArgumentException("Unknown payment provider: {$name}"),
        };
    }

    public function setProvider(PaymentProviderInterface $provider): void
    {
        $this->provider = $provider;
    }
}
