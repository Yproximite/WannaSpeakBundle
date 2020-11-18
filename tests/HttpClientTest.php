<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Response\MockResponse;
use Yproximite\WannaSpeakBundle\Exception\Api\AuthFailedException;
use Yproximite\WannaSpeakBundle\Exception\Api\BadAccountException;
use Yproximite\WannaSpeakBundle\Exception\Api\CantUseDidAsDestinationException;
use Yproximite\WannaSpeakBundle\Exception\Api\DidAlreadyReservedException;
use Yproximite\WannaSpeakBundle\Exception\Api\DidNotExistsOrNotOwnedException;
use Yproximite\WannaSpeakBundle\Exception\Api\MethodNotImplementedException;
use Yproximite\WannaSpeakBundle\Exception\Api\MissingArgumentsException;
use Yproximite\WannaSpeakBundle\Exception\Api\NoDidAvailableForRegionException;
use Yproximite\WannaSpeakBundle\Exception\Api\UnknownApiException;
use Yproximite\WannaSpeakBundle\Exception\Api\UnknownException;
use Yproximite\WannaSpeakBundle\Exception\Api\UnknownMethodException;
use Yproximite\WannaSpeakBundle\Exception\TestModeException;

class HttpClientTest extends TestCase
{
    use HttpClientTestTrait;

    public function testRequestInTestMode(): void
    {
        $this->expectException(TestModeException::class);

        $client = $this->createHttpClient(null, true);

        $client->request('the api', 'the method');
    }

    public function testRequestWithoutError(): void
    {
        $client = $this->createHttpClient(new MockResponse(
            (string) json_encode([
                'error' => null,
            ])
        ));

        $response = $client->request('the api', 'the method');

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

        $response = $client->request('the api', 'the method');

        static::assertSame(['error' => ['nb' => 200]], $response->toArray());
    }

    public function testRequestWithCode401(): void
    {
        $this->expectException(AuthFailedException::class);

        $client = $this->createHttpClient(new MockResponse(
            (string) json_encode([
                'error' => [
                    'nb'  => 401,
                    'txt' => 'Auth Failed',
                ],
            ])
        ));

        $client->request('the api', 'the method');
    }

    public function testRequestWithCode403(): void
    {
        $this->expectException(BadAccountException::class);

        $client = $this->createHttpClient(new MockResponse(
            (string) json_encode([
                'error' => [
                    'nb'  => 403,
                    'txt' => 'Bad account ID or not Call tracking enabled',
                ],
            ])
        ));

        $client->request('the api', 'the method');
    }

    public function testRequestWithCode404(): void
    {
        $this->expectException(UnknownMethodException::class);

        $client = $this->createHttpClient(new MockResponse(
            (string) json_encode([
                'error' => [
                    'nb'  => 404,
                    'txt' => 'Unknown method',
                ],
            ])
        ));

        $client->request('the api', 'the method');
    }

    public function testRequestWithCode405(): void
    {
        $this->expectException(MethodNotImplementedException::class);

        $client = $this->createHttpClient(new MockResponse(
            (string) json_encode([
                'error' => [
                    'nb'  => 405,
                    'txt' => 'Method not yet implemented',
                ],
            ])
        ));

        $client->request('the api', 'the method');
    }

    public function testRequestWithCode406(): void
    {
        $this->expectException(NoDidAvailableForRegionException::class);

        $client = $this->createHttpClient(new MockResponse(
            (string) json_encode([
                'error' => [
                    'nb'  => 406,
                    'txt' => 'NO DID AVAILABLE FOR THAT REGION',
                ],
            ])
        ));

        $client->request('the api', 'the method');
    }

    public function testRequestWithCode407DidAlreadyReserved(): void
    {
        $this->expectException(DidAlreadyReservedException::class);

        $client = $this->createHttpClient(new MockResponse(
            (string) json_encode([
                'error' => [
                    'nb'  => 407,
                    'txt' => 'DID already reserved or not tested',
                ],
            ])
        ));

        $client->request('the api', 'the method');
    }

    public function testRequestWithCode407DidNotExists(): void
    {
        $this->expectException(DidNotExistsOrNotOwnedException::class);

        $client = $this->createHttpClient(new MockResponse(
            (string) json_encode([
                'error' => [
                    'nb'  => 407,
                    'txt' => 'DID not exists or not owned',
                ],
            ])
        ));

        $client->request('the api', 'the method');
    }

    public function testRequestWithCode407Unknown(): void
    {
        $this->expectException(UnknownException::class);

        $client = $this->createHttpClient(new MockResponse(
            (string) json_encode([
                'error' => [
                    'nb'  => 407,
                    'txt' => '???',
                ],
            ])
        ));

        $client->request('the api', 'the method');
    }

    public function testRequestWithCode410(): void
    {
        $this->expectException(CantUseDidAsDestinationException::class);

        $client = $this->createHttpClient(new MockResponse(
            (string) json_encode([
                'error' => [
                    'nb'  => 410,
                    'txt' => "can't use DID as destination",
                ],
            ])
        ));

        $client->request('the api', 'the method');
    }

    public function testRequestWithCode500(): void
    {
        $this->expectException(MissingArgumentsException::class);

        $client = $this->createHttpClient(new MockResponse(
            (string) json_encode([
                'error' => [
                    'nb'  => 500,
                    'txt' => 'Missing arguments (...)',
                ],
            ])
        ));

        $client->request('the api', 'the method');
    }

    public function testRequestWithCode501(): void
    {
        $this->expectException(UnknownApiException::class);

        $client = $this->createHttpClient(new MockResponse(
            (string) json_encode([
                'error' => [
                    'nb'  => 501,
                    'txt' => 'Unknown API',
                ],
            ])
        ));

        $client->request('the api', 'the method');
    }

    public function testRequestWithUnknownCode(): void
    {
        $this->expectException(UnknownException::class);

        $client = $this->createHttpClient(new MockResponse(
            (string) json_encode([
                'error' => [
                    'nb'  => 999,
                    'txt' => '???',
                ],
            ])
        ));

        $client->request('the api', 'the method');
    }

    public function testRequestWithDifferentTypesOfArguments(): void
    {
        $fieldsAllFound = false;

        $client = $this->createHttpClient(static function (string $method, string $url, array $options) use (&$fieldsAllFound) {
            $body = '';
            while ($part = $options['body']()) {
                $body .= $part;
            }

            static::assertRegExp("/name=\"name\"[\r\n]+The name[\r\n]+--/", $body);
            static::assertRegExp("/name=\"tag1\"[\r\n]+12345[\r\n]+--/", $body);
            static::assertRegExp("/name=\"foo1\"[\r\n]+true[\r\n]+--/", $body);
            static::assertRegExp("/name=\"foo2\"[\r\n]+false[\r\n]+--/", $body);

            $fieldsAllFound = true;

            return new MockResponse(
                (string) json_encode([
                    'error' => null,
                ])
            );
        });

        $client->request('the api', 'the method', [
            'name' => 'The name',
            'tag1' => 12345,
            'foo1' => true,
            'foo2' => false,
        ]);

        static::assertTrue($fieldsAllFound, 'Fields "name", "tag1", "foo1", and "foo2" were not all founds in the request body.');
    }
}
