<?php

namespace Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

trait MockGuzzleClient
{
    /**
     * Mock Guzzle client at the app container level.
     *
     * @param array $responses Responses to return. If empty, will return a single empty response.
     *
     * @return void
     */
    public function mockGuzzleClient(array $responses = []): void
    {
        if (empty($responses)) {
            $responses = [new Response()];
        }

        $mock = new MockHandler($responses);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $this->app->extend(Client::class, fn () => $client);
    }
}