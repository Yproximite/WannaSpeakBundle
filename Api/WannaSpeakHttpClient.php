<?php

namespace Yproximite\WannaSpeakBundle\Api;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class WannaSpeakHttpClient
{
    const DEFAULT_METHOD_POST = 'POST';

    protected $accountId;
    protected $secretKey;
    protected $baseUrl;
    protected $test;

    private $httpClient;

    public function __construct(string $accountId, string $secretKey, string $baseUrl, bool $test = false, HttpClientInterface $httpClient = null)
    {
        $this->accountId  = $accountId;
        $this->secretKey  = $secretKey;
        $this->baseUrl    = $baseUrl;
        $this->test       = $test;
        $this->httpClient = $httpClient ?? HttpClient::create();
    }

    public function createAndSendRequest(array $body, array $headers = []): ResponseInterface
    {
        $formData = new FormDataPart(array_merge([
            'id'  => $this->accountId,
            'key' => $this->getAuthKey(),
        ], $body));

        $options = [
            'headers' => array_merge($headers, $formData->getPreparedHeaders()->toArray()),
            'body'    => $formData->bodyToIterable(),
        ];

        return $this->sendRequest(static::DEFAULT_METHOD_POST, $this->baseUrl, $options);
    }

    protected function sendRequest(string $method, string $url, array $options = []): ResponseInterface
    {
        if (!$this->test) {
            $response = $this->httpClient->request($method, $url, $options);
        } else {
            throw new \LogicException('The configuration "wanna_speak.api.test" is set to "false", the request has not been sent to the WannaSpeak API.');
        }

        return $response;
    }

    protected function getAuthKey(): string
    {
        $timeStamp = time();

        return $timeStamp.'-'.md5($this->accountId.$timeStamp.$this->secretKey);
    }
}
