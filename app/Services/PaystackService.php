<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class PaystackService
{
    private PendingRequest $client;
    public function __construct()
    {
        $this->client = Http::withToken(config('services.paystack.secret'))
                            ->baseUrl(config('services.paystack.payment_url'))
                            ->acceptJson();
    }

    public function getBanks(): array
    {
        $response = $this->client->get('/bank', ['country' => 'nigeria']);

        if ($response->successful()) {
            return $response->json()['data'];
        }

        throw new \Exception('Failed to fetch banks from Paystack: ' . $response->body());
    }
}
