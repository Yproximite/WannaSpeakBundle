<?php

namespace spec\Yproximite\WannaSpeakBundle\Api;

use PhpSpec\ObjectBehavior;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Yproximite\WannaSpeakBundle\Api\Statistics;
use Yproximite\WannaSpeakBundle\Api\StatisticsInterface;
use Yproximite\WannaSpeakBundle\HttpClientInterface;

class StatisticsSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Statistics::class);
    }

    public function let(HttpClientInterface $client): void
    {
        $this->beConstructedWith($client);
    }

    public function it_should_get_stats(HttpClientInterface $client, ResponseInterface $response)
    {
        $client->request(StatisticsInterface::API, 'did', [])->shouldBeCalled();

        $this->did();
    }
}
