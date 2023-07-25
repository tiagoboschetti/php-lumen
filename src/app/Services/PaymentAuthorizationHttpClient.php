<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class PaymentAuthorizationHttpClient
{
    private const GET_METHOD = 'GET';

    private Client $client;
    private string $baseUrl;
    private string $clientId;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->baseUrl = config('pay-authorization.base_url');
        $this->clientId = config('pay-authorization.client_id');
    }

    public function authorize(): ResponseInterface
    {
        $endpoint = "{$this->baseUrl}/{$this->clientId}";

        $request = new Request(self::GET_METHOD, $endpoint);

        return $this->send($request);
    }

    private  function send(RequestInterface $request): ResponseInterface
    {
        return $this->client->send($request);
    }
}