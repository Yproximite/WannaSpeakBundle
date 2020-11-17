<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Api;

use Yproximite\WannaSpeakBundle\HttpClientInterface;

class Sounds implements SoundsInterface
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function list(/* TODO: implement parameters */)
    {
        $this->client->request(self::API, 'list', []);
    }

    public function upload(/* TODO: implement parameters */)
    {
        $this->client->request(self::API, 'upload', []);
    }

    public function delete(/* TODO: implement parameters */)
    {
        $this->client->request(self::API, 'delete', []);
    }
}
