<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Exception\Api;

use Yproximite\WannaSpeakBundle\HttpClientInterface;

class NoDidAvailableForRegionException extends WannaSpeakApiException
{
    public function getStatusCode(): int
    {
        return HttpClientInterface::CODE_NO_DID_AVAILABLE_FOR_REGION;
    }
}
