<?php

declare(strict_types=1);

namespace spec\Yproximite\WannaSpeakBundle\Api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Mime\Part\DataPart;
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

    public function let(HttpClientInterface $client, ResponseInterface $response): void
    {
        $this->beConstructedWith($client);

        // Since we are in spec, the response content will always be the, see PHPUnit tests for real response asserting.
        $response
            ->toArray()
            ->willReturn(['error' => null, 'data' => [/* ... */]]);
    }

    public function it_should_list(HttpClientInterface $client, ResponseInterface $response)
    {
        $client
            ->request(SoundsInterface::API, 'available', [])
            ->shouldBeCalled()
            ->willReturn($response);

        $this
            ->list()
            ->shouldBe(['error' => null, 'data' => [/* ... */]]);
    }

    public function it_should_upload_from_path(HttpClientInterface $client, ResponseInterface $response)
    {
        $client
            ->request(
                SoundsInterface::API,
                'upload',
                ['name' => 'the name'],
                Argument::that(function ($args) {
                    return $args['sound'] instanceof DataPart;
                })
            )
            ->shouldBeCalled()
            ->willReturn($response);

        $this
            ->upload(__DIR__.'/../../../../tests/fixtures/callee.mp3', 'the name')
            ->shouldBe(['error' => null, 'data' => [/* ... */]]);
    }

    public function it_should_upload_from_file(HttpClientInterface $client, ResponseInterface $response)
    {
        $client
            ->request(
                SoundsInterface::API,
                'upload',
                ['name' => 'the name'],
                Argument::that(function ($args) {
                    return $args['sound'] instanceof DataPart;
                })
            )
            ->shouldBeCalled()
            ->willReturn($response);

        $file = new \SplFileInfo(__DIR__.'/../../../../tests/fixtures/callee.mp3');

        $this
            ->upload($file, 'the name')
            ->shouldBe(['error' => null, 'data' => [/* ... */]]);
    }

    public function it_should_delete(HttpClientInterface $client, ResponseInterface $response)
    {
        $client
            ->request(SoundsInterface::API, 'delete', [
                'name' => 'the name',
            ])
            ->shouldBeCalled()
            ->willReturn($response);

        $this
            ->delete('the name')
            ->shouldBe(['error' => null, 'data' => [/* ... */]]);
    }
}
