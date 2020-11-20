<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Tests\Api;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Response\MockResponse;
use Yproximite\WannaSpeakBundle\Api\Statistics;
use Yproximite\WannaSpeakBundle\Tests\HttpClientTestTrait;

class StatisticsTest extends TestCase
{
    use HttpClientTestTrait;

    public function testDid(): void
    {
        $statistics = new Statistics(
            $this->createHttpClient(new MockResponse(
                (string) json_encode($responseData = [
                    'error' => null,
                    'data'  => [
                        'calls' => [
                            [
                                'starttime' => '2020-01-01 16:28:49',
                                'source'    => 'unknown',
                                'duration'  => 30,
                                // ...
                            ],
                        ],
                    ],
                ])
            ))
        );

        static::assertSame(
            $responseData,
            $statistics->did()
        );
    }
}
