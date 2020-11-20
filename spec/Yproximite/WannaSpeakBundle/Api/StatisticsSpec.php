<?php

declare(strict_types=1);

namespace spec\Yproximite\WannaSpeakBundle\Api;

use PhpSpec\ObjectBehavior;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Yproximite\WannaSpeakBundle\Api\Statistics;
use Yproximite\WannaSpeakBundle\Api\StatisticsInterface;
use Yproximite\WannaSpeakBundle\HttpClientInterface;

class StatisticsSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Statistics::class);
    }

    public function let(HttpClientInterface $client, ResponseInterface $response): void
    {
        $this->beConstructedWith($client);

        // Since we are in spec, the response content will always be the, see PHPUnit tests for real response asserting.
        $response
            ->toArray()
            ->willReturn(['error' => null, 'data' => [/* ... */]]);
    }

    public function it_should_get_stats(HttpClientInterface $client, ResponseInterface $response)
    {
        $client
            ->request(StatisticsInterface::API, 'did', [
                'tag1' => '12345',
            ])
            ->shouldBeCalled()
            ->willReturn($response);

        $this
            ->did(['tag1' => '12345'])
            ->shouldBe(['error' => null, 'data' => [/* ... */]]);
    }
}
