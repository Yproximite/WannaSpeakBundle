<?php

namespace Yproximite\WannaSpeakBundle\Api;

use Http\Client\HttpClient;
use Psr\Http\Message\RequestInterface;
use Http\Discovery\UriFactoryDiscovery;
use Http\Discovery\HttpClientDiscovery;
use Psr\Http\Message\ResponseInterface;
use Http\Discovery\MessageFactoryDiscovery;
use Symfony\Component\HttpFoundation\Response;

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
        $this->httpClient = $httpClient;
    }

    /**
     * @param array $args
     *
     * @return ResponseInterface
     */
    public function createAndSendRequest($args)
    {
        $defaultArgs = [
            'id'     => $this->accountId,
            'key'    => $this->getAuthKey(),
        ];

        $args    = array_merge($defaultArgs, $args);
        $uri     = UriFactoryDiscovery::find()->createUri($this->baseUrl);
        $uri     = $uri->withQuery(http_build_query($args));
        $request = MessageFactoryDiscovery::find()->createRequest(self::DEFAULT_METHOD_POST, $uri);

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
            $data     = ['error' => ['txt' => 'You are in dev env, the API has not been called, try modify your configuration if you are sure...']];
            $jsonData = json_encode($data);
            $response = new Response($jsonData, 200);
        }

        return $response;
    }

    /**
     * @return HttpClient
     */
    protected function getHttpClient()
    {
        if ($this->httpClient === null) {
            $this->httpClient = HttpClientDiscovery::find();
        }

        return $this->httpClient;
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
