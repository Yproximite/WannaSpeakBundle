<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Api;

use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\UriFactoryDiscovery;
use Http\Message\Authentication\QueryParam;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class WannaSpeakHttpClient
 */
class WannaSpeakHttpClient
{
    const DEFAULT_METHOD_POST = 'POST';

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var string
     */
    protected $accountId;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var bool
     */
    protected $test;

    /**
     * __construct
     *
     * @param string     $accountId
     * @param string     $secretKey
     * @param string     $baseUrl
     * @param bool       $test
     * @param HttpClient $httpClient
     */
    public function __construct($accountId, $secretKey, $baseUrl, $test = false, HttpClient $httpClient = null)
    {
        $this->accountId  = $accountId;
        $this->secretKey  = $secretKey;
        $this->baseUrl    = $baseUrl;
        $this->test       = $test;
        $this->httpClient = $httpClient;
    }

    /**
     * @param array                                $args
     * @param array                                $headers
     * @param resource|string|StreamInterface|null $body
     *
     * @return ResponseInterface
     */
    public function createAndSendRequest($args, $headers = [], $body = null)
    {
        $defaultArgs = [
            'id' => $this->accountId,
        ];

        $args    = array_merge($defaultArgs, $args);
        $uri     = UriFactoryDiscovery::find()->createUri($this->baseUrl);
        $uri     = $uri->withQuery(http_build_query($args));
        $request = MessageFactoryDiscovery::find()->createRequest(self::DEFAULT_METHOD_POST, $uri, $headers, $body);

        return $this->sendRequest($request);
    }

    /**
     * @param RequestInterface $request
     *
     * @return array|Response|null
     */
    protected function sendRequest($request)
    {
        if (!$this->test) {
            $response = $this->getHttpClient()->sendRequest($request);
        } else {
            throw new \LogicException('You are in dev env, the API has not been called, try modify your configuration if you are sure...');
        }

        return $response;
    }

    /**
     * @return HttpClient
     */
    protected function getHttpClient()
    {
        $client = null !== $this->httpClient ? $this->httpClient : HttpClientDiscovery::find();

        $authentication       = new QueryParam(['key' => $this->getAuthKey()]);
        $authenticationPlugin = new AuthenticationPlugin($authentication);

        return new PluginClient($client, [$authenticationPlugin]);
    }

    /**
     * Return your Authentication key
     *
     * @return string
     */
    protected function getAuthKey()
    {
        $timeStamp = time();

        return $timeStamp.'-'.md5($this->accountId.$timeStamp.$this->secretKey);
    }
}
