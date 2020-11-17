<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle;

use Symfony\Contracts\HttpClient\ResponseInterface;
use Yproximite\WannaSpeakBundle\Exception\TestModeException;

class HttpClient implements HttpClientInterface
{
    public function request(string $api, string $method, array $options = []): ResponseInterface
    {

    }
}
