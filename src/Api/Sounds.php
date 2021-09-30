<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Api;

use Symfony\Component\Mime\Part\DataPart;
use Yproximite\WannaSpeakBundle\HttpClientInterface;

class Sounds implements SoundsInterface
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function list(array $additionalArguments = []): array
    {
        $response = $this->client->request(self::API, 'available', $additionalArguments);

        /* @phpstan-ignore-next-line */
        return $response->toArray();
    }

    public function upload($file, string $name, array $additionalArguments = []): array
    {
        /** @var string|false $path */
        $path = is_string($file) ? $file : $file->getRealPath();
        if (false === $path) {
            throw new \InvalidArgumentException(sprintf('A string or an instance of "SplInfo" is required for uploading the file.'));
        }

        $response = $this->client->request(
            self::API,
            'upload',
            array_merge($additionalArguments, [
                'name' => $name,
            ]),
            [
                'sound' => DataPart::fromPath($path),
            ]
        );

        return $response->toArray(); // @phpstan-ignore-line
    }

    public function delete(string $name, array $additionalArguments = []): array
    {
        $arguments = array_merge($additionalArguments, [
            'name' => $name,
        ]);

        $response = $this->client->request(self::API, 'delete', $arguments);

        return $response->toArray(); // @phpstan-ignore-line
    }
}
