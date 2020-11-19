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

    public function getNumbers(?string $method = null, array $additionalArguments = []): array
    {
        $method = $method ?? static::NUMBERS_LIST;

        if (!in_array($method, $allowedMethods = [static::NUMBERS_LIST, self::NUMBERS_AVAILABLE, self::NUMBERS_DELETED], true)) {
            throw new \InvalidArgumentException(sprintf('Method "%s" for listing numbers is valid, valid values are "%s".', $method, implode('", "', $allowedMethods)));
        }

        $response = $this->client->request(self::API, $method, $additionalArguments);

        return $response->toArray();
    }

    public function add(string $phoneDid, string $phoneDestination, string $name, array $additionalArguments = []): array
    {
        $arguments = array_merge($additionalArguments, [
            'did'         => $phoneDid,
            'destination' => $phoneDestination,
            'name'        => $name,
        ]);

        $response = $this->client->request(self::API, 'add', $arguments);

        return $response->toArray();
    }

    public function modify(string $phoneDid, array $additionalArguments = []): array
    {
        $arguments = array_merge($additionalArguments, [
            'did' => $phoneDid,
        ]);

        $response = $this->client->request(self::API, 'modify', $arguments);

        return $response->toArray();
    }

    public function delete(string $phoneDid, array $additionalArguments = []): array
    {
        $arguments = array_merge($additionalArguments, [
            'did' => $phoneDid,
        ]);

        $response = $this->client->request(self::API, 'delete', $arguments);

        return $response->toArray();
    }

    public function expires(string $phoneDid, \DateTimeInterface $when, array $additionalArguments = []): array
    {
        $arguments = array_merge($additionalArguments, [
            'did'      => $phoneDid,
            'stopdate' => $when->format('Y-m-d'),
        ]);

        $response = $this->client->request(self::API, 'modify', $arguments);

        return $response->toArray();
    }
}
