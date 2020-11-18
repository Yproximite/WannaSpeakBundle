<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Exception\Api;

use Yproximite\WannaSpeakBundle\HttpClientInterface;

class DidNotExistsOrNotOwnedException extends WannaSpeakApiException
{
    public function getStatusCode(): int
    {
        return HttpClientInterface::CODE_DID_NOT_EXISTS_OR_NOT_OWNED;
    }
}
