<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Exception\Api;

use Yproximite\WannaSpeakBundle\HttpClientInterface;

class MissingArgumentsException extends WannaSpeakApiException
{
    public function getStatusCode(): int
    {
        return HttpClientInterface::CODE_MISSING_ARGUMENTS;
    }
}
