<?php

// src/Service/DofusApiService.php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class DofusApiService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetchItems($skip = 0, $limit = 20)
    {
        $url = "https://api.beta.dofusdb.fr/items?\$limit=$limit&\$skip=$skip";
        $response = $this->client->request('GET', $url);

        return $response->toArray();
    }
}
