<?php

namespace iMemento\ActivityLog;

use GuzzleHttp\Client;

class RequestService
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function post($url, array $payload = null/*, string $jwt = null*/)
    {
        $options = [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                //'Authorization' => 'Bearer ' . $jwt,
            ],
            'json' => $payload ?? [],
        ];

        return $this->client->post($url, $options);
    }
}