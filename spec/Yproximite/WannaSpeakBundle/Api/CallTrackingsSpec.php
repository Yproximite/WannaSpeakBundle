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

    public function it_should_list_all_numbers_by_default(HttpClientInterface $client, ResponseInterface $response): void
    {
        $response->toArray()->shouldBeCalled()->willReturn([]);
        $client->request(CallTrackingsInterface::API, 'list', [])->shouldBeCalled()->willReturn($response);

        $this->getNumbers()->shouldBe([]);
    }

    public function it_should_list_all_numbers(HttpClientInterface $client, ResponseInterface $response): void
    {
        $response->toArray()->shouldBeCalled()->willReturn([]);
        $client->request(CallTrackingsInterface::API, 'list', [])->shouldBeCalled()->willReturn($response);

        $this->getNumbers(CallTrackingsInterface::NUMBERS_LIST)->shouldBe([]);
    }

    public function it_should_list_available_numbers(HttpClientInterface $client, ResponseInterface $response): void
    {
        $response->toArray()->shouldBeCalled()->willReturn([]);
        $client->request(CallTrackingsInterface::API, 'available', [])->shouldBeCalled()->willReturn($response);

        $this->getNumbers(CallTrackingsInterface::NUMBERS_AVAILABLE)->shouldBe([]);
    }

    public function it_should_list_deleted_numbers(HttpClientInterface $client, ResponseInterface $response): void
    {
        $response->toArray()->shouldBeCalled()->willReturn([]);
        $client->request(CallTrackingsInterface::API, 'deleted', [])->shouldBeCalled()->willReturn($response);

        $this->getNumbers(CallTrackingsInterface::NUMBERS_DELETED)->shouldBe([]);
    }

    public function it_should_add(HttpClientInterface $client, ResponseInterface $response): void
    {
        $response->toArray()->shouldBeCalled()->willReturn([]);
        $client
            ->request(CallTrackingsInterface::API, 'add', [
                'did'         => '33176280XXX',
                'destination' => '33700XXYYZZ',
                'name'        => 'The name',
                'tag1'        => 'Tag 1',
                'tag2'        => 'Tag 2',
            ])
            ->shouldBeCalled()
            ->willReturn($response);

        $this->add('33176280XXX', '33700XXYYZZ', 'The name', [
            'tag1' => 'Tag 1',
            'tag2' => 'Tag 2',
        ])->shouldBe([]);
    }

    public function it_should_modify(HttpClientInterface $client, ResponseInterface $response): void
    {
        $response->toArray()->shouldBeCalled()->willReturn([]);
        $client
            ->request(CallTrackingsInterface::API, 'modify', [
                'did'  => '33176280XXX',
                'name' => 'My CallTracking',
            ])
            ->shouldBeCalled()
            ->willReturn($response)
        ;

        $this->modify('33176280XXX', ['name' => 'My CallTracking'])->shouldBe([]);
    }

    public function it_should_delete(HttpClientInterface $client, ResponseInterface $response): void
    {
        $response->toArray()->shouldBeCalled()->willReturn([]);
        $client
            ->request(CallTrackingsInterface::API, 'delete', [
                'did' => '33176280XXX',
            ])
            ->shouldBeCalled()
            ->willReturn($response);

        $this->delete('33176280XXX')->shouldBe([]);
    }

    public function it_should_expires(HttpClientInterface $client, ResponseInterface $response): void
    {
        $response->toArray()->shouldBeCalled()->willReturn([]);
        $client
            ->request(CallTrackingsInterface::API, 'modify', [
                'did'      => '33176280XXX',
                'stopdate' => '2020-11-19',
            ])
            ->shouldBeCalled()
            ->willReturn($response);

        $this->expires('33176280XXX', new \DateTime('2020-11-19'))->shouldBe([]);
    }
}
