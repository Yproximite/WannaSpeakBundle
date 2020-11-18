<?php

declare(strict_types=1);

namespace spec\Yproximite\WannaSpeakBundle\Api;

use PhpSpec\ObjectBehavior;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Yproximite\WannaSpeakBundle\Api\Sounds;
use Yproximite\WannaSpeakBundle\Api\SoundsInterface;
use Yproximite\WannaSpeakBundle\HttpClientInterface;

class SoundsSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Sounds::class);
    }

    public function let(HttpClientInterface $client): void
    {
        $this->beConstructedWith($client);
    }

    public function it_should_list(HttpClientInterface $client, ResponseInterface $response)
    {
        $client->request(SoundsInterface::API, 'list', [])->shouldBeCalled();

        $this->list();
    }

    public function it_should_upload(HttpClientInterface $client, ResponseInterface $response)
    {
        $client->request(SoundsInterface::API, 'upload', [])->shouldBeCalled();

        $this->upload();
    }

    public function it_should_delete(HttpClientInterface $client, ResponseInterface $response)
    {
        $client->request(SoundsInterface::API, 'delete', [])->shouldBeCalled();

        $this->delete();
    }
}
