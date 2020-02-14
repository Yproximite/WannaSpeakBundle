<?php

namespace spec\Yproximite\WannaSpeakBundle\Api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Yproximite\WannaSpeakBundle\Api\WannaSpeakApi;
use Yproximite\WannaSpeakBundle\Api\WannaSpeakHttpClient;
use Yproximite\WannaSpeakBundle\Exception\WannaSpeakException;

class WannaSpeakApiSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(WannaSpeakApi::class);
    }

    public function let(WannaSpeakHttpClient $httpClient): void
    {
        $this->beConstructedWith($httpClient);
    }

    public function it_test_wannaspeak_exception(WannaSpeakHttpClient $httpClient, ResponseInterface $response)
    {
        $responseData = ['error' => 'error message'];

        $response->toArray()->shouldBeCalledOnce()->willReturn($responseData);
        $httpClient->createAndSendRequest(Argument::any())->shouldBeCalledOnce()->willReturn($response);

        $this->shouldThrow(new WannaSpeakException('WannaSpeak API: error message'))->during('callTracking', ['a', 'b', 'c', 'd']);
    }

    public function it_test_wannaspeak_exception_with_nested_message(WannaSpeakHttpClient $httpClient, ResponseInterface $response)
    {
        $responseData = ['error' => ['txt' => 'error message']];

        $response->toArray()->shouldBeCalledOnce()->willReturn($responseData);
        $httpClient->createAndSendRequest(Argument::any())->shouldBeCalledOnce()->willReturn($response);

        $this->shouldThrow(new WannaSpeakException('WannaSpeak API: error message'))->during('callTracking', ['a', 'b', 'c', 'd']);
    }

    public function it_test_wannaspeak_exception_when_error_is_not_a_string(WannaSpeakHttpClient $httpClient, ResponseInterface $response)
    {
        $responseData = ['error' => 123]; // not a string, will fallback on "Unknown error."

        $response->toArray()->shouldBeCalledOnce()->willReturn($responseData);
        $httpClient->createAndSendRequest(Argument::any())->shouldBeCalledOnce()->willReturn($response);

        $this->shouldThrow(new WannaSpeakException('WannaSpeak API: Unknown error.'))->during('callTracking', ['a', 'b', 'c', 'd']);
    }
}
