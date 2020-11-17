<?php

namespace Yproximite\WannaSpeakBundle\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Yproximite\WannaSpeakBundle\Exception\WannaSpeakApiException;
use Yproximite\WannaSpeakBundle\HttpClientInterface;

class WannaSpeakApiExceptionTest extends TestCase
{
    public function testCreate(): void
    {
        $exception = WannaSpeakApiException::create(123, 'My message');

        static::assertSame(123, $exception->getStatusCode());
        static::assertSame('My message', $exception->getMessage());
    }

    public function testCreateUnknown(): void
    {
        $exception = WannaSpeakApiException::createUnknown('My message');

        static::assertSame(HttpClientInterface::CODE_UNKNOWN_ERROR, $exception->getStatusCode());
        static::assertSame('My message', $exception->getMessage());
    }
}