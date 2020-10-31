<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Api;

use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\Authentication\QueryParam;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Class WannaSpeakHttpClient
 */
class WannaSpeakHttpClient
{
    const DEFAULT_METHOD_POST = 'POST';

    private $accountId;
    private $secretKey;
    private $baseUrl;
    private $test;
    private $httpClient;

    public function __construct(string $accountId, string $secretKey, string $baseUrl, bool $test = false, ?HttpClient $httpClient = null)
    {
        $this->accountId  = $accountId;
        $this->secretKey  = $secretKey;
        $this->baseUrl    = $baseUrl;
        $this->test       = $test;
        $this->httpClient = $httpClient;
    }

    /**
     * @param array<string,mixed> $args
     * @param array<string,mixed> $headers
     */
    public function createAndSendRequest(array $args, array $headers = [], ?StreamInterface $body = null): ResponseInterface
    {
        $defaultArgs = [
            'id' => $this->accountId,
        ];

        $uri = Psr17FactoryDiscovery::findUriFactory()->createUri($this->baseUrl);
        $uri = $uri->withQuery(http_build_query(array_merge($defaultArgs, $args)));

        $request = Psr17FactoryDiscovery::findRequestFactory()->createRequest(self::DEFAULT_METHOD_POST, $uri);
        foreach ($headers as $headerName => $headerValue) {
            $request = $request->withHeader($headerName, $headerValue);
        }
        if ($body !== null) {
            $request = $request->withBody($body);
        }

        return $this->sendRequest($request);
    }

    protected function sendRequest(RequestInterface $request): ResponseInterface
    {
        if (!$this->test) {
            $response = $this->getHttpClient()->sendRequest($request);
        } else {
            throw new \LogicException('You are in dev env, the API has not been called, try modify your configuration if you are sure...');
        }

        return $response;
    }

    protected function getHttpClient(): HttpClient
    {
        $client = null !== $this->httpClient ? $this->httpClient : HttpClientDiscovery::find();

        $authentication       = new QueryParam(['key' => $this->getAuthKey()]);
        $authenticationPlugin = new AuthenticationPlugin($authentication);

        return new PluginClient($client, [$authenticationPlugin]);
    }

    protected function getAuthKey(): string
    {
        $timeStamp = time();

        return $timeStamp.'-'.md5($this->accountId.$timeStamp.$this->secretKey);
    }
}
