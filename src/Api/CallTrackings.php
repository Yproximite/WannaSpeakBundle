<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Api;

use Yproximite\WannaSpeakBundle\HttpClientInterface;

class CallTrackings implements CallTrackingsInterface
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getNumbers(?string $method = null /* TODO: implement parameters */)
    {
        $method = $method ?? static::NUMBERS_LIST;

        $this->client->request(self::API, $method, []);
    }

    public function add(/* TODO: implement parameters */)
    {
        $this->client->request(self::API, 'add', []);
    }

    public function modify(/* TODO: implement parameters */)
    {
        $this->client->request(self::API, 'modify', []);
    }

    public function delete(/* TODO: implement parameters */)
    {
        $this->client->request(self::API, 'delete', []);
    }

    public function expires(/* TODO: implement parameters */)
    {
        $this->client->request(self::API, 'modify', []);
    }
}
