<?php declare(strict_types=1);

namespace Tests\Yproximite\WannaSpeakBundle;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpClient\ScopingHttpClient;
use Yproximite\WannaSpeakBundle\Api\WannaSpeakHttpClient;

class WannaSpeakHttpClientTest extends TestCase
{
    public function test(): void
    {
        $wannaspeakClient = new WannaSpeakHttpClient('a', 'b', false, 'https://www-2.wannaspeak.com/api/api.php');
    }

    public function testWithCustomHttpClient(): void
    {
        $client = new WannaSpeakHttpClient(
            'a',
            'b',
            false,
            null,
            ScopingHttpClient::forBaseUri(new MockHttpClient(), 'https://www-2.wannaspeak.com/api/api.php')
        );

        /** @var MockResponse $response */
        $response = $client->createAndSendRequest([]);

        static::assertSame('https://www-2.wannaspeak.com/api/api.php', $response->getInfo('url'));
    }
}
