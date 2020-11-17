<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle;

use Symfony\Contracts\HttpClient\ResponseInterface;
use Yproximite\WannaSpeakBundle\Exception\TestModeException;

interface HttpClientInterface
{
    /**
     * @param array<string,mixed> $options Symfony HttpClient options
     *
     * @throws TestModeException
     */
    public function request(string $api, string $method, array $options = []): ResponseInterface;
}
