<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Tests\Api;

use PHPUnit\Framework\TestCase;
use Yproximite\WannaSpeakBundle\Api\Statistics;
use Yproximite\WannaSpeakBundle\Tests\HttpClientTestTrait;

class StatisticsTest extends TestCase
{
    use HttpClientTestTrait;

    public function testDid(): void
    {
        $statistics = new Statistics(
            $this->createHttpClient(/* ... */)
        );

        $statistics->did();
    }
}
