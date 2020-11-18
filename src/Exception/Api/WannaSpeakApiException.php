<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\Exception\Api;

abstract class WannaSpeakApiException extends \RuntimeException implements WannaSpeakApiExceptionInterface
{
    abstract public function getStatusCode(): int;
}
