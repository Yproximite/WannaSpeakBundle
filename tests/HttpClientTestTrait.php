<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Tests;

use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Yproximite\WannaSpeakBundle\HttpClient;

trait HttpClientTestTrait
{
    /**
     * @param callable|callable[]|ResponseInterface|ResponseInterface[]|iterable|null $responseFactory
     */
    public function createHttpClient($responseFactory = null, bool $test = false): HttpClient
    {
        $baseUri = 'https://www-2.wannaspeak.com/api/api.php';

        return new HttpClient(
            '9999999999',
            '0000000000',
            $baseUri,
            $test,
            new MockHttpClient($responseFactory, $baseUri)
        );
    }
}
