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
    protected $test;

    private $httpClient;

    public function __construct(string $accountId, string $secretKey, bool $test = false, ?string $baseUrl = null, ?HttpClientInterface $httpClient = null)
    {
        $this->accountId  = $accountId;
        $this->secretKey  = $secretKey;
        $this->test       = $test;
        $this->httpClient = $httpClient ?? HttpClient::create(['base_uri' => $baseUrl]);
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

        return $this->sendRequest(static::DEFAULT_METHOD_POST, $options);
    }

    protected function sendRequest(string $method, array $options = []): ResponseInterface
    {
        if (!$this->test) {
            $response = $this->httpClient->request($method, '', $options);
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
