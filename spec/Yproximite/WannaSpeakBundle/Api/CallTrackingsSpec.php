<?php

declare(strict_types=1);

namespace spec\Yproximite\WannaSpeakBundle\Api;

use PhpSpec\ObjectBehavior;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Yproximite\WannaSpeakBundle\Api\CallTrackings;
use Yproximite\WannaSpeakBundle\Api\CallTrackingsInterface;
use Yproximite\WannaSpeakBundle\HttpClientInterface;

class CallTrackingsSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(CallTrackings::class);
    }

    public function let(HttpClientInterface $client): void
    {
        $this->beConstructedWith($client);
    }

    public function it_should_list_all_numbers(HttpClientInterface $client, ResponseInterface $response)
    {
        $client->request('ct', 'list', [])->shouldBeCalled();

        $this->getNumbers();
    }

    public function it_should_list_available_numbers(HttpClientInterface $client, ResponseInterface $response)
    {
        $client->request(CallTrackingsInterface::API, 'available', [])->shouldBeCalled();

        $this->getNumbers(CallTrackingsInterface::NUMBERS_AVAILABLE);
    }

    public function it_should_add(HttpClientInterface $client, ResponseInterface $response)
    {
        $client->request(CallTrackingsInterface::API, 'add', [])->shouldBeCalled();

        $this->add();
    }

    public function it_should_modify(HttpClientInterface $client, ResponseInterface $response)
    {
        $client->request(CallTrackingsInterface::API, 'modify', [])->shouldBeCalled();

        $this->modify();
    }

    public function it_should_delete(HttpClientInterface $client, ResponseInterface $response)
    {
        $client->request(CallTrackingsInterface::API, 'delete', [])->shouldBeCalled();

        $this->delete();
    }

    public function it_should_exprise(HttpClientInterface $client, ResponseInterface $response)
    {
        $client->request(CallTrackingsInterface::API, 'modify', [])->shouldBeCalled();

        $this->expires();
    }
}
