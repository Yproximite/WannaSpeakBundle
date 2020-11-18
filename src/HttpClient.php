<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\ScopingHttpClient;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Yproximite\WannaSpeakBundle\Exception\Api;
use Yproximite\WannaSpeakBundle\Exception\InvalidResponseException;
use Yproximite\WannaSpeakBundle\Exception\TestModeException;

class HttpClient implements HttpClientInterface
{
    private $accountId;
    private $secretKey;
    private $test;
    private $client;
    private $logger;

    public function __construct(
        string $accountId,
        string $secretKey,
        string $baseUri,
        bool $test,
        \Symfony\Contracts\HttpClient\HttpClientInterface $client,
        ?LoggerInterface $logger = null
    ) {
        $this->accountId = $accountId;
        $this->secretKey = $secretKey;
        $this->test      = $test;
        $this->client    = ScopingHttpClient::forBaseUri($client, $baseUri);
        $this->logger    = $logger ?? new NullLogger();
    }

    public function request(string $api, string $method, array $arguments = []): ResponseInterface
    {
        $this->logger->info('[wanna-speak] Requesting WannaSpeak API {api} with method {method}.', [
            'api'       => $api,
            'method'    => $method,
            'arguments' => $arguments,
        ]);

        if ($this->test) {
            throw new TestModeException();
        }

        $response = $this->doRequest($api, $method, $arguments);

        $this->handleResponse($response);

        return $response;
    }

    /**
     * @throws Api\WannaSpeakApiException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function doRequest(string $api, string $method, array $additionalArguments = []): ResponseInterface
    {
        $formData = new FormDataPart(array_merge($additionalArguments, [
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
     * @throws Api\WannaSpeakApiException
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
                throw new Api\UnknownException($responseData['error']);
            }

            if (is_array($responseData['error']) && array_key_exists('nb', $responseData['error'])) {
                $statusCode = $responseData['error']['nb'];
                // Not possible with JSON format, but just in case of...
                if (200 === $statusCode) {
                    return;
                }

                $message = $responseData['error']['txt'] ?? 'No message.';

                switch ($statusCode) {
                    case static::CODE_AUTH_FAILED:
                        throw new Api\AuthFailedException($message);
                    case static::CODE_BAD_ACCOUNT:
                        throw new Api\BadAccountException($message);
                    case static::CODE_UNKNOWN_METHOD:
                        throw new Api\UnknownMethodException($message);
                    case static::CODE_METHOD_NOT_IMPLEMENTED:
                        throw new Api\MethodNotImplementedException($message);
                    case static::CODE_NO_DID_AVAILABLE_FOR_REGION:
                        throw new Api\NoDidAvailableForRegionException($message);
                    case static::CODE_DID_ALREADY_RESERVED:
                    case static::CODE_DID_NOT_EXISTS_OR_NOT_OWNED:
                        if ('DID not exists or not owned' === $message) {
                            throw new Api\DidNotExistsOrNotOwnedException($message);
                        } elseif ('DID already reserved or not tested' === $message) {
                            throw new Api\DidAlreadyReservedException($message);
                        }

                        throw new Api\UnknownException($message);
                    case static::CODE_CANT_USE_DID_AS_DESTINATION:
                        throw new Api\CantUseDidAsDestinationException($message);
                    case static::CODE_MISSING_ARGUMENTS:
                        throw new Api\MissingArgumentsException($message);
                    case static::CODE_UNKNOWN_API:
                        throw new Api\UnknownApiException($message);
                    case static::CODE_UNKNOWN_ERROR:
                    default:
                        throw new Api\UnknownException($message);
                }
            }

            throw new InvalidResponseException(sprintf('Unable to handle field "error" from the response, value is: "%s".', get_debug_type($responseData['error'])));
        }
    }
}
