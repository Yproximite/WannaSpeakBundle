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

    public function let(HttpClientInterface $client, ResponseInterface $response): void
    {
        $this->beConstructedWith($client);

        // Since we are in spec, the response content will always be the, see PHPUnit tests for real response asserting.
        $response
            ->toArray()
            ->willReturn(['error' => null, 'data' => [/* ... */]]);
    }

    public function it_should_list_all_numbers_by_default(HttpClientInterface $client, ResponseInterface $response): void
    {
        $client
            ->request(CallTrackingsInterface::API, 'list', [])
            ->shouldBeCalled()
            ->willReturn($response);

        $this->getNumbers()->shouldBe(['error' => null, 'data' => [/* ... */]]);
    }

    public function it_should_list_all_numbers(HttpClientInterface $client, ResponseInterface $response): void
    {
        $client
            ->request(CallTrackingsInterface::API, 'list', ['tag1' => 'value'])
            ->shouldBeCalled()
            ->willReturn($response);

        $this
            ->getNumbers(CallTrackingsInterface::NUMBERS_LIST, ['tag1' => 'value'])
            ->shouldBe(['error' => null, 'data' => [/* ... */]]);
    }

    public function it_should_list_available_numbers(HttpClientInterface $client, ResponseInterface $response): void
    {
        $client
            ->request(CallTrackingsInterface::API, 'available', ['did_pattern' => '331%'])
            ->shouldBeCalled()
            ->willReturn($response);

        $this
            ->getNumbers(CallTrackingsInterface::NUMBERS_AVAILABLE, ['did_pattern' => '331%'])
            ->shouldBe(['error' => null, 'data' => [/* ... */]]);
    }

    public function it_should_list_deleted_numbers(HttpClientInterface $client, ResponseInterface $response): void
    {
        $client
            ->request(CallTrackingsInterface::API, 'deleted', [])
            ->shouldBeCalled()
            ->willReturn($response);

        $this
            ->getNumbers(CallTrackingsInterface::NUMBERS_DELETED)
            ->shouldBe(['error' => null, 'data' => [/* ... */]]);
    }

    public function it_should_add(HttpClientInterface $client, ResponseInterface $response): void
    {
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

        $this
            ->add('33176280XXX', '33700XXYYZZ', 'The name', [
                'tag1' => 'Tag 1',
                'tag2' => 'Tag 2',
            ])
            ->shouldBe(['error' => null, 'data' => [/* ... */]]);
    }

    public function it_should_modify(HttpClientInterface $client, ResponseInterface $response): void
    {
        $client
            ->request(CallTrackingsInterface::API, 'modify', [
                'did'         => '33176280XXX',
                'destination' => '33474123XXX',
                'name'        => 'My CallTracking',
            ])
            ->shouldBeCalled()
            ->willReturn($response);

        $this
            ->modify('33176280XXX', '33474123XXX', ['name' => 'My CallTracking'])
            ->shouldBe(['error' => null, 'data' => [/* ... */]]);
    }

    public function it_should_delete(HttpClientInterface $client, ResponseInterface $response): void
    {
        $client
            ->request(CallTrackingsInterface::API, 'delete', [
                'did' => '33176280XXX',
            ])
            ->shouldBeCalled()
            ->willReturn($response);

        $this
            ->delete('33176280XXX')
            ->shouldBe(['error' => null, 'data' => [/* ... */]]);
    }

    public function it_should_expires(HttpClientInterface $client, ResponseInterface $response): void
    {
        $client
            ->request(CallTrackingsInterface::API, 'modify', [
                'did'         => '33176280XXX',
                'destination' => '33474123XXX',
                'stopdate'    => '2020-11-19',
            ])
            ->shouldBeCalled()
            ->willReturn($response);

        $this
            ->expires('33176280XXX', '33474123XXX', new \DateTime('2020-11-19'))
            ->shouldBe(['error' => null, 'data' => [/* ... */]]);
    }
}
