<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle;

use Symfony\Contracts\HttpClient\ResponseInterface;
use Yproximite\WannaSpeakBundle\Exception\TestModeException;

interface HttpClientInterface
{
    public const CODE_SUCCESS                     = 200;
    public const CODE_AUTH_FAILED                 = 401;
    public const CODE_BAD_ACCOUNT                 = 403;
    public const CODE_UNKNOWN_METHOD              = 404;
    public const CODE_METHOD_NOT_IMPLEMENTED      = 405;
    public const CODE_NO_DID_AVAILABLE_FOR_REGION = 406;
    public const CODE_DID_ALREADY_RESERVED        = 407;
    public const CODE_CANT_USE_DID_AS_DESTINATION = 410;
    public const CODE_MISSING_ARGUMENTS           = 500;
    public const CODE_UNKNOWN_API                 = 501;
    public const CODE_UNKNOWN_ERROR               = -1; // when WannaSpeak returns a string for "error"

    /**
     * @param array<string,mixed> $arguments Additional WannaSpeak request arguments
     *
     * @throws TestModeException
     */
    public function request(string $api, string $method, array $additionalArguments = []): ResponseInterface;
}
