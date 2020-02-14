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

    public function it_add_calltracking(WannaSpeakHttpClient $httpClient, ResponseInterface $response)
    {
        $response->toArray()->shouldBeCalledOnce()->willReturn([]);

        $httpClient
            ->createAndSendRequest([
                'api'         => 'ct',
                'method'      => 'add',
                'name'        => 'Name',
                'destination' => '33122334455',
                'did'         => '33988776655',
            ])
            ->shouldBeCalledOnce()
            ->willReturn($response);

        $this->callTracking('add', 'Name', '33122334455', '33988776655');
    }

    public function it_modify_calltracking(WannaSpeakHttpClient $httpClient, ResponseInterface $response)
    {
        $response->toArray()->shouldBeCalledOnce()->willReturn([]);

        $httpClient
            ->createAndSendRequest([
                'api'         => 'ct',
                'method'      => 'modify',
                'name'        => 'Name',
                'destination' => '33122334455',
                'did'         => '33988776655',
            ])
            ->shouldBeCalledOnce()
            ->willReturn($response);

        $this->callTracking('modify', 'Name', '33122334455', '33988776655');
    }

    public function it_add_calltracking_with_additional_args(WannaSpeakHttpClient $httpClient, ResponseInterface $response)
    {
        $response->toArray()->shouldBeCalledOnce()->willReturn([]);

        $httpClient
            ->createAndSendRequest([
                'api'         => 'ct',
                'method'      => 'add',
                'name'        => 'Name',
                'destination' => '33122334455',
                'did'         => '33988776655',
                // caller/callee
                'tag3' => 'callerid:33988776655',
                'leg1' => 'caller_message',
                'leg2' => 'callee_message',
                // sms
                'sms' => '33611223344',
                'tag4' => 'Sender name',
                'tag5' => 'Company name',
            ])
            ->shouldBeCalledOnce()
            ->willReturn($response);

        $this->callTracking('add', 'Name', '33122334455', '33988776655', [
            // caller/callee
            'tag3' => 'callerid:33988776655',
            'leg1' => 'caller_message',
            'leg2' => 'callee_message',
            // sms
            'sms' => '33611223344',
            'tag4' => 'Sender name',
            'tag5' => 'Company name',
        ]);
    }

    public function it_add_calltracking_with_additional_args_without_override_initial_args(WannaSpeakHttpClient $httpClient, ResponseInterface $response)
    {
        $response->toArray()->shouldBeCalledOnce()->willReturn([]);

        $httpClient
            ->createAndSendRequest([
                'api'         => 'ct',
                'method'      => 'add',
                'name'        => 'Name',
                'destination' => '33122334455',
                'did'         => '33988776655',
            ])
            ->shouldBeCalledOnce()
            ->willReturn($response);

        $this->callTracking('add', 'Name', '33122334455', '33988776655', [
            'method' => 'foobar',
        ]);
    }
}
