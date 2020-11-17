<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Response\MockResponse;
use Yproximite\WannaSpeakBundle\Api\CallTrackingsInterface;
use Yproximite\WannaSpeakBundle\Exception\TestModeException;
use Yproximite\WannaSpeakBundle\Exception\WannaSpeakApiException;
use Yproximite\WannaSpeakBundle\HttpClientInterface;

class HttpClientTest extends TestCase
{
    use HttpClientTestTrait;

    public function testRequestInTestMode(): void
    {
        $this->expectException(TestModeException::class);

        $client = $this->createHttpClient(null, true);

        $client->request(CallTrackingsInterface::API, CallTrackingsInterface::NUMBERS_AVAILABLE);
    }

    public function testRequestWithoutError(): void
    {
        $client = $this->createHttpClient(new MockResponse(
            (string) json_encode([
                'error' => null,
            ])
        ));

        $response = $client->request(CallTrackingsInterface::API, CallTrackingsInterface::NUMBERS_AVAILABLE);

        static::assertSame(['error' => null], $response->toArray());
    }

    /**
     * This test is maybe useless, because WannaSpeak does not return a response with nb "200" when using JSON format...
     */
    public function testRequestWithCode200(): void
    {
        $client = $this->createHttpClient(new MockResponse(
            (string) json_encode([
                'error' => [
                    'nb' => 200,
                ],
            ])
        ));

        $response = $client->request(CallTrackingsInterface::API, CallTrackingsInterface::NUMBERS_AVAILABLE);

        static::assertSame(['error' => ['nb' => 200]], $response->toArray());
    }

    public function testRequestWithCode401(): void
    {
        $this->expectExceptionObject(
            WannaSpeakApiException::create(HttpClientInterface::CODE_AUTH_FAILED, 'Auth Failed')
        );

        $client = $this->createHttpClient(new MockResponse(
            (string) json_encode([
                'error' => [
                    'nb'  => 401,
                    'txt' => 'Auth Failed',
                ],
            ])
        ));

        $client->request(CallTrackingsInterface::API, CallTrackingsInterface::NUMBERS_AVAILABLE);
    }

    public function testRequestWithCode403(): void
    {
        $this->expectExceptionObject(
            WannaSpeakApiException::create(HttpClientInterface::CODE_BAD_ACCOUNT, 'Bad account ID or not Call tracking enabled')
        );

        $client = $this->createHttpClient(new MockResponse(
            (string) json_encode([
                'error' => [
                    'nb'  => 403,
                    'txt' => 'Bad account ID or not Call tracking enabled',
                ],
            ])
        ));

        $client->request(CallTrackingsInterface::API, CallTrackingsInterface::NUMBERS_AVAILABLE);
    }

    public function testRequestWithCode404(): void
    {
        $this->expectExceptionObject(
            WannaSpeakApiException::create(HttpClientInterface::CODE_UNKNOWN_METHOD, 'Unknown method')
        );
        $client = $this->createHttpClient(new MockResponse(
            (string) json_encode([
                'error' => [
                    'nb'  => 404,
                    'txt' => 'Unknown method',
                ],
            ])
        ));

        $client->request(CallTrackingsInterface::API, CallTrackingsInterface::NUMBERS_AVAILABLE);
    }

    public function testRequestWithCode405(): void
    {
        $this->expectExceptionObject(
            WannaSpeakApiException::create(HttpClientInterface::CODE_METHOD_NOT_IMPLEMENTED, 'Method not yet implemented')
        );
        $client = $this->createHttpClient(new MockResponse(
            (string) json_encode([
                'error' => [
                    'nb'  => 405,
                    'txt' => 'Method not yet implemented',
                ],
            ])
        ));

        $client->request(CallTrackingsInterface::API, CallTrackingsInterface::NUMBERS_AVAILABLE);
    }

    public function testRequestWithCode406(): void
    {
        $this->expectExceptionObject(
            WannaSpeakApiException::create(HttpClientInterface::CODE_NO_DID_AVAILABLE_FOR_REGION, 'NO DID AVAILABLE FOR THAT REGION')
        );
        $client = $this->createHttpClient(new MockResponse(
            (string) json_encode([
                'error' => [
                    'nb'  => 406,
                    'txt' => 'NO DID AVAILABLE FOR THAT REGION',
                ],
            ])
        ));

        $client->request(CallTrackingsInterface::API, CallTrackingsInterface::NUMBERS_AVAILABLE);
    }

    public function testRequestWithCode410(): void
    {
        $this->expectExceptionObject(
            WannaSpeakApiException::create(HttpClientInterface::CODE_CANT_USE_DID_AS_DESTINATION, "can't use DID as destination")
        );

        $client = $this->createHttpClient(new MockResponse(
            (string) json_encode([
                'error' => [
                    'nb'  => 410,
                    'txt' => "can't use DID as destination",
                ],
            ])
        ));
        $client->request(CallTrackingsInterface::API, CallTrackingsInterface::NUMBERS_AVAILABLE);
    }

    public function testRequestWithCode500(): void
    {
        $this->expectExceptionObject(
            WannaSpeakApiException::create(HttpClientInterface::CODE_MISSING_ARGUMENTS, 'Missing arguments (...)')
        );

        $client = $this->createHttpClient(new MockResponse(
            (string) json_encode([
                'error' => [
                    'nb'  => 500,
                    'txt' => 'Missing arguments (...)',
                ],
            ])
        ));

        $client->request(CallTrackingsInterface::API, CallTrackingsInterface::NUMBERS_AVAILABLE);
    }

    public function testRequestWithCode501(): void
    {
        $this->expectExceptionObject(
            WannaSpeakApiException::create(HttpClientInterface::CODE_UNKNOWN_API, 'Unknown API')
        );

        $client = $this->createHttpClient(new MockResponse(
            (string) json_encode([
                'error' => [
                    'nb'  => 501,
                    'txt' => 'Unknown API',
                ],
            ])
        ));

        $client->request(CallTrackingsInterface::API, CallTrackingsInterface::NUMBERS_AVAILABLE);
    }
}
