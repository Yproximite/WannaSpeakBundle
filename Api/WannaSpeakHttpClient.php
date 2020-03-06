<?php

namespace Yproximite\WannaSpeakBundle\Api;

use Http\Client\Exception\NetworkException;
use Http\Client\HttpClient;
use Http\Client\Common\PluginClient;
use Psr\Http\Message\RequestInterface;
use Http\Discovery\UriFactoryDiscovery;
use Http\Discovery\HttpClientDiscovery;
use Psr\Http\Message\ResponseInterface;
use Http\Message\Authentication\QueryParam;
use Http\Discovery\MessageFactoryDiscovery;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\HttpFoundation\Response;
use Http\Client\Common\Plugin\AuthenticationPlugin;

/**
 * Class WannaSpeakHttpClient
 */
class WannaSpeakHttpClient
{
    const DEFAULT_METHOD_POST = 'POST';

    /**
     * @var $httpClient HttpClient
     */
    private $httpClient;

    /**
     * @var string $accountId
     */
    protected $accountId;

    /**
     * @var string $secretKey
     */
    protected $secretKey;

    /**
     * @var string $baseUrl
     */
    protected $baseUrl;

    /**
     * @var boolean $test
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
     * @param array $args
     *
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

        // Throw Exception to trigger Httplug Retry plugin
        if($response->getStatusCode() !== 200){
            throw new NetworkException('Wannaspeak request error : response code ' . $response->getStatusCode(), $request);
        }

        return $response;
    }

    /**
     * @return HttpClient
     */
    protected function getHttpClient()
    {
        $client = $this->httpClient !== null ? $this->httpClient : HttpClientDiscovery::find();

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
