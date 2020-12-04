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
use Yproximite\WannaSpeakBundle\Exception\Api\SoundNameAlreadyExistsException;
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

    public function testRequestWithCode401SoundNameAlreadyExists(): void
    {
        $this->expectException(SoundNameAlreadyExistsException::class);

        $client = $this->createHttpClient(new MockResponse(
            (string) json_encode([
                'error' => [
                    'nb'  => 401,
                    'txt' => 'Name already Exists',
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

        $client = $this->createHttpClient(static function (string $method, string $url, array $options) use (&$fieldsAllFound): MockResponse {
            static::assertSame('9999999999', $options['query']['id']);
            static::assertRegExp('#^\d{10}-[a-z0-9]{32}$#', $options['query']['key']);
            static::assertSame('the api', $options['query']['api']);
            static::assertSame('the method', $options['query']['method']);
            static::assertSame('The name', $options['query']['query_name']);
            static::assertSame('12345', $options['query']['query_tag1']);
            static::assertSame('1', $options['query']['query_foo1']);
            static::assertSame('0', $options['query']['query_foo2']);

            $body = '';
            while ('' !== $part = $options['body']()) {
                $body .= $part;
            }

            static::assertRegExp("/name=\"body_name\"[\r\n]+The name[\r\n]+--/", $body);
            static::assertRegExp("/name=\"body_tag1\"[\r\n]+12345[\r\n]+--/", $body);
            static::assertRegExp("/name=\"body_foo1\"[\r\n]+1[\r\n]+--/", $body);
            static::assertRegExp("/name=\"body_foo2\"[\r\n]+0[\r\n]+--/", $body);

            $fieldsAllFound = true;

            return new MockResponse(
                (string) json_encode([
                    'error' => null,
                ])
            );
        });

        $client->request(
            'the api',
            'the method',
            [
                'query_name' => 'The name',
                'query_tag1' => 12345,
                'query_foo1' => true,
                'query_foo2' => false,
              ],
            [
                'body_name' => 'The name',
                'body_tag1' => 12345,
                'body_foo1' => true,
                'body_foo2' => false,
            ]
        );

        static::assertTrue($fieldsAllFound, 'Fields "name", "tag1", "foo1", and "foo2" were not all founds in the request body.');
    }
}
