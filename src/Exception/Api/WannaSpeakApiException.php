<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Exception\Api;

abstract class WannaSpeakApiException extends \RuntimeException implements WannaSpeakApiExceptionInterface
{
    private $statusCode;

    /**
     * @return static
     */
    public static function create(int $statusCode, string $message)
    {
        switch ($statusCode) {
            case 401:
                if ('Name already Exists' === $message) {
                    throw new SoundNameAlreadyExistsException($statusCode, $message);
                }

                throw new AuthFailedException($statusCode, $message);
            case 402:
                throw new TimeErrorException($statusCode, $message);
            case 403:
                throw new BadAccountException($statusCode, $message);
            case 404:
                throw new UnknownMethodException($statusCode, $message);
            case 405:
                throw new MethodNotImplementedException($statusCode, $message);
            case 406:
                throw new NoDidAvailableForRegionException($statusCode, $message);
            case 407:
                if ('DID not exists or not owned' === $message) {
                    throw new DidNotExistsOrNotOwnedException($statusCode, $message);
                } elseif ('DID already reserved or not tested' === $message) {
                    throw new DidAlreadyReservedException($statusCode, $message);
                }

                throw new UnknownException($statusCode, $message);
            case 410:
                throw new CantUseDidAsDestinationException($statusCode, $message);
            case 500:
                throw new MissingArgumentsException($statusCode, $message);
            case 501:
                throw new UnknownApiException($statusCode, $message);
            default:
                throw new UnknownException(-1, $message);
        }
    }

    public function __construct(int $statusCode, string $message)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
