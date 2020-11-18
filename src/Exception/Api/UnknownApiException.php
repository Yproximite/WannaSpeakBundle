<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Exception\Api;

use Yproximite\WannaSpeakBundle\HttpClientInterface;

class UnknownApiException extends WannaSpeakApiException
{
    public function getStatusCode(): int
    {
        return HttpClientInterface::CODE_UNKNOWN_API;
    }
}
