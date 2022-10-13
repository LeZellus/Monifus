<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiService
{
    private $client;

    public function __construct(HttpClientInterface $client) {
        $this->client = $client;
    }

    public function getDofapiData(): array
    {
        $response = $this->client->request(
            "GET",
            "https://fr.dofus.dofapi.fr/resources"
        );

        return $response->toArray();
    }

}