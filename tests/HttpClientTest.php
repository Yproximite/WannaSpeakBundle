<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Tests;

use PHPUnit\Framework\TestCase;
use Yproximite\WannaSpeakBundle\Exception\TestModeException;

class HttpClientTest extends TestCase
{
    use HttpClientTestTrait;

    public function testRequestInTestMode()
    {
        $this->expectException(TestModeException::class);

        $client = $this->createHttpClient(null, true);

        $client->request('ct', 'list');
    }
}
