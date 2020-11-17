<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle;

use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Yproximite\WannaSpeakBundle\Exception\InvalidResponseException;
use Yproximite\WannaSpeakBundle\Exception\TestModeException;
use Yproximite\WannaSpeakBundle\Exception\WannaSpeakApiException;

class HttpClient implements HttpClientInterface
{
    private $accountId;
    private $secretKey;
    private $baseUri;
    private $test;
    private $client;

    public function __construct(
        string $accountId,
        string $secretKey,
        string $baseUri,
        bool $test,
        \Symfony\Contracts\HttpClient\HttpClientInterface $client
    ) {
        $this->accountId = $accountId;
        $this->secretKey = $secretKey;
        $this->baseUri   = $baseUri;
        $this->test      = $test;
        $this->client    = $client;
    }

    public function request(string $api, string $method, array $arguments = []): ResponseInterface
    {
        if ($this->test) {
            throw new TestModeException();
        }

        $response = $this->doRequest($api, $method, $arguments);

        $this->handleResponse($response);

        return $response;
    }

    /**
     * @param array<string,mixed> $arguments Additional WannaSpeak request arguments
     *
     * @throws WannaSpeakApiException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function doRequest(string $api, string $method, array $arguments = []): ResponseInterface
    {
        $formData = new FormDataPart(array_merge($arguments, [
            'id'     => $this->accountId,
            'key'    => $this->getAuthKey(),
            'api'    => $api,
            'method' => $method,
        ]));

        $options = [
            'headers' => $formData->getPreparedHeaders()->toArray(),
            'body'    => $formData->bodyToIterable(),
        ];

        return $this->client->request('POST', '', $options);
    }

    private function getAuthKey(): string
    {
        $timeStamp = time();

        return $timeStamp.'-'.md5($this->accountId.$timeStamp.$this->secretKey);
    }

    /**
     * @throws WannaSpeakApiException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function handleResponse(ResponseInterface $response): void
    {
        $responseData = $response->toArray();

        if (array_key_exists('error', $responseData)) {
            if (null === $responseData['error']) {
                return;
            }

            if (is_string($responseData['error'])) {
                throw WannaSpeakApiException::createUnknown($responseData['error']);
            }

            if (is_array($responseData['error']) && array_key_exists('nb', $responseData['error'])) {
                // Not possible with JSON format, but just in case of...
                if (200 === $responseData['error']['nb']) {
                    return;
                }

                throw WannaSpeakApiException::create($responseData['error']['nb'], $responseData['error']['txt'] ?? 'No message.');
            }

            throw new InvalidResponseException(sprintf('Unable to handle field "error" from the response, value is: "%s".', get_debug_type($responseData['error'])));
        }
    }
}
